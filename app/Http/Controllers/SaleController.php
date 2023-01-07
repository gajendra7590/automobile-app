<?php

namespace App\Http\Controllers;

use App\Models\BikeModel;
use App\Models\Sale;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

//Helpr
use App\Traits\CommonHelper;

class SaleController extends Controller
{
    use CommonHelper;
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
                ->branchWise()
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
                ->addColumn('customer', function ($row) {
                    // $str = '';
                    // if ($row->customer_gender) {
                    //     $str .= custPrefix($row->customer_gender) . ' ';
                    // }

                    // if ($row->customer_name) {
                    //     $str .= ucwords($row->customer_name) . ' ';
                    // }

                    // if ($row->customer_relationship) {
                    //     $str .= custRel($row->customer_relationship) . ' ';
                    // }

                    // if ($row->customer_guardian_name) {
                    //     $str .= ucwords($row->customer_guardian_name);
                    // }
                    // return $str;
                    return ucwords($row->customer_name);
                })
                ->addColumn('bike_detail', function ($row) {
                    $str = '';
                    if (isset($row->brand->name)) {
                        $str .= $row->brand->name . ' | ';
                    }

                    if (isset($row->model->model_name)) {
                        $str .= $row->model->model_name . ' | ';
                    }

                    if (isset($row->modelColor->color_name)) {
                        $str .= $row->modelColor->color_name;
                    }
                    return $str;
                })
                ->addColumn('total_amount', function ($row) {
                    return '₹' . $row->total_amount;
                })
                ->addColumn('created_at', function ($row) {
                    return date('Y-m-d', strtotime($row->created_at));
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'open') {
                        return '<span class="label label-warning">Open</span>';
                    } else {
                        return '<span class="label label-success">Close</span>';
                    }
                })
                ->rawColumns([
                    'action', 'branch.branch_name', 'dealer.company_name', 'customer',
                    'bike_detail', 'total_amount', 'created_at', 'status'
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
        $auth = User::find(auth()->id());
        $data = array(
            'branches'              => self::_getBranches(),
            'dealers'               => self::_getDealers(),
            'states'                => self::_getStates(),
            'brands'                => self::_getBrands(),
            'bank_financers'        => self::_getFinaceirs(),
            'tyre_brands'           => self::_getTyreBrands(!$auth->is_admin),
            'battery_brands'        => self::_getBatteryBrands(!$auth->is_admin),
            'bike_types'            => bike_types(),
            'bike_fuel_types'       => bike_fuel_types(),
            'break_types'           => break_types(),
            'wheel_types'           => wheel_types(),
            'vin_physical_statuses' => vin_physical_statuses(),
            //Other Important Data
            'method' => 'POST',
            'action' => route('sales.store')
        );

        // return $data['purchases'];

        if (!empty(request('q'))) {
            $quotation = Quotation::select('*')->find(request('q'));
            if ($quotation) {
                $data['purchases'] = self::_getInStockPurchases($quotation['branch_id']);
                $data['data'] = $quotation;
                $data['data']['bike_branch'] = $quotation->branch_id;
                $data['data']['quotation_id'] = $quotation->id;
            } else {
                $data['purchases'] = self::_getInStockPurchases();
            }
            $data['models']    = self::_getModels($quotation->bike_brand);
            $data['colors']    = self::_getColors($quotation->bike_model);
            $data['districts'] = self::_getDistricts($quotation->customer_state);
            $data['cities']    = self::_getCities($quotation->customer_district);
        } else {
            $data['purchases'] = self::_getInStockPurchases();
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
        try {
            $postData = $request->all();
            $validator = Validator::make($postData, [
                'purchase_id' => 'required|exists:purchases,id',
                'bike_branch' => 'required|exists:branches,id',
                'bike_dealer' => 'required|exists:bike_dealers,id',
                'bike_brand' => 'required|exists:bike_brands,id',
                'bike_model' => 'required|exists:bike_models,id',
                'bike_color' => 'required|exists:bike_colors,id',
                'bike_type' => 'required',
                'bike_fuel_type' => 'required',
                'break_type' => 'required',
                'wheel_type' => 'required',
                'vin_number'  => "required|min:17",
                'vin_physical_status' => 'required',
                'sku' => 'nullable',
                'sku_description' => 'nullable',
                'hsn_number' => "required|min:6",
                'engine_number'  => "required|min:14",
                'key_number' => 'required',
                'service_book_number' => 'required',
                'tyre_brand_id' => 'required|exists:tyre_brands,id',
                'tyre_front_number' => 'required',
                'tyre_rear_number' => 'required',
                'battery_brand_id' => 'required|exists:battery_brands,id',
                'battery_number' => 'required',
                'bike_description' => 'nullable',
                'customer_gender' => 'required|in:1,2,3',
                'customer_name' => 'required|string',
                'customer_relationship' => 'required|in:1,2,3',
                'customer_guardian_name' => 'required|string',
                'customer_address_line' => 'required|string',
                'customer_state' => 'required|exists:u_states,id',
                'customer_district' => 'required|exists:u_districts,id',
                'customer_city' => 'required|exists:u_cities,id',
                'customer_zipcode' => 'required|numeric',
                'customer_mobile_number' => 'required|numeric',
                'customer_email_address' => 'nullable|email',
                'payment_type' => 'required',
                'is_exchange_avaliable' => 'required|in:Yes,No',
                'hyp_financer' => 'nullable|exists:bank_financers,id',
                'hyp_financer_description' => 'nullable',
                'ex_showroom_price' => 'required|numeric',
                'registration_amount' => 'required|numeric',
                'insurance_amount' => 'required|numeric',
                'hypothecation_amount' => 'required|numeric',
                'accessories_amount' => 'required|numeric',
                'other_charges' => 'required|numeric',
                'total_amount' => 'required|numeric',
                'active_status' => 'nullable|in:0,1'
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

            $postData['sale_uuid'] = random_uuid('sale');
            $postData['created_by'] = Auth::user()->id;
            //Create Sale
            $createModel = Sale::create($postData);

            //Mark Status Closed If Purchase With Quotation
            if (isset($postData['quotation_id']) && intval($postData['quotation_id']) > 0) {
                Quotation::where('id', $postData['quotation_id'])->update(['status' => 'close']);
            }

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
        $bpModel = Sale::where(['id' => $id])->first();
        if (!$bpModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => trans('messages.id_not_exist', ['id' => $id])
            ]);
        }
        $data = array(
            'branches'              => self::_getBranches(),
            'dealers'               => self::_getDealers(),
            'states'                => self::_getStates(),
            'districts'             => self::_getDistricts($bpModel->customer_state),
            'cities'                => self::_getCities($bpModel->customer_district),
            'brands'                => self::_getBrands(),
            'models'                => self::_getModels($bpModel->bike_brand),
            'colors'                => self::_getColors($bpModel->bike_model),
            'bank_financers'        => self::_getFinaceirs(),
            'purchases'             => self::_getInStockPurchases(),
            'tyre_brands'           => self::_getTyreBrands(!$auth->is_admin),
            'battery_brands'        => self::_getBatteryBrands(!$auth->is_admin),
            'bike_types'            => bike_types(),
            'bike_fuel_types'       => bike_fuel_types(),
            'break_types'           => break_types(),
            'wheel_types'           => wheel_types(),
            'vin_physical_statuses' => vin_physical_statuses(),
            //Other Important Data
            'action' => route('sales.update', ['sale' => $id]),
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
        try {
            $bpModel = Sale::where(['id' => $id]);
            if (!$bpModel) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => trans('messages.id_not_exist', ['id' => $id])
                ]);
            }

            $postData = $request->all();
            $validator = Validator::make($postData, [
                'purchase_id' => 'required|exists:purchases,id',
                'quotation_id' => 'nullable|exists:quotations,id',
                'bike_branch' => 'required|exists:branches,id',
                'bike_dealer' => 'required|exists:bike_dealers,id',
                'bike_brand' => 'required|exists:bike_brands,id',
                'bike_model' => 'required|exists:bike_models,id',
                'bike_color' => 'required|exists:bike_colors,id',
                'bike_type' => 'required',
                'bike_fuel_type' => 'required',
                'break_type' => 'required',
                'wheel_type' => 'required',
                'vin_number'  => "required|min:17",
                'vin_physical_status' => 'required',
                'sku' => 'nullable',
                'sku_description' => 'nullable',
                'hsn_number' => "required|min:6",
                'engine_number'  => "required|min:14",
                'key_number' => 'required',
                'service_book_number' => 'required',
                'tyre_brand_id' => 'required|exists:tyre_brands,id',
                'tyre_front_number' => 'required',
                'tyre_rear_number' => 'required',
                'battery_brand_id' => 'required|exists:battery_brands,id',
                'battery_number' => 'required',
                'bike_description' => 'nullable',
                'customer_gender' => 'required|in:1,2,3',
                'customer_name' => 'required|string',
                'customer_relationship' => 'required|in:1,2,3',
                'customer_guardian_name' => 'required|string',
                'customer_address_line' => 'required|string',
                'customer_state' => 'required|exists:u_states,id',
                'customer_district' => 'required|exists:u_districts,id',
                'customer_city' => 'required|exists:u_cities,id',
                'customer_zipcode' => 'required|numeric',
                'customer_mobile_number' => 'required|numeric',
                'customer_email_address' => 'nullable|email',
                'payment_type' => 'required',
                'is_exchange_avaliable' => 'required|in:Yes,No',
                'hyp_financer' => 'nullable|exists:bank_financers,id',
                'hyp_financer_description' => 'nullable',
                'ex_showroom_price' => 'required|numeric',
                'registration_amount' => 'required|numeric',
                'insurance_amount' => 'required|numeric',
                'hypothecation_amount' => 'required|numeric',
                'accessories_amount' => 'required|numeric',
                'other_charges' => 'required|numeric',
                'total_amount' => 'required|numeric',
                'active_status' => 'nullable|in:0,1'
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
        $action .= '<a href="' . route('sales.edit', ['sale' => $row->id]) . '" class="btn btn-sm btn-warning" data-modal_title="Update Sale"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
        // $action .= '<a href="' . route('sales.destroy', ['sale' => $row->id]) . '" data-id="' . $row->id . '" class="btn btn-sm btn-danger ajaxModalDelete" data-modal_title="Delete Sale"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';

        if (intval($row->sp_account_id) > 0) {
            $action .= '<a href="' . route('sales-accounts.edit', ['sales_account' => $row->sp_account_id]) . '" class="btn btn-sm btn-success" data-title="View Account"><i class="fa fa-eye" aria-hidden="true"></i></a>';
        } else {
            $action .= '<a href="' . route('sales-accounts.create') . '?sales_id=' . $row->id . '" class="btn btn-sm btn-success ajaxModalPopup" data-title="Open Account" data-modal_title="Create New Sales Account" data-modal_size="modal-lg"><i class="fa fa-plus" aria-hidden="true"></i></a>';
        }
        $action .= '</div>';
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
}
