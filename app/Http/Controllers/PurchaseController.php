<?php

namespace App\Http\Controllers;

use App\Models\BikeModel;
use App\Models\Purchase;
use App\Models\User;
use App\Traits\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller
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
            return view('admin.purchases.index');
        } else {
            $data = Purchase::select('*')
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
                ])->when(!$auth->is_admin, function ($q) use ($auth) {
                    $q->where('bike_branch', $auth->branch_id);
                });

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
                ->addColumn('bike_detail', function ($row) {
                    $str = '';
                    if ($row->brand) {
                        $str .= $row->brand->name . ' | ';
                    }
                    if ($row->model) {
                        $str .= $row->model->model_name . ' | ';
                    }
                    if ($row->modelColor) {
                        $str .= $row->modelColor->color_name;
                    }
                    return $str;
                })
                ->addColumn('purchase_invoice_amount', function ($row) {
                    return '₹' . $row->purchase_invoice_amount;
                })
                ->addColumn('grand_total', function ($row) {
                    return '₹' . $row->grand_total;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == '1') {
                        return '<span class="label label-success">IN_STOCK</span>';
                    } else {
                        return '<span class="label label-danger">SOLD_OUT</span>';
                    }
                })
                ->rawColumns([
                    'action', 'purchase_id', 'branch.branch_name', 'dealer.company_name', 'bike_detail', 'purchase_invoice_amount', 'grand_total', 'status'
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
        $auth = User::find(auth()->id());
        $data = [];
        $data['method'] = 'POST';
        $data['branches'] = self::_getbranches(!$auth->is_admin);
        config(['first_brand' => true]);
        $data['brands'] = self::_getbrands(!$auth->is_admin);
        $data['models'] = self::_getmodels(config('brand_id'), !$auth->is_admin);
        $data['dealers'] = self::_getDealers(!$auth->is_admin);
        $data['gst_rates'] = self::_getGstRates(!$auth->is_admin);
        $data['bike_types'] = bike_types();
        $data['bike_fuel_types'] = bike_fuel_types();
        $data['break_types'] = break_types();
        $data['wheel_types'] = wheel_types();
        $data['vin_physical_statuses'] = vin_physical_statuses();
        $data['method'] = 'POST';
        $data['action'] = route('purchases.store');

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
        try {
            $postData = $request->all();
            $validator = Validator::make($postData, [
                'bike_dealer'               => "required|exists:bike_dealers,id",
                'bike_branch'               => "required|exists:branches,id",
                'bike_brand'                => "required|exists:bike_brands,id",
                'bike_model'                => "required|exists:bike_models,id",
                'bike_model_color'          => "required|exists:bike_colors,id",
                'bike_type'                 => "required",
                'bike_fuel_type'            => "required",
                'break_type'                => "required",
                'wheel_type'                => "required",
                'dc_number'                 => "nullable",
                'dc_date'                   => "nullable",
                'vin_number'                => "required|min:17",
                'vin_physical_status'       => "nullable",
                'hsn_number'                => "nullable|min:6",
                'engine_number'             => "nullable|min:14",
                'sku'                       => "nullable",
                'sku_description'           => "nullable",
                'key_number'                => "nullable|",
                'service_book_number'       => "nullable",
                'battery_brand'             => "nullable",
                'battery_number'            => "nullable",
                'tyre_brand_name'           => "nullable",
                'tyre_front_number'         => "nullable",
                'tyre_rear_number'          => "nullable",
                'purchase_invoice_amount'   => "required|numeric",
                'purchase_invoice_number'   => "required",
                'purchase_invoice_date'     => "required|date",
                'status'                    => "nullable|in:1,2",
                //'active_status'             => "required|in:0,1",
                'gst_rate'                  => 'required|numeric',
                'pre_gst_amount'            => 'required|numeric',
                'gst_amount'                => 'required|numeric',
                'ex_showroom_price'         => 'required|numeric',
                'discount_price'            => 'nullable|numeric',
                'grand_total'               => 'required|numeric',
                'bike_description'          => "nullable",
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

            //Create
            $createModel = Purchase::create($postData);
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
        $bpModel = Purchase::where(['uuid' => $id])->first();
        if (!$bpModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => "Sorry! This id($id) not exist"
            ]);
        }
        $data = [];
        config(['first_brand' => true]);
        $data['branches'] = self::_getbranches(!$auth->is_admin);
        $data['dealers'] = self::_getDealers(!$auth->is_admin);
        $data['brands'] = self::_getbrands(!$auth->is_admin,$bpModel->bike_branch);
        $data['models'] = self::_getmodels($bpModel->bike_brand, !$auth->is_admin);
        $data['colors'] = self::_getColors($bpModel->bike_model,$bpModel->color_id);
        $data['gst_rates'] = self::_getGstRates(!$auth->is_admin);
        $data['bike_types'] = bike_types();
        $data['bike_fuel_types'] = bike_fuel_types();
        $data['break_types'] = break_types();
        $data['wheel_types'] = wheel_types();
        $data['vin_physical_statuses'] = vin_physical_statuses();
        $data['action'] = route('purchases.update', ['purchase' => $id]);
        $data['data']   = $bpModel;
        $data['method'] = 'PUT';
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
        try {
            $bpModel = Purchase::where(['uuid' => $id]);
            if (!$bpModel) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => "Sorry! This id($id) not exist"
                ]);
            }

            $postData = $request->all();
            $validator = Validator::make($postData, [
                'bike_dealer'               => "required|exists:bike_dealers,id",
                'bike_branch'               => "required|exists:branches,id",
                'bike_brand'                => "required|exists:bike_brands,id",
                'bike_model'                => "required|exists:bike_models,id",
                'bike_model_color'          => "required|exists:bike_colors,id",
                'bike_type'                 => "required",
                'bike_fuel_type'            => "required",
                'break_type'                => "required",
                'wheel_type'                => "required",
                'dc_number'                 => "nullable",
                'dc_date'                   => "nullable",
                'vin_number'                => "required|min:17",
                'vin_physical_status'       => "nullable",
                'hsn_number'                => "nullable|min:6",
                'engine_number'             => "nullable|min:14",
                'sku'                       => "nullable",
                'sku_description'           => "nullable",
                'key_number'                => "nullable|",
                'service_book_number'       => "nullable",
                'battery_brand'             => "nullable",
                'battery_number'            => "nullable",
                'tyre_brand_name'           => "nullable",
                'tyre_front_number'         => "nullable",
                'tyre_rear_number'          => "nullable",
                'purchase_invoice_amount'   => "required|numeric",
                'purchase_invoice_number'   => "required",
                'purchase_invoice_date'     => "required|date",
                'status'                    => "nullable|in:1,2",
                //'active_status'             => "required|in:0,1",
                'gst_rate'                  => 'required|numeric|exists:gst_rates,id',
                'gst_rate_percent'          => 'required|numeric',
                'pre_gst_amount'            => 'required|numeric',
                'gst_amount'                => 'required|numeric',
                'ex_showroom_price'         => 'required|numeric',
                'discount_price'            => 'nullable|numeric',
                'grand_total'               => 'required|numeric',
                'bike_description'          => "nullable",
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

            //Updated
            $bpModel->update($postData);
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
        $action .= '<a href="' . route('purchases.edit', ['purchase' => $row->uuid]) . '" class="btn btn-sm btn-warning" data-modal_title="Update Purchase"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        // $action .= '<a href="' . route('purchases.destroy', ['purchase' => $row->id]) . '" data-id="' . $row->id . '" class="btn btn-sm btn-danger ajaxModalDelete" data-modal_title="Delete Purchase"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
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

    public function getPurchaseDetails($id)
    {
        $models = Purchase::find($id);
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Retrived Successfully.",
            'data'       => $models
        ]);
    }
}
