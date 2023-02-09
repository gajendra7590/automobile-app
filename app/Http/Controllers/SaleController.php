<?php

namespace App\Http\Controllers;

use App\Models\BikeModel;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Quotation;
use App\Models\SalePaymentAccounts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

//Helpr
use App\Traits\CommonHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    use CommonHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!request()->ajax()) {
            $data = array(
                'branches' => self::getAllBranchesWithInActive()
            );
            return view('admin.sales.index', $data);
        } else {

            $postData = $request->all();
            $data = Sale::select('*')
                ->branchWise()
                ->select('id', 'branch_id', 'purchase_id', 'customer_name', 'total_amount', 'created_at', 'status', 'sp_account_id')
                ->with([
                    'purchase',
                    'salesman' => function ($model) {
                        $model->select('id', 'name');
                    },
                ]);

            //Fitler By Branch
            if (isset($postData['columns'][1]['search']['value']) && (!empty($postData['columns'][1]['search']['value']))) {
                $data->where('branch_id', $postData['columns'][1]['search']['value']);
            }

            //Fitler By Due Status
            if (isset($postData['columns'][6]['search']['value']) && (!empty($postData['columns'][6]['search']['value']))) {
                $data->where('status', $postData['columns'][6]['search']['value']);
            }

            //Fitler By Broker
            if (isset($postData['columns'][7]['search']['value']) && ($postData['columns'][7]['search']['value'] != '')) {
                $data->whereHas('purchase', function ($query) use ($postData) {
                    $query->where('transfer_status', $postData['columns'][7]['search']['value']);
                });
            }

            // dd($data->limit(10)->get()->toArray());
            $search_string = isset($postData['search']['value']) ? $postData['search']['value'] : "";
            return DataTables::of($data)
                ->filter(function ($query) use ($search_string) {
                    if ($search_string != "") {
                        $query->where('id', $search_string)
                            ->orWhere('customer_name', 'LIKE', '%' . $search_string . '%')
                            ->orWhereHas('purchase.branch', function ($q) use ($search_string) {
                                $q->where('branch_name', 'LIKE', '%' . $search_string . '%');
                            })
                            ->orWhereHas('purchase.brand', function ($q) use ($search_string) {
                                $q->where('name', 'LIKE', '%' . $search_string . '%');
                            })
                            ->orWhereHas('purchase.model', function ($q) use ($search_string) {
                                $q->where('model_name', 'LIKE', '%' . $search_string . '%');
                            })
                            ->orWhereHas('purchase.color', function ($q) use ($search_string) {
                                $q->where('color_name', 'LIKE', '%' . $search_string . '%');
                            })
                            ->orwhereDate('created_at', $search_string)
                            ->orwhere('total_amount', 'LIKE', '%' . $search_string . '%');
                    }
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return $this->getActions($row);
                })
                ->addColumn('branch_name', function ($row) {
                    if (isset($row->purchase->branch)) {
                        return $row->purchase->branch->branch_name;
                    } else {
                        return 'N/A';
                    }
                })
                ->addColumn('broker_status', function ($row) {
                    if (isset($row->purchase->transfer_status) && ($row->purchase->transfer_status == '1')) {
                        return convertBadgesStr(1);
                    } else {
                        return convertBadgesStr(0);
                    }
                })
                ->addColumn('customer_name', function ($row) {
                    return ucwords($row->customer_name);
                })
                ->addColumn('bike_detail', function ($row) {
                    $str = '';
                    if (isset($row->purchase->brand)) {
                        $str .= $row->purchase->brand->name . ' | ';
                    }

                    if (isset($row->purchase->model)) {
                        $str .= $row->purchase->model->model_name . ' | ';
                    }

                    if (isset($row->purchase->modelColor)) {
                        $str .= $row->purchase->modelColor->color_name;
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
                    'action', 'branch_name', 'broker_status', 'customer_name',
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
            'method' => 'POST',
            'action' => route('sales.store'),
            'quotation_id' => null
        );
        if (!empty(request('q'))) {
            $quotation = Quotation::select('id', 'branch_id', 'salesman_id')->find(request('q'));
            if ($quotation) {
                $data['purchases'] = self::_getInStockPurchases($quotation->branch_id);
                if (intval($quotation->salesman_id) > 0) {
                    $data['salesmans'] = self::_getSalesmanById($quotation->salesman_id);
                } else {
                    $data['salesmans'] = self::_getSalesman();
                }
                $data['quotation_id'] = $quotation->id;
                $data['data']['salesman_id'] = $quotation->salesman_id;
            } else {
                $data['purchases'] = self::_getInStockPurchases();
                $data['salesmans'] = self::_getSalesman();
            }
        } else {
            $data['purchases'] = self::_getInStockPurchases();
            $data['salesmans'] = self::_getSalesman();
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
        if (!request()->ajax()) {
            return redirect()->route('sales.index');;
        } else {
            try {
                DB::beginTransaction();
                $postData = $request->only(
                    'purchase_id',
                    'quotation_id',
                    'salesman_id',
                    'customer_gender',
                    'customer_name',
                    'customer_relationship',
                    'customer_guardian_name',
                    'customer_address_line',
                    'customer_state',
                    'customer_district',
                    'customer_city',
                    'customer_zipcode',
                    'customer_mobile_number',
                    'customer_mobile_number_alt',
                    'customer_email_address',
                    'witness_person_name',
                    'witness_person_phone',
                    'payment_type',
                    'is_exchange_avaliable',
                    'hyp_financer',
                    'hyp_financer_description',
                    'ex_showroom_price',
                    'registration_amount',
                    'insurance_amount',
                    'hypothecation_amount',
                    'accessories_amount',
                    'other_charges',
                    'total_amount'
                );
                $formValidationArr = [
                    'purchase_id' => 'required|exists:purchases,id',
                    'quotation_id' => 'nullable|exists:quotations,id',
                    'salesman_id' => 'nullable|exists:salesmans,id',
                    'customer_gender' => 'required|in:1,2,3',
                    'customer_name' => 'required|string',
                    'customer_relationship' => 'required|in:1,2,3',
                    'customer_guardian_name' => 'required|string',
                    'customer_address_line' => 'required|string',
                    'customer_state' => 'required|exists:u_states,id',
                    'customer_district' => 'required|exists:u_districts,id',
                    'customer_city' => 'required|exists:u_cities,id',
                    'customer_zipcode' => 'required|numeric',
                    'customer_mobile_number' => 'required|numeric|digits:10',
                    'customer_mobile_number_alt' => 'nullable|numeric|digits:10',
                    'customer_email_address' => 'nullable|email',
                    'witness_person_name' => 'required',
                    'witness_person_phone' => 'required|numeric|digits:10',
                    'payment_type' => 'required|in:1,2,3',
                    'is_exchange_avaliable' => 'required|in:Yes,No',
                    'hyp_financer' => 'nullable|exists:bank_financers,id',
                    'hyp_financer_description' => 'nullable',
                    'ex_showroom_price' => 'required|numeric',
                    'registration_amount' => 'required|numeric',
                    'insurance_amount' => 'required|numeric',
                    'hypothecation_amount' => 'required|numeric',
                    'accessories_amount' => 'required|numeric',
                    'other_charges' => 'required|numeric',
                    'total_amount' => 'required|numeric'
                ];
                if (isset($postData['payment_type']) && (in_array($postData['payment_type'], ['2', '3']))) {
                    $formValidationArr['hyp_financer'] = 'required|exists:bank_financers,id';
                }
                $validator = Validator::make($postData, $formValidationArr);
                //If Validation failed
                if ($validator->fails()) {
                    DB::rollBack();
                    return response()->json([
                        'status'     => false,
                        'statusCode' => 419,
                        'message'    => $validator->errors()->first(),
                        'errors'     => $validator->errors()
                    ]);
                }

                //Add Some Keys
                $postData['branch_id'] = Purchase::where(['id' => $postData['purchase_id']])->value('bike_branch');
                $postData['sale_uuid'] = random_uuid('sale');
                $postData['created_by'] = Auth::user()->id;
                //Create Sale
                $createModel = Sale::create($postData);
                //Mark Status Closed If Purchase With Quotation
                if (isset($postData['quotation_id']) && intval($postData['quotation_id']) > 0) {
                    Quotation::where('id', $postData['quotation_id'])->update(['status' => 'close']);
                }
                //Change status of purchase - Mark as sold
                Purchase::where(['id' => $postData['purchase_id']])->update(['status' => '2']);

                //Create Sales Account
                $accountModel = SalePaymentAccounts::create(['sale_id' => $createModel->id, 'sales_total_amount' => $postData['total_amount']]);

                //Update
                $createModel->update(['sp_account_id' => $accountModel->id]);
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!request()->ajax()) {
            return redirect()->route('sales.index');;
        } else {
            $modals = Sale::where('id', $id)->with([
                'state' => function ($s) {
                    $s->select('id', 'state_name');
                },
                'district' => function ($s) {
                    $s->select('id', 'district_name');
                },
                'city' => function ($s) {
                    $s->select('id', 'city_name');
                },
                'financer' => function ($s) {
                    $s->select('id', 'bank_name');
                },
                'branch' => function ($s) {
                    $s->select('id', 'branch_name');
                },
                'salesman' => function ($s) {
                    $s->select('id', 'name');
                },
                'quotation' => function ($s) {
                    $s->select('id', 'uuid');
                },
                'purchase'
            ])->first();
            if (!$modals) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => trans('messages.id_not_exist', ['id' => $id])
                ]);
            }

            $data = array(
                'data' => $modals
            );
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.ajax_model_loaded'),
                'data'       => view('admin.sales.show', $data)->render()
            ]);
        }
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
        // return $bpModel;
        if (!$bpModel) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => trans('messages.id_not_exist', ['id' => $id])
            ]);
        }

        $purhcaseModel = Purchase::with([
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
            }
        ])->where('id', $bpModel['purchase_id'])->first();
        $data = array(
            'method' => 'PUT',
            'action' => route('sales.update', ['sale' => $bpModel->id]),
            'states' => self::_getStates(1),
            'districts' => [],
            'cities' => [],
            'gst_rto_rates' => self::_getRtoGstRates(),
            'salesmans' => self::_getSalesmanById($bpModel->salesman_id),
            'purchaseModel' => $purhcaseModel
        );
        //Quotation data for prefield
        if ($bpModel) {
            $data['data'] = $bpModel;
            if ($bpModel) {
                $data['districts'] = self::_getDistricts($bpModel->customer_state);
                $data['cities'] = self::_getCities($bpModel->customer_district);
                $data['bank_financers'] = self::_getFinaceirs($bpModel->payment_type - 1);
            }
        }

        $data['htmlData'] = (view('admin.sales.ajax.ajax-view')->with($data)->render());
        $data['purchases'] = self::_getPurchasesById($bpModel->purchase_id);
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
        if (!request()->ajax()) {
            return redirect()->route('sales.index');;
        } else {
            try {
                DB::beginTransaction();
                $bpModel = Sale::where(['id' => $id])->first();
                if (!$bpModel) {
                    DB::rollBack();
                    return response()->json([
                        'status'     => false,
                        'statusCode' => 419,
                        'message'    => trans('messages.id_not_exist', ['id' => $id])
                    ]);
                }

                $postData = $request->only(
                    'purchase_id',
                    'quotation_id',
                    'salesman_id',
                    'customer_gender',
                    'customer_name',
                    'customer_relationship',
                    'customer_guardian_name',
                    'customer_address_line',
                    'customer_state',
                    'customer_district',
                    'customer_city',
                    'customer_zipcode',
                    'customer_mobile_number',
                    'customer_mobile_number_alt',
                    'customer_email_address',
                    'witness_person_name',
                    'witness_person_phone',
                    'payment_type',
                    'is_exchange_avaliable',
                    'hyp_financer',
                    'hyp_financer_description',
                    'ex_showroom_price',
                    'registration_amount',
                    'insurance_amount',
                    'hypothecation_amount',
                    'accessories_amount',
                    'other_charges',
                    'total_amount'
                );
                $formValidationArr = [
                    'purchase_id' => 'nullable|exists:purchases,id',
                    'quotation_id' => 'nullable|exists:quotations,id',
                    'salesman_id' => 'nullable|exists:salesmans,id',
                    'customer_gender' => 'nullable|in:1,2,3',
                    'customer_name' => 'nullable|string',
                    'customer_relationship' => 'nullable|in:1,2,3',
                    'customer_guardian_name' => 'nullable|string',
                    'customer_address_line' => 'nullable|string',
                    'customer_state' => 'nullable|exists:u_states,id',
                    'customer_district' => 'nullable|exists:u_districts,id',
                    'customer_city' => 'nullable|exists:u_cities,id',
                    'customer_zipcode' => 'nullable|numeric',
                    'customer_mobile_number' => 'nullable|numeric|digits:10',
                    'customer_mobile_number_alt' => 'nullable|numeric|digits:10',
                    'customer_email_address' => 'nullable|email',
                    'witness_person_name' => 'nullable',
                    'witness_person_phone' => 'nullable|numeric|digits:10',
                    'payment_type' => 'nullable|in:1,2,3',
                    'is_exchange_avaliable' => 'nullable|in:Yes,No',
                    'hyp_financer' => 'nullable|exists:bank_financers,id',
                    'hyp_financer_description' => 'nullable',
                    'ex_showroom_price' => 'nullable|numeric',
                    'registration_amount' => 'nullable|numeric',
                    'insurance_amount' => 'nullable|numeric',
                    'hypothecation_amount' => 'nullable|numeric',
                    'accessories_amount' => 'nullable|numeric',
                    'other_charges' => 'nullable|numeric',
                    'total_amount' => 'nullable|numeric'
                ];
                if (isset($postData['payment_type']) && (in_array($postData['payment_type'], ['2', '3']))) {
                    $formValidationArr['hyp_financer'] = 'nullable|exists:bank_financers,id';
                }
                $validator = Validator::make($postData, $formValidationArr);

                //If Validation failed
                if ($validator->fails()) {
                    DB::rollBack();
                    return response()->json([
                        'status'     => false,
                        'statusCode' => 419,
                        'message'    => $validator->errors()->first(),
                        'errors'     => $validator->errors()
                    ]);
                }
                $postData['updated_by'] = Auth::user()->id;
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

    /**
     * Function for generate Full Delivery Challan
     * @param $id  Sales ID
     * @return Generate PDF Invoice
     */
    public function deliveryChallanFull(Request $request, $id)
    {
        $id = base64_decode($id);
        $branch_id = self::getCurrentUserBranch();
        $where = array();
        if ($branch_id > 0) {
            $where = array('branch_id' => $branch_id);
        }

        $saleModel = Sale::where('id', $id)
            ->where($where)
            ->with([
                'financer' => function ($financer) {
                    $financer->select('id', 'bank_name');
                },
                'branch',
                'purchase'
            ])
            ->first();
        if (!$saleModel) {
            return view('admin.accessDenied');
        }
        $broker_name = brokerNameByPurchase($saleModel->purchase_id);
        // return view('admin.sales.deliveryChallanFull', ['data' => $saleModel,'broker_name' => $broker_name]);
        $pdf = Pdf::loadView('admin.sales.deliveryChallanFull', ['data' => $saleModel, 'broker_name' => $broker_name]);
        return $pdf->stream('invoice.pdf');
    }


    /**
     * Function for generate On Road Delivery Challan
     * @param $id  Sales ID
     * @return Generate PDF Invoice
     */
    public function deliveryChallanOnRoad(Request $request, $id)
    {
        $id = base64_decode($id);
        $branch_id = self::getCurrentUserBranch();
        $where = array();
        if ($branch_id > 0) {
            $where = array('branch_id' => $branch_id);
        }

        $saleModel = Sale::where('id', $id)
            ->where($where)
            ->with([
                'financer' => function ($financer) {
                    $financer->select('id', 'bank_name');
                },
                'branch',
                'purchase',
            ])
            ->first();
        if (!$saleModel) {
            return view('admin.accessDenied');
        }

        $broker_name = brokerNameByPurchase($saleModel->purchase_id);
        // return view('admin.sales.deliveryChallanOnRoad', ['data' => $saleModel, 'broker_name' => $broker_name]);
        $pdf = Pdf::loadView('admin.sales.deliveryChallanOnRoad', ['data' => $saleModel, 'broker_name' => $broker_name]);
        return $pdf->stream('invoice.pdf');
    }


    public function getActions($row)
    {
        $action  = '<div class="dropdown pull-right customDropDownOption"><button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown" style="padding: 3px 10px !important;"><span class="caret"></span></button>';
        $action  .= '<ul class="dropdown-menu">';
        $action .= '<li><a href="' . route('sales.show', ['sale' => $row->id]) . '" class="ajaxModalPopup" data-title="VIEW DETAIL" data-modal_title="VIEW DETAIL" data-modal_size="modal-lg">VIEW DETAIL</a></li>';
        if ($row->status == 'open') {
            $action .= '<li><a href="' . route('sales.edit', ['sale' => $row->id]) . '" class="" data-modal_title="UPDATE DETAIL">UPDATE</a></li>';
        }

        $action .= '<li><a href="' . route('deliveryChallanFull', ['id' => base64_encode($row->id)]) . '" target="_blank" class="" data-modal_title="UPDATE DETAIL">DELIVERY CHALLAN FULL</a></li>';
        $action .= '<li><a href="' . route('deliveryChallanOnRoad', ['id' => base64_encode($row->id)]) . '" target="_blank" class="" data-modal_title="UPDATE DETAIL">DELIVERY CHALLAN ON ROAD</a></li>';

        $action  .= '</ul>';
        $action  .= '</div>';
        return $action;
    }

    public function getModelsList($id)
    {
        if (!request()->ajax()) {
            return redirect()->route('sales.index');;
        } else {
            $models = BikeModel::where('active_status', '1')->where(['brand_id' => $id])->get()->toArray();
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.retrieve_success'),
                'data'       => models_list($models)
            ]);
        }
    }

    public function ajaxLoadeView(Request $request)
    {
        if (!request()->ajax()) {
            return redirect()->route('sales.index');;
        } else {
            $postData = $request->all();
            $purhcaseModel = Purchase::with([
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
                'variants' => function ($model) {
                    $model->select('id', 'variant_name');
                },
                'color' => function ($model) {
                    $model->select('id', 'color_name', 'sku_code');
                },
                'tyreBrand' => function ($model) {
                    $model->select('id', 'name');
                },
                'batteryBrand' => function ($model) {
                    $model->select('id', 'name');
                },
                'skuSalesPrice'
            ])->where('id', $postData['id'])->first();
            // dd($purhcaseModel->toArray());
            $data = array(
                'states' => self::_getStates(1),
                'districts' => [],
                'cities' => [],
                'gst_rto_rates' => self::_getRtoGstRates(),
                'purchaseModel' => $purhcaseModel,
                'action' => "add"
            );
            //Quotation data for prefield
            if (!empty($postData['q'])) {
                $quot = Quotation::select('*')->find(request('q'));
                $data['data'] = $quot;
                if ($quot) {
                    $data['districts'] = self::_getDistricts($purhcaseModel->customer_state);
                    $data['cities'] = self::_getCities($purhcaseModel->customer_district);
                    $data['bank_financers'] = self::_getFinaceirs(($quot->payment_type - 1));
                }
            }

            //IF PURCHASE MAP WITH SKU SALES PRICE
            if (isset($purhcaseModel->skuSalesPrice) && ($purhcaseModel->skuSalesPrice)) {
                $data['data']['ex_showroom_price'] = $purhcaseModel->skuSalesPrice->ex_showroom_price;
                $data['data']['registration_amount'] = $purhcaseModel->skuSalesPrice->registration_amount;
                $data['data']['insurance_amount'] = $purhcaseModel->skuSalesPrice->insurance_amount;
                $data['data']['hypothecation_amount'] = $purhcaseModel->skuSalesPrice->hypothecation_amount;
                $data['data']['accessories_amount'] = $purhcaseModel->skuSalesPrice->accessories_amount;
                $data['data']['other_charges'] = $purhcaseModel->skuSalesPrice->other_charges;
                $data['data']['total_amount'] = $purhcaseModel->skuSalesPrice->total_amount;
                $data['data']['ex_showroom_price'] = $purhcaseModel->skuSalesPrice->ex_showroom_price;
            }

            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => 'Load Form',
                'data'       => (view('admin.sales.ajax.ajax-view')->with($data)->render())
            ]);
        }
    }
}
