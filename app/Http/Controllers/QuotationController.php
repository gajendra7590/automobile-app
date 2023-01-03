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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            return view('admin.quotations.index');
        } else {
            $data = Quotation::with([
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
            ])->select('*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('active_status', function ($row) {
                    if ($row->active_status == '1') {
                        return '<span class="label label-success">Active</span>';
                    } else {
                        return '<span class="label label-warning">In Active</span>';
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
                ->rawColumns(['active_status', 'bike_detail', 'action'])
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

        $formData = array(
            'branches' => Branch::where('active_status', '1')->select('id', 'branch_name')->get(),
            'states' => State::where('active_status', '1')->select('id', 'state_name')->get(),
            'brands' => BikeBrand::where('active_status', '1')->select('id', 'name')->get(),
            'action' => route('quotations.store'),
            'bank_financers' => BankFinancer::select('id', 'bank_name')->where('active_status', '1')->get()
        );

        return view('admin.quotations.create', $formData);

        // return response()->json([
        //     'status'     => true,
        //     'statusCode' => 200,
        //     'message'    => 'AjaxModal Loaded',
        //     'data'       => view('admin.quotations.ajaxModal', $formData)->render()
        // ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
            'purchase_visit_date'       => "nullable|date",
            'purchase_est_date'         => "nullable|date",
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

        //Create New Role
        $quotation = Quotation::create($postData);
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Created Successfully.",
            'data'       => $quotation
        ]);
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
        $quotModel = Quotation::find($id);
        if (!$quotModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => "Sorry! This id($id) not exist"
            ]);
        }

        $formData = array(
            'branches' => Branch::where('active_status', '1')->select('id', 'branch_name')->get(),
            'states' => State::where('active_status', '1')->select('id', 'state_name')->get(),
            'districts' => District::where('active_status', '1')->where('state_id', $quotModel->customer_state)->select('id', 'district_name')->get(),
            'cities' => City::where('active_status', '1')->where('district_id', $quotModel->customer_district)->select('id', 'city_name')->get(),

            'brands' => BikeBrand::where('active_status', '1')->select('id', 'name')->get(),
            'models' => BikeModel::where('active_status', '1')->where('brand_id', $quotModel->bike_brand)->select('id', 'model_name')->get(),
            'colors' => BikeColor::where('active_status', '1')->select('id', 'color_name')->get(),

            'action' => route('quotations.store'),
            'bank_financers' => BankFinancer::select('id', 'bank_name')->where('active_status', '1')->get(),
            'data'  => $quotModel,
            'action' => route('quotations.update', ['quotation' => $id]),
            'method' => 'PUT',
        );

        return view('admin.quotations.create', $formData);

        // return response()->json([
        //     'status'     => true,
        //     'statusCode' => 200,
        //     'message'    => 'AjaxModal Loaded',
        //     'data'       => view('admin.quotations.create', $formData)->render()
        // ]);
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
            'purchase_visit_date'       => "nullable|date",
            'purchase_est_date'         => "nullable|date",
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
        $action .= '<a title="Update Quotation" href="' . route('quotations.edit', ['quotation' => $row->id]) . '" data-modal_size="modal-lg" class="btn btn-sm btn-warning ajaxModalPopup" data-modal_title="Quotation Update"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        $action .= '<a title="Print Quotation" href="' . route('print-quotation', ['id' => $row->id]) . '" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-print" aria-hidden="true"></i></a>';
        $action .= '<a title="Create Sale" href="' . route('sales.create') . "?q=$row->id" . '" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-recycle" aria-hidden="true"></i></a>';

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
