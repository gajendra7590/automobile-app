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
        $auth = User::find(auth()->id());
        $formData = [];
        $formData['method'] = 'POST';
        $formData['branches'] = self::_getbranches();
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
                'branch_id'                 => "required|exists:branches,id",
                'customer_gender'           => "required|in:1,2,3",
                'customer_name'             => "required|string",
                'customer_relationship'     => "required|in:1,2,3",
                'customer_guardian_name'    => "required|string",
                'customer_address_line'     => "required|string",
                'customer_state'            => "required|exists:u_states,id",
                'customer_district'         => "required|exists:u_districts,id",
                'customer_city'             => "required|exists:u_cities,id",
                'customer_zipcode'          => "required|numeric",
                'customer_mobile_number'    => "required|numeric|min:10",
                'customer_email_address'    => "nullable|email",
                'payment_type'              => "required|in:1,2,3",
                'is_exchange_avaliable'     => "required|in:Yes,No",
                'hyp_financer'              => "nullable|exists:bank_financers,id",
                'hyp_financer_description'  => "nullable|string",
                'bike_brand'                => "required|exists:bike_brands,id",
                'bike_model'                => "required|exists:bike_models,id",
                'bike_color'                => "required|exists:bike_colors,id",
                'ex_showroom_price'         => "required|numeric",
                'registration_amount'       => "required|numeric",
                'insurance_amount'          => "required|numeric",
                'hypothecation_amount'      => "required|numeric",
                'accessories_amount'        => "required|numeric",
                'other_charges'             => "nullable|numeric",
                'total_amount'              => "required|numeric",
                'purchase_visit_date'       => "required|date",
                'purchase_est_date'         => "required|date",
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

            //Add Check For Add
            if (Auth::user()->is_admin == '0' && (Auth::user()->branch_id != $postData['branch_id'])) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    =>  trans('messages.not_authorized_user'),
                ]);
            }

            unset($postData['_token']);
            $postData['created_by'] = Auth::user()->id;

            //Create
            $createModel = Quotation::create($postData);
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.create_success'),
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
            return response()->json(['status' => false, 'statusCode' => 419, 'message' => trans('messages.id_not_exist', ['id' => $id])]);
        }

        $formData = [];

        $formData['states'] = self::_getStatesByCountry();
        $formData['districts'] = self::_getDistrictsByStateId($quotModel->customer_state);
        $formData['cities'] = self::_getCitiesByDistrictId($quotModel->customer_district);

        $formData['branches'] = self::_getBranchById($quotModel->branch_id);
        $formData['brands'] = self::_getBrandByBranch($quotModel->branch_id);
        $formData['models'] = self::_getModelByBrand($quotModel->bike_brand);
        $formData['colors'] = self::_getColorByModel($quotModel->bike_model);

        $formData['bank_financers'] = self::_getFinaceirs(($quotModel->financer_type - 1));
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
                    'message'    => trans('messages.id_not_exist', ['id' => $id])
                ]);
            }

            $postData = $request->all();
            $validator = Validator::make($postData, [
                'branch_id'                 => "nullable|exists:branches,id",
                'customer_gender'           => "nullable|in:1,2,3",
                'customer_name'             => "nullable|string",
                'customer_relationship'     => "nullable|in:1,2,3",
                'customer_guardian_name'    => "nullable|string",
                'customer_address_line'     => "nullable|string",
                'customer_state'            => "required|exists:u_states,id",
                'customer_district'         => "required|exists:u_districts,id",
                'customer_city'             => "required|exists:u_cities,id",
                'customer_zipcode'          => "required|numeric",
                'customer_mobile_number'    => "required|numeric|min:10",
                'customer_email_address'    => "nullable|email",
                'payment_type'              => "required|in:1,2,3",
                'is_exchange_avaliable'     => "required|in:Yes,No",
                'hyp_financer'              => "nullable|exists:bank_financers,id",
                'hyp_financer_description'  => "nullable|string",
                'bike_brand'                => "nullable|exists:bike_brands,id",
                'bike_model'                => "required|exists:bike_models,id",
                'bike_color'                => "required|exists:bike_colors,id",
                'ex_showroom_price'         => "required|numeric",
                'registration_amount'       => "required|numeric",
                'insurance_amount'          => "required|numeric",
                'hypothecation_amount'      => "required|numeric",
                'accessories_amount'        => "required|numeric",
                'other_charges'             => "nullable|numeric",
                'total_amount'              => "required|numeric",
                'purchase_visit_date'       => "required|date",
                'purchase_est_date'         => "required|date",
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

            //Add Check For Add
            if (Auth::user()->is_admin == '0' && (Auth::user()->branch_id != $quotModel->branch_id)) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    =>  trans('messages.not_authorized_user'),
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
                'message'    => trans('messages.update_success')
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
            'message'    => trans('messages.retrieve_success'),
            'data'       => $models
        ]);
    }
}
