<?php

namespace App\Http\Controllers;

use App\Models\BankFinancer;
use App\Models\BikeBrand;
use App\Models\BikeColor;
use App\Models\BikeDealer;
use App\Models\BikeModel;
use App\Models\Sale;
use App\Models\Branch;
use App\Models\City;
use App\Models\District;
use App\Models\Purchase;
use App\Models\Quotation;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            return view('admin.sales.index');
        } else {

            $data = Sale::select('*')
                ->with([
                    'branch' => function ($model) {
                        $model->select('id', 'branch_name');
                    },
                    'dealer' => function ($model) {
                        $model->select('id', 'company_name');
                    },
                    'brand' => function ($model) {
                        $model->select('id', 'name');
                    },
                    'model' => function ($model) {
                        $model->select('id', 'model_name');
                    },
                    'modelColor' => function ($model) {
                        $model->select('id', 'color_name');
                    }
                ]);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return $this->getActions($row);
                })
                ->addColumn('sale_id', function ($row) {
                    return $row->uuid;
                })
                ->addColumn('branch.branch_name', function ($row) {
                    if ($row->branch) {
                        return $row->branch->branch_name;
                    } else {
                        return 'N/A';
                    }
                })
                ->addColumn('dealer.company_name', function ($row) {
                    if ($row->dealer) {
                        return $row->dealer->company_name;
                    } else {
                        return 'N/A';
                    }
                })
                ->addColumn('brand.name', function ($row) {
                    if ($row->brand) {
                        return $row->brand->name;
                    } else {
                        return 'N/A';
                    }
                })
                ->addColumn('model.model_name', function ($row) {
                    if ($row->model) {
                        return $row->model->model_name;
                    } else {
                        return 'N/A';
                    }
                })
                ->addColumn('model_color.color_name', function ($row) {
                    if ($row->modelColor) {
                        return $row->modelColor->color_name;
                    } else {
                        return 'N/A';
                    }
                })
                ->addColumn('sale_invoice_amount', function ($row) {
                    return '₹' . $row->sale_invoice_amount;
                })
                ->rawColumns([
                    'action', 'sale_id', 'branch.branch_name', 'dealer.company_name', 'brand.name',
                    'model.model_name', 'model_color.color_name', 'sale_invoice_amount'
                ])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = array(
            'branches' => Branch::where('active_status', '1')->select('id', 'branch_name')->get(),
            'dealers' => BikeDealer::where('active_status', '1')->select('id', 'company_name')->get(),
            'states' => State::where('active_status', '1')->select('id', 'state_name')->get(),
            'brands' => BikeBrand::where('active_status', '1')->select('id', 'name')->get(),
            'colors' => BikeColor::where('active_status', '1')->select('id', 'color_name')->get(),
            'bank_financers' => BankFinancer::select('id', 'bank_name')->where('active_status', '1')->get(),
            'purchases' => Purchase::select('id', 'dc_number', 'vin_number', 'sku')->where('status', '1')->get(),
            // 'quotations' => Quotation::select('*')->get(),
            'bike_types' => bike_types(),
            'bike_fuel_types' => bike_fuel_types(),
            'break_types' => break_types(),
            'wheel_types' => wheel_types(),
            'vin_physical_statuses' => vin_physical_statuses(),
            //Other Important Data
            'method' => 'POST',
            'action' => route('sales.store')
        );

        if (!empty(request('q'))) {
            $quotation = Quotation::select('*')->find(request('q'));


            $data['data'] = $quotation;
            $data['bike_branch'] = $quotation->branch_id;
            $data['bike_model_color'] = $quotation->bike_color;

            // return $quotation;

            $data['models'] = BikeModel::select('*')->where('brand_id', $quotation->bike_brand)->get();
            $data['colors'] = BikeColor::select('*')->where('bike_model', $quotation->bike_model)->get();
            $data['districts'] = District::select('*')->where('state_id', $quotation->customer_state)->get();
            $data['cities'] = City::select('*')->where('district_id', $quotation->customer_district)->get();
        }

        return view('admin.sales.create', $data);
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
        $validator = Validator::make($postData, [
            'bike_branch' => 'nullable',
            'bike_dealer' => 'nullable',
            'bike_brand' => 'nullable',
            'bike_model' => 'nullable',
            'bike_color' => 'nullable',
            'bike_type' => 'nullable',
            'bike_fuel_type' => 'nullable',
            'break_type' => 'nullable',
            'wheel_type' => 'nullable',
            'dc_number' => 'nullable',
            'dc_date' => 'nullable',
            'vin_number' => 'nullable',
            'vin_physical_status' => 'nullable',
            'sku' => 'nullable',
            'sku_description' => 'nullable',
            'hsn_number' => 'nullable',
            'engine_number' => 'nullable',
            'key_number' => 'nullable',
            'service_book_number' => 'nullable',
            'tyre_brand_name' => 'nullable',
            'tyre_front_number' => 'nullable',
            'tyre_rear_number' => 'nullable',
            'battery_brand' => 'nullable',
            'battery_number' => 'nullable',
            'purchase_invoice_number' => 'nullable',
            'purchase_invoice_amount' => 'nullable',
            'purchase_invoice_date' => 'nullable',
            'bike_description' => 'nullable',
            'pre_gst_amount' => 'nullable',
            'gst_amount' => 'nullable',
            'discount_price' => 'nullable',
            'grand_total' => 'nullable',
            'branch_id' => 'nullable',
            'customer_first_name' => 'nullable',
            'customer_middle_name' => 'nullable',
            'customer_last_name' => 'nullable',
            'customer_father_name' => 'nullable',
            'customer_address_line' => 'nullable',
            'customer_state' => 'nullable',
            'customer_district' => 'nullable',
            'customer_city' => 'nullable',
            'customer_zipcode' => 'nullable',
            'customer_mobile_number' => 'nullable',
            'customer_email_address' => 'nullable',
            'payment_type' => 'nullable',
            'is_exchange_avaliable' => 'nullable',
            'hyp_financer' => 'nullable',
            'hyp_financer_description' => 'nullable',
            'purchase_visit_date' => 'nullable',
            'purchase_est_date' => 'nullable',
            'ex_showroom_price' => 'nullable',
            'registration_amount' => 'nullable',
            'insurance_amount' => 'nullable',
            'hypothecation_amount' => 'nullable',
            'accessories_amount' => 'nullable',
            'other_charges' => 'nullable',
            'total_amount' => 'nullable',
            'active_status' => 'nullable',
            'status' => 'nullable',
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

        $postData['uuid'] = random_uuid('sale');
        $postData['created_by'] = Auth::user()->id;

        //Create Sale
        Sale::create($postData);

        //Mark Status Closed If Purchase With Quotation
        if (isset($postData['quotation_id']) && intval($postData['quotation_id']) > 0) {
            Quotation::where('id', $postData['quotation_id'])->update('status', 'close');
        }

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Created Successfully."
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
        $bpModel = Sale::where(['uuid' => $id])->first();
        if (!$bpModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => "Sorry! This id($id) not exist"
            ]);
        }

        $models = BikeModel::where(['brand_id' => $bpModel->bike_brand])->get()->toArray();
        $editModelsHtml = models_list($models, $bpModel->bike_model);

        $data = array(
            'branches' => Branch::where('active_status', '1')->select('id', 'branch_name')->get(),
            'dealers' => BikeDealer::where('active_status', '1')->select('id', 'company_name')->get(),
            'brands' => BikeBrand::where('active_status', '1')->select('id', 'name')->get(),
            'colors' => BikeColor::where('active_status', '1')->select('id', 'color_name')->get(),
            'bike_types' => bike_types(),
            'bike_fuel_types' => bike_fuel_types(),
            'break_types' => break_types(),
            'wheel_types' => wheel_types(),
            'vin_physical_statuses' => vin_physical_statuses(),
            //Other Important Data
            'action' => route('sales.update', ['sale' => $id]),
            'editModelsHtml' => $editModelsHtml,
            'data'   => $bpModel,
            'method' => 'PUT',
        );
        return view('admin.sales.create', $data);
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
        $bpModel = Sale::where(['uuid' => $id]);
        if (!$bpModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => "Sorry! This id($id) not exist"
            ]);
        }

        $postData = $request->all();
        $validator = Validator::make($postData, [
            'bike_branch' => 'nullable',
            'bike_dealer' => 'nullable',
            'bike_brand' => 'nullable',
            'bike_model' => 'nullable',
            'bike_model_color' => 'nullable',
            'bike_type' => 'nullable',
            'bike_fuel_type' => 'nullable',
            'break_type' => 'nullable',
            'wheel_type' => 'nullable',
            'dc_number' => 'nullable',
            'dc_date' => 'nullable',
            'vin_number' => 'nullable',
            'vin_physical_status' => 'nullable',
            'sku' => 'nullable',
            'sku_description' => 'nullable',
            'hsn_number' => 'nullable',
            'engine_number' => 'nullable',
            'key_number' => 'nullable',
            'service_book_number' => 'nullable',
            'tyre_brand_name' => 'nullable',
            'tyre_front_number' => 'nullable',
            'tyre_rear_number' => 'nullable',
            'battery_brand' => 'nullable',
            'battery_number' => 'nullable',
            'purchase_invoice_number' => 'nullable',
            'purchase_invoice_amount' => 'nullable',
            'purchase_invoice_date' => 'nullable',
            'bike_description' => 'nullable',
            'pre_gst_amount' => 'nullable',
            'gst_amount' => 'nullable',
            'discount_price' => 'nullable',
            'grand_total' => 'nullable',
            'branch_id' => 'nullable',
            'customer_first_name' => 'nullable',
            'customer_middle_name' => 'nullable',
            'customer_last_name' => 'nullable',
            'customer_father_name' => 'nullable',
            'customer_address_line' => 'nullable',
            'customer_state' => 'nullable',
            'customer_district' => 'nullable',
            'customer_city' => 'nullable',
            'customer_zipcode' => 'nullable',
            'customer_mobile_number' => 'nullable',
            'customer_email_address' => 'nullable',
            'payment_type' => 'nullable',
            'is_exchange_avaliable' => 'nullable',
            'hyp_financer' => 'nullable',
            'hyp_financer_description' => 'nullable',
            'purchase_visit_date' => 'nullable',
            'purchase_est_date' => 'nullable',
            'ex_showroom_price' => 'nullable',
            'registration_amount' => 'nullable',
            'insurance_amount' => 'nullable',
            'hypothecation_amount' => 'nullable',
            'accessories_amount' => 'nullable',
            'other_charges' => 'nullable',
            'total_amount' => 'nullable',
            'active_status' => 'nullable',
            'status' => 'nullable',
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

        $postData['updated_by'] = Auth::user()->id;
        unset($postData['_token']);
        unset($postData['_method']);
        //Create New Role
        $bpModel->update($postData);
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
        $action .= '<a href="' . route('sales.edit', ['sale' => $row->uuid]) . '" class="btn btn-sm btn-warning" data-modal_title="Update Sale"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        // $action .= '<a href="' . route('sales.destroy', ['sale' => $row->id]) . '" data-id="' . $row->id . '" class="btn btn-sm btn-danger ajaxModalDelete" data-modal_title="Delete Sale"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
        $action .= '</div>';
        return $action;
    }

    public function getModelsList($id)
    {
        $models = BikeModel::where('active_status', '1')->where(['brand_id' => $id])->get()->toArray();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Retrived Successfully.",
            'data'       => models_list($models)
        ]);
    }
}
