<?php

namespace App\Http\Controllers;

use App\Models\BankFinancer;
use App\Models\BikeBrand;
use App\Models\BikeColor;
use App\Models\BikeModel;
use App\Models\Branch;
use App\Models\City;
use App\Models\District;
use App\Models\Quotation;
use App\Models\State;
use App\Models\User;
use App\Traits\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationController extends Controller
{
    use CommonHelper;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auth = User::find(auth()->id());
        if (!request()->ajax()) {
            return view('admin.quotations.index');
        } else {
            $data = Quotation::select('*')->with([
                'state' => function ($s) {
                    $s->select('id', 'state_name');
                },
                'district' => function ($s) {
                    $s->select('id', 'district_name');
                },
                'city' => function ($s) {
                    $s->select('id', 'city_name');
                },
                'brand' => function ($s) {
                    $s->select('id', 'name');
                },
                'model' => function ($s) {
                    $s->select('id', 'model_name');
                },
                'color' => function ($s) {
                    $s->select('id', 'color_name');
                },
                'financer' => function ($s) {
                    $s->select('id', 'bank_name');
                }
            ]);

            if (!$auth->is_admin) {
                $data->where('branch_id', $auth->branch_id);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    if ($row->status == 'open') {
                        return '<span class="label label-warning">Open</span>';
                    } else {
                        return '<span class="label label-success">Close</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    return $this->getActions($row);
                })
                ->addColumn('bike_detail', function ($row) {
                    $str = '';
                    $str .= (isset($row->brand->name) ? $row->brand->name : '') . ' | ';
                    $str .= (isset($row->model->model_name) ? $row->model->model_name : '') . ' | ';
                    $str .= (isset($row->color->color_name) ? $row->color->color_name : '');
                    return $str;
                })
                ->rawColumns(['status', 'bike_detail', 'action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        config(['type' => true]);
        $auth = User::find(auth()->id());
        $formData = [];
        $formData['method'] = 'POST';
        $formData['branches'] = self::_getbranches(!$auth->is_admin);
        $formData['brands'] = self::_getbrands(!$auth->is_admin);
        $formData['models'] = self::_getmodels(config('brand_id'), !$auth->is_admin);
        $formData['states'] = self::_getStates();
        $formData['action'] = route('quotations.store');
        $formData['bank_financers'] = self::_getFinaceirs();
        $formData['method'] = 'POST';
        return view('admin.quotations.create', $formData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $postData = $request->all();
            // dd($postData);
            $validator = Validator::make($postData, [
                'customer_gender'           => "required|string",
                'customer_name'             => "required|string",
                'customer_relationship'     => "required|string",
                'customer_guardian_name'    => "required|string",
                'customer_address_line'     => "required|string",
                'customer_state'            => "required",
                'customer_district'         => "required",
                'customer_city'             => "required",
                'customer_zipcode'          => "required|numeric",
                'customer_mobile_number'    => "required",
                'customer_email_address'    => "nullable",
                'payment_type'              => "required",
                'is_exchange_avaliable'     => "required",
                'hyp_financer'              => "nullable",
                'hyp_financer_description'  => "nullable",
                'purchase_visit_date'       => "required|date",
                'purchase_est_date'         => "required|date",
                'bike_brand'                => "required",
                'bike_model'                => "required",
                'bike_color'                => "required",
                'ex_showroom_price'         => "required|numeric",
                'registration_amount'       => "required|numeric",
                'insurance_amount'          => "required|numeric",
                'hypothecation_amount'      => "required|numeric",
                'accessories_amount'        => "required|numeric",
                'other_charges'             => "nullable|numeric",
                'total_amount'              => "required|numeric",
                // 'active_status'             => "required|in:0,1"
            ]);

            //If Validation failed
            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ]);
            }

            unset($postData['_token']);

            $postData['created_by'] = Auth::user()->id;

            //Create
            $createModel = Quotation::create($postData);
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => "Created Successfully.",
                'data'       => $createModel
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => $e->getMessage(),
                'data'       => ['file' => $e->getFile(), 'line' => $e->getLine()]
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $auth = User::find(auth()->id());
        $quotModel = Quotation::find($id);
        if (!$quotModel) {
            return response()->json(['status' => false, 'statusCode' => 419, 'message' => "Sorry! This id($id) not exist"]);
        }

        $formData = [];
        $formData['branches'] = self::_getBranches(!$auth->is_admin);
        $formData['states'] = self::_getStates(!$auth->is_admin);
        $formData['districts'] = self::_getDistricts($quotModel->customer_state, !$auth->is_admin);
        $formData['cities'] = self::_getCities($quotModel->customer_district, !$auth->is_admin);
        $formData['brands'] = self::_getBrands(!$auth->is_admin, $quotModel->branch_id);
        $formData['models'] = self::_getModels($quotModel->bike_brand, !$auth->is_admin);
        $formData['colors'] = self::_getColors($quotModel->bike_model, !$auth->is_admin);
        $formData['bank_financers'] = self::_getFinaceirs();
        $formData['action'] = route('quotations.store');
        $formData['data']  = $quotModel;
        $formData['action'] = route('quotations.update', ['quotation' => $id]);
        $formData['method'] = 'PUT';

        return view('admin.quotations.create', $formData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $quotModel = Quotation::find($id);
            if (!$quotModel) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => "Sorry! This id($id) not exist"
                ]);
            }

            $postData = $request->all();
            $validator = Validator::make($postData, [
                'customer_gender'           => "required|string",
                'customer_name'             => "required|string",
                'customer_relationship'     => "required|string",
                'customer_guardian_name'    => "required|string",
                'customer_address_line'     => "required|string",
                'customer_state'            => "required",
                'customer_district'         => "required",
                'customer_city'             => "required",
                'customer_zipcode'          => "required|numeric",
                'customer_mobile_number'    => "required",
                'customer_email_address'    => "nullable",
                'payment_type'              => "required",
                'is_exchange_avaliable'     => "required",
                'hyp_financer'              => "nullable",
                'hyp_financer_description'  => "nullable",
                'purchase_visit_date'       => "required|date",
                'purchase_est_date'         => "required|date",
                'bike_brand'                => "required",
                'bike_model'                => "required",
                'bike_color'                => "required",
                'ex_showroom_price'         => "required|numeric",
                'registration_amount'       => "required|numeric",
                'insurance_amount'          => "required|numeric",
                'hypothecation_amount'      => "required|numeric",
                'accessories_amount'        => "required|numeric",
                'other_charges'             => "nullable|numeric",
                'total_amount'              => "required|numeric"
            ]);

            //If Validation failed
            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors()
                ]);
            }

            unset($postData['_token']);
            unset($postData['_method']);
            $postData['updated_by'] = Auth::user()->id;

            //Update Quotation
            $quotModel->update($postData);
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => "Updated Successfully."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => $e->getMessage(),
                'data'       => ['file' => $e->getFile(), 'line' => $e->getLine()]
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getActions($row)
    {
        $action = '<div class="action-btn-container">';
        $action .= '<a title="Update Quotation" href="' . route('quotations.edit', ['quotation' => $row->id]) . '" class="btn btn-sm btn-warning"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        $action .= '<a title="Print Quotation" href="' . route('print-quotation', ['id' => $row->id]) . '" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-print" aria-hidden="true"></i></a>';

        if ($row->status == 'open') {
            $action .= '<a title="Create Sale" href="' . route('sales.create') . "?q=$row->id" . '" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-recycle" aria-hidden="true"></i></a>';
        }
        // $action .= '<a href="' . route('purchases.destroy', ['purchase' => $row->id]) . '" data-id="' . $row->id . '" class="btn btn-sm btn-danger ajaxModalDelete" data-modal_title="Delete Purchase"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
        $action .= '</div>';
        return $action;
    }


    public function printQuotation(Request $request, $id)
    {
        $quotationModel = Quotation::with([
            'branch'
        ])->where(['id' => $id])->first();
        // return view('admin.quotations.invoice-print');
        $pdf = Pdf::loadView('admin.quotations.invoice-print', ['data' => $quotationModel]);
        return $pdf->stream('invoice.pdf');
    }

    public function getQuotationDetails($id)
    {
        $models = Quotation::find($id);
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Retrived Successfully.",
            'data'       => $models
        ]);
    }
}
