<?php

namespace App\Http\Controllers;

use App\Models\BikeModel;
use App\Models\Purchase;
use App\Models\User;
use App\Traits\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public function index(Request $request)
    {
        $auth = User::find(auth()->id());
        if (!request()->ajax()) {
            return view('admin.purchases.index');
        } else {
            $postData = $request->all();
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
                    },
                    'transfers'
                ])->when(!$auth->is_admin, function ($q) use ($auth) {
                    $q->where('bike_branch', $auth->branch_id);
                });

            $search_string = isset($postData['search']['value']) ? $postData['search']['value'] : "";
            return DataTables::of($data)
                ->filter(function ($query) use ($search_string) {
                    if ($search_string != "") {
                        $transfer_status = (strtolower($search_string) == "yes") ? 1 : ((strtolower($search_string) == "no") ? 0 : 22);
                        $query->whereHas('branch', function ($q) use ($search_string) {
                            $q->where('branch_name', 'LIKE', '%' . $search_string . '%');
                        })
                            ->orWhereHas('brand', function ($q) use ($search_string) {
                                $q->where('name', 'LIKE', '%' . $search_string . '%');
                            })
                            ->orWhereHas('model', function ($q) use ($search_string) {
                                $q->where('model_name', 'LIKE', '%' . $search_string . '%');
                            })
                            ->orWhereHas('modelColor', function ($q) use ($search_string) {
                                $q->where('color_name', 'LIKE', '%' . $search_string . '%');
                            })
                            ->orwhere('sku', 'LIKE', '%' . $search_string . '%')
                            ->orwhere('dc_number', 'LIKE', '%' . $search_string . '%')
                            ->orwhereDate('dc_date', $search_string)
                            ->orwhere('grand_total', 'LIKE', '%' . $search_string . '%')
                            ->orwhere('transfer_status', $transfer_status);
                    }
                })
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
                ->addColumn('transfer_status', function ($row) {
                    if ($row->transfer_status == '1') {
                        return '<span class="label label-success">YES</span>';
                    } else {
                        return '<span class="label label-danger">NO</span>';
                    }
                })
                ->rawColumns([
                    'action', 'purchase_id', 'branch.branch_name', 'bike_detail', 'grand_total', 'transfer_status', 'status'
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
        $data['branches'] = self::_getbranches();
        $data['dealers'] = []; //self::_getDealers();
        $data['brands'] = []; //self::_getbrands();
        $data['models'] = []; //self::_getmodels();
        $data['gst_rates'] = self::_getGstRates();
        $data['tyre_brands'] = self::_getTyreBrands();
        $data['battery_brands'] = self::_getBatteryBrands();
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
            DB::beginTransaction();
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
                'dc_number'                 => "required",
                'dc_date'                   => "required|date",
                'vin_number'                => "required|min:17",
                'vin_physical_status'       => "required",
                'hsn_number'                => "required|min:6",
                'engine_number'             => "required|min:14",
                'variant'                   => "required",
                'sku'                       => "required",
                'sku_description'           => "nullable|string",
                'key_number'                => "nullable|string",
                'service_book_number'       => "nullable|string",
                'battery_brand_id'          => "nullable|exists:battery_brands,id",
                'battery_number'            => "nullable|string",
                'tyre_brand_id'             => "nullable|exists:tyre_brands,id",
                'tyre_front_number'         => "nullable|string",
                'tyre_rear_number'          => "nullable|string",
                'gst_rate'                  => 'required|numeric',
                'pre_gst_amount'            => 'required|numeric',
                'gst_amount'                => 'required|numeric',
                'ex_showroom_price'         => 'required|numeric',
                'discount_price'            => 'nullable|numeric',
                'grand_total'               => 'required|numeric',
                'bike_description'          => "nullable|string",
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
            DB::commit();
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.create_success'),
                'data'       => $createModel
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
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
        $purchaseModel = Purchase::with([
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
            'color' => function ($model) {
                $model->select('id', 'color_name');
            },
            'tyreBrand' => function ($model) {
                $model->select('id', 'name');
            },
            'batteryBrand' => function ($model) {
                $model->select('id', 'name');
            },
            'invoice' => function ($model) {
                $model->select('id', 'purchase_id', 'grand_total');
            },
            'transfers' => function ($transfers) {
                $transfers->with(['broker'])->select('id', 'broker_id', 'purchase_id');
            }
        ])->where('id', $id)->first();

        $data = array('data' => $purchaseModel);

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.purchases.show', $data)->render()
        ]);
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
        $bpModel = Purchase::where(['id' => $id])
            ->with([
                'invoice',
                'transfers' => function ($transfers) {
                    $transfers->with(['broker'])->select('id', 'broker_id', 'purchase_id');
                }
            ])->first();
        if (!$bpModel) {
            return redirect()->back();
        }

        // return $bpModel;
        $data = [];
        $data['branches'] = self::_getBranchById($bpModel->bike_branch);
        $data['dealers'] = self::_getDealerById($bpModel->bike_dealer);
        $data['brands'] = self::_getbrands();
        $data['models'] = self::_getmodels($bpModel->bike_brand, !$auth->is_admin);
        $data['colors'] = self::_getColors($bpModel->bike_model, $bpModel->color_id);
        $data['gst_rates'] = self::_getGstRates(!$auth->is_admin);
        $data['tyre_brands'] = self::_getTyreBrands(!$auth->is_admin);
        $data['battery_brands'] = self::_getBatteryBrands(!$auth->is_admin);
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
            DB::beginTransaction();
            $bpModel = Purchase::where(['id' => $id])->first();
            if (!$bpModel) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => trans('messages.id_not_exist', ['id' => $id])
                ]);
            }

            $postData = $request->all();
            $validator = Validator::make($postData, [
                'bike_dealer'               => "nullable|exists:bike_dealers,id",
                'bike_branch'               => "nullable|exists:branches,id",
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
                'variant'                   => "nullable",
                'sku'                       => "nullable",
                'sku_description'           => "nullable",
                'key_number'                => "nullable|",
                'service_book_number'       => "nullable",
                'battery_brand_id'          => "nullable|exists:battery_brands,id",
                'battery_number'            => "nullable",
                'tyre_brand_id'             => "nullable|exists:tyre_brands,id",
                'tyre_front_number'         => "nullable",
                'tyre_rear_number'          => "nullable",
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
            DB::commit();
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.update_success')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
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
        $action  = '<div class="dropdown pull-right customDropDownOption"><button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown" style="padding: 3px 10px !important;"><span class="caret"></span></button>';
        $action  .= '<ul class="dropdown-menu">';
        $action .= '<li><a href="' . route('purchases.show', ['purchase' => $row->id]) . '" data-id="' . $row->id . '" class="ajaxModalPopup" data-modal_size="modal-lg" data-title="Purchase Detail" data-modal_title="View Purchase Detail">VIEW DETAIL</a></li>';
        if ($row->status == '1') {
            $action .= '<li><a href="' . route('purchases.edit', ['purchase' => $row->id]) . '" class="" data-title="Update Purchase Detail" data-modal_title="Update Purchase">UPDATE</a></li>';
        }
        $action  .= '</ul>';
        $action  .= '</div>';
        return $action;
    }

    public function getModelsList($id)
    {
        $models = BikeModel::where('active_status', '1')->where(['brand_id' => $id])->get()->toArray();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.retrieve_success'),
            'data'       => models_list($models)
        ]);
    }

    public function getPurchaseDetails($id)
    {
        $models = Purchase::find($id);
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.retrieve_success'),
            'data'       => $models
        ]);
    }
}
