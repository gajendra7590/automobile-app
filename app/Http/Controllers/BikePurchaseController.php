<?php

namespace App\Http\Controllers;

use App\Models\BikeBrand;
use App\Models\BikeColor;
use App\Models\BikeDealer;
use App\Models\BikeModel;
use App\Models\BikePurchased;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BikePurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            return view('admin.purchases.index');
        } else {

            $data = BikePurchased::select('*')
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
                ->addColumn('purchase_id', function ($row) {
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
                ->addColumn('purchase_invoice_amount', function ($row) {
                    return '₹' . $row->purchase_invoice_amount;
                })
                ->rawColumns([
                    'action', 'purchase_id', 'branch.branch_name', 'dealer.company_name', 'brand.name',
                    'model.model_name', 'model_color.color_name', 'purchase_invoice_amount'
                ])
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
        $data = array(
            'branches' => Branch::select('id', 'branch_name')->get(),
            'dealers' => BikeDealer::select('id', 'company_name')->get(),
            'brands' => BikeBrand::select('id', 'name')->get(),
            'colors' => BikeColor::select('id', 'color_name')->get(),
            'bike_types' => bike_types(),
            'bike_fuel_types' => bike_fuel_types(),
            'break_types' => break_types(),
            'wheel_types' => wheel_types(),
            'vin_physical_statuses' => vin_physical_statuses(),
            //Other Important Data
            'method' => 'POST',
            'action' => route('purchases.store')
        );

        // return $data;
        return view('admin.purchases.create', $data);
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
            'bike_branch'               => "required",
            'bike_dealer'               => "required",
            'bike_brand'                => "required",
            'bike_model'                => "required",
            'bike_model_color'          => "required",
            'bike_type'                 => "required",
            'bike_fuel_type'            => "required",
            'break_type'                => "required",
            'wheel_type'                => "required",
            'dc_number'                 => "nullable",
            'dc_date'                   => "nullable",
            'vin_number'                => "nullable",
            'vin_physical_status'       => "nullable",
            'sku'                       => "nullable",
            'sku_description'           => "nullable",
            'hsn_number'                => "nullable",
            'model_number'              => "nullable",
            'engine_number'             => "nullable",
            'key_number'                => "nullable",
            'service_book_number'       => "nullable",
            'tyre_brand_name'           => "nullable",
            'tyre_front_number'         => "nullable",
            'tyre_rear_number'          => "nullable",
            'battery_brand'             => "nullable",
            'battery_number'            => "nullable",
            'purchase_invoice_number'   => "required",
            'purchase_invoice_amount'   => "required|numeric",
            'purchase_invoice_date'     => "required|date",
            'final_price'               => "required|numeric",
            'sale_price'                => "required|numeric",
            'bike_description'          => "required",
            'status'                    => "nullable|in:1,2"
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


        $postData['uuid'] = random_uuid('purc');
        $postData['created_by'] = Auth::user()->id;

        //Create New Role
        BikePurchased::create($postData);
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
        $bpModel = BikePurchased::where(['uuid' => $id])->first();
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
            'branches' => Branch::select('id', 'branch_name')->get(),
            'dealers' => BikeDealer::select('id', 'company_name')->get(),
            'brands' => BikeBrand::select('id', 'name')->get(),
            'colors' => BikeColor::select('id', 'color_name')->get(),
            'bike_types' => bike_types(),
            'bike_fuel_types' => bike_fuel_types(),
            'break_types' => break_types(),
            'wheel_types' => wheel_types(),
            'vin_physical_statuses' => vin_physical_statuses(),
            //Other Important Data
            'action' => route('purchases.update', ['purchase' => $id]),
            'editModelsHtml' => $editModelsHtml,
            'data'   => $bpModel,
            'method' => 'PUT',
        );
        return view('admin.purchases.create', $data);
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
        $bpModel = BikePurchased::where(['uuid' => $id]);
        if (!$bpModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => "Sorry! This id($id) not exist"
            ]);
        }

        $postData = $request->all();
        $validator = Validator::make($postData, [
            'bike_branch'               => "required",
            'bike_dealer'               => "required",
            'bike_brand'                => "required",
            'bike_model'                => "required",
            'bike_model_color'          => "required",
            'bike_type'                 => "required",
            'bike_fuel_type'            => "required",
            'break_type'                => "required",
            'wheel_type'                => "required",
            'dc_number'                 => "nullable",
            'dc_date'                   => "nullable",
            'vin_number'                => "nullable",
            'vin_physical_status'       => "nullable",
            'sku'                       => "nullable",
            'sku_description'           => "nullable",
            'hsn_number'                => "nullable",
            'model_number'              => "nullable",
            'engine_number'             => "nullable",
            'key_number'                => "nullable",
            'service_book_number'       => "nullable",
            'tyre_brand_name'           => "nullable",
            'tyre_front_number'         => "nullable",
            'tyre_rear_number'          => "nullable",
            'battery_brand'             => "nullable",
            'battery_number'            => "nullable",
            'purchase_invoice_number'   => "required",
            'purchase_invoice_amount'   => "required|numeric",
            'purchase_invoice_date'     => "required|date",
            'final_price'               => "required|numeric",
            'sale_price'                => "required|numeric",
            'bike_description'          => "required",
            'status'                    => "nullable|in:1,2"
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

        $postData['uuid'] = random_uuid('purc');
        $postData['updated_by'] = Auth::user()->id;
        //Create New Role
        BikePurchased::create($postData);
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
        $action .= '<a href="' . route('purchases.edit', ['purchase' => $row->uuid]) . '" class="btn btn-sm btn-warning" data-modal_title="Update Purchase"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        // $action .= '<a href="' . route('purchases.destroy', ['purchase' => $row->id]) . '" data-id="' . $row->id . '" class="btn btn-sm btn-danger ajaxModalDelete" data-modal_title="Delete Purchase"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
        $action .= '</div>';
        return $action;
    }

    public function getModelsList($id)
    {
        $models = BikeModel::where(['brand_id' => $id])->get()->toArray();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Retrived Successfully.",
            'data'       => models_list($models)
        ]);
    }
}
