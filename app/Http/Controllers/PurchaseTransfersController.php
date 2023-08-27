<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseTransfer;
use App\Traits\CommonHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PurchaseTransfersController extends Controller
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
            return view('admin.purchases.transfers.index');
        } else {
            $data =   Purchase::branchWise()
                ->whereHas('transfers', function ($transfer) {
                    $transfer->where('status', '0')->where('active_status', '1');
                })
                ->where('transfer_status', '1')
                ->select('*')
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
                    'transfers' => function ($tran) {
                        $tran->with([
                            'broker' => function ($broker) {
                                $broker->select('id', 'name');
                            }
                        ]);
                    }
                ]);

            $postData = $request->all();
            $search_string = isset($postData['search']['value']) ? strtolower($postData['search']['value']) : "";
            return DataTables::of($data)
                ->filter(function ($query) use ($search_string) {
                    if ($search_string != "") {
                        $query->where(function ($q) use ($search_string) {
                            $q->where('sku', 'LIKE', '%' . $search_string . '%')
                                ->orWhere('vin_number', 'LIKE', '%' . $search_string . '%')
                                ->orWhereDate('created_at', $search_string)
                                ->orWhereHas('branch', function ($q) use ($search_string) {
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
                                ->orWhereHas('transfers.broker', function ($q) use ($search_string) {
                                    $q->where('name', 'LIKE', '%' . $search_string . '%');
                                })
                                ->orWhereHas('transfers', function ($q) use ($search_string) {
                                    $q->where('total_price_on_road', 'LIKE', '%' . $search_string . '%');
                                });
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return $this->getActions($row);
                })
                ->addColumn('total_price_on_road', function ($row) {
                    if (isset($row->transfers)) {
                        return $row->transfers->total_price_on_road;
                    } else {
                        return 0.00;
                    }
                })
                ->addColumn('branch_name', function ($row) {
                    if (isset($row->branch)) {
                        return $row->branch->branch_name;
                    } else {
                        return '--';
                    }
                })
                ->addColumn('broker_name', function ($row) {
                    return isset($row->transfers->broker) ? $row->transfers->broker->name : '';
                })
                ->addColumn('bike_detail', function ($row) {
                    $str = '';
                    if (isset($row->brand)) {
                        $str .= $row->brand->name . ' | ';
                    }
                    if (isset($row->model)) {
                        $str .= $row->model->model_name . ' | ';
                    }
                    if (isset($row->modelColor)) {
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
                ->addColumn('created_at', function ($row) {
                    return isset($row->created_at) ? date('Y-m-d', strtotime($row->created_at)) : "";
                })
                ->rawColumns([
                    'action', 'total_price_on_road', 'branch_name', 'bike_detail', 'grand_total', 'status'
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
        if (!$request->ajax()) {
            return redirect()->route('purchaseTransfers.index');
        } else {
            $data['action']  = route('purchaseTransfers.store');
            $data['method']  = "POST";
            $data['brokers'] = self::_getBrokers();

            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.retrieve_success'),
                'data'       => (view('admin.purchases.transfers.create', $data)->render())
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect()->route('purchaseTransfers.index');
        } else {
            try {
                DB::beginTransaction();
                $postData = $request->only('purchase_id', 'broker_id', 'transfer_date', 'transfer_note', 'total_price_on_road');
                $validator = Validator::make($postData, [
                    'purchase_id'         => "required|exists:purchases,id",
                    'broker_id'           => "required|exists:brokers,id",
                    'total_price_on_road' => 'required|numeric|min:1',
                    'transfer_date'       => "required|date:Y-m-d",
                    'transfer_note'       => "required|string"
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

                $postData['created_by'] = Auth::user()->id;
                $created = PurchaseTransfer::create($postData); // Save Transfer
                if ($created) {
                    Purchase::where('id', $postData['purchase_id'])->update(['transfer_status' => '1']); //Log In Purchase
                    //In Active Old Transfer & Return For Same Pruchase If Any
                    PurchaseTransfer::where('purchase_id', $postData['purchase_id'])->whereNotIn('id', [$created->id])->update([
                        'active_status' => '0'
                    ]);
                }
                DB::commit();
                return response()->json([
                    'status'     => true,
                    'statusCode' => 200,
                    'message'    => trans('messages.create_success')
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status'     => false,
                    'statusCode' => 409,
                    'message'    => $e->getMessage()
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
        $id = base64_decode($id);
        $branch_id = self::getCurrentUserBranch();
        $where = array();
        if ($branch_id > 0) {
            $where = array('branch_id' => $branch_id);
        }

        $brokerReturnModel = PurchaseTransfer::where('id', $id)->with(['purchase', 'broker'])->first();
        // return $brokerReturnModel;
        if (!$brokerReturnModel) {
            return view('admin.accessDenied');
        }

        // return view('admin.purchases.transfers.deliveryChallan', ['data' => $brokerReturnModel]);
        $pdf = Pdf::loadView('admin.purchases.transfers.deliveryChallan', ['data' => $brokerReturnModel]);
        return $pdf->stream('invoice.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!$request->ajax()) {
            return redirect()->route('purchaseTransfers.index');
        } else {
            $model = PurchaseTransfer::find($id);
            if (!$model) {
                return response()->json([
                    'status'     => true,
                    'statusCode' => 200,
                    'message'    => trans('messages.id_not_exist', ['ID' => $id])
                ]);
            }
            $data['action']      = route('purchaseTransfers.update', ['purchaseTransfer' => $id]);
            $data['method']      = "PUT";
            $data['brokers']     = self::_getBrokers();
            $data['data']        = $model;

            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.retrieve_success'),
                'data'       => (view('admin.purchases.transfers.create', $data)->render())
            ]);
        }
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
        if (!$request->ajax()) {
            return redirect()->route('purchaseTransfers.index');
        } else {
            try {
                $model = PurchaseTransfer::find($id);
                if (!$model) {
                    return response()->json([
                        'status'     => true,
                        'statusCode' => 200,
                        'message'    => trans('messages.id_not_exist', ['ID' => $id])
                    ]);
                }


                DB::beginTransaction();
                $postData = $request->only('broker_id', 'total_price_on_road', 'transfer_note');
                $validator = Validator::make($postData, [
                    'broker_id'            => "required|exists:purchases,id",
                    'total_price_on_road'  => "required|numeric|min:1",
                    'transfer_note'        => "required|string"
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
                $model->update($postData);
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
                    'statusCode' => 409,
                    'message'    => $e->getMessage()
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

    public function returnIndex(Request $request, $id)
    {
        if (!$request->ajax()) {
            return redirect()->route('purchaseTransfers.index');
        } else {
            $model = PurchaseTransfer::where(['id' => $id, 'status' => '0', 'active_status' => '1'])->first();
            if (!$model) {
                return response()->json([
                    'status'     => true,
                    'statusCode' => 200,
                    'message'    => trans('messages.id_not_exist', ['ID' => $id])
                ]);
            }
            $data['action']      = route('purchaseTransferReturnSave', ['id' => $id]);
            $data['method']      = "PUT";
            $data['brokers']     = self::_getBrokers();
            $data['purchase_id'] = $model->purchase_id;

            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.retrieve_success'),
                'data'       => (view('admin.purchases.transfers.create_return', $data)->render())
            ]);
        }
    }

    public function returnSave(Request $request, $id)
    {
        if (!$request->ajax()) {
            return redirect()->route('purchaseTransfers.index');
        } else {
            try {
                $model = PurchaseTransfer::where(['id' => $id, 'status' => '0', 'active_status' => '1'])->first();
                if (!$model) {
                    return response()->json([
                        'status'     => true,
                        'statusCode' => 200,
                        'message'    => trans('messages.id_not_exist', ['ID' => $id])
                    ]);
                }


                DB::beginTransaction();
                $postData = $request->only('purchase_id', 'return_date', 'return_note');
                // dd($postData);
                $validator = Validator::make($postData, [
                    'purchase_id'    => "required|exists:purchases,id",
                    'return_date'  => "required|date:Y-m-d",
                    'return_note'  => "required|string"
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

                $postData['updated_by']     = Auth::user()->id;
                $postData['status']         = 1;
                $postData['active_status']  = 0;
                $updated = $model->update($postData); // Save Transfer
                if ($updated) {
                    Purchase::where('id', $postData['purchase_id'])->update(['transfer_status' => '0']);
                }
                DB::commit();
                return response()->json([
                    'status'     => true,
                    'statusCode' => 200,
                    'message'    => trans('messages.create_success')
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status'     => false,
                    'statusCode' => 409,
                    'message'    => $e->getMessage()
                ]);
            }
        }
    }

    public function getActions($row)
    {
        if ($row) {
            $action  = '<div class="dropdown pull-right customDropDownOption"><button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown" style="padding: 3px 10px !important;"><span class="caret"></span></button>';
            $action  .= '<ul class="dropdown-menu">';
            $action .= '<li><a href="' . route('purchases.show', ['purchase' => $row->id]) . '" data-id="' . $row->id . '" class="ajaxModalPopup" data-modal_size="modal-lg" data-title="VIEW PURCHASE DETAIL" data-modal_title="VIEW PURCHASE DETAIL">VIEW PURCHASE DETAIL</a></li>';
            if ((isset($row->status) && ($row->status == '1')) || (Auth::user()->is_admin == '1')) {
                $action .= '<li><a href="' . route('purchaseTransfers.edit', ['purchaseTransfer' => $row->transfers->id]) . '" data-id="' . $row->transfers->id . '" class="ajaxModalPopup" data-modal_size="modal-lg" data-modal_title="UPDATE BROKER TRANSFER">UPDATE TRANSFER</a></li>';
            }
            if ((isset($row->status) && ($row->status == '1')) || (Auth::user()->is_admin == '1')) {
                $action .= '<li><a href="' . route('purchaseTransferReturnIndex', ['id' => $row->transfers->id]) . '" data-id="' . $row->transfers->id . '" class="ajaxModalPopup" data-modal_size="modal-lg" data-modal_title="CREATE RETURN">CREATE RETURN</a></li>';
            }
            $action .= '<li><a href="' . route('purchaseTransferDeliveryChallan', ['id' => base64_encode($row->transfers->id)]) . '" data-id="' . $row->transfers->id . '" class="" title="DELIVERY CHALLAN" target="_blank">DELIVERY CHALLAN</a></li>';
            $action  .= '</ul>';
            $action  .= '</div>';
            return $action;
        }
    }

    /**
     * Function for load purchases list for select2 ajax dropdrown
     */
    public function getTransferPurchasesList(Request $request)
    {
        if (!$request->ajax()) {
            return redirect()->route('purchaseTransfers.index');
        } else {
            $postData = $request->all();
            $data = Purchase::branchWise()->select('id', DB::raw('CONCAT(vin_number," | ",engine_number," | ",hsn_number) AS text'))
                ->where(['transfer_status' => '0', 'status' => '1']);
            if (isset($postData['search']) && ($postData['search'] != "")) {
                $data = $data->where('vin_number', 'LIKE', '%' . $postData['search'] . '%')
                    ->orwhere('engine_number', 'LIKE', '%' . $postData['search'] . '%')
                    ->orwhere('hsn_number', 'LIKE', '%' . $postData['search'] . '%');
            }
            $data = $data->get();
            return response()->json([
                'status'  => true,
                'results' => $data,
                'message' => "List retrieved successfully"
            ]);
        }
    }
}
