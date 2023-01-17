<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseTransfer;
use App\Traits\CommonHelper;
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
    public function index()
    {
        if (!request()->ajax()) {
            return view('admin.purchases.transfers.index');
        } else {
            $data = Purchase::branchWise()
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
                ->addColumn('broker_name', function ($row) {
                    return isset($row->transfers->broker) ? $row->transfers->broker->name : '';
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
                ->rawColumns([
                    'action', 'purchase_id', 'branch.branch_name', 'bike_detail', 'grand_total', 'status'
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
            $postData = $request->only('purchase_id', 'broker_id', 'transfer_date', 'transfer_note');
            $validator = Validator::make($postData, [
                'purchase_id'    => "required|exists:purchases,id",
                'broker_id'      => "required|exists:brokers,id",
                'transfer_date'  => "required|date:Y-m-d",
                'transfer_note'  => "required|string"
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
        $model = PurchaseTransfer::where(['id' => $id, 'status' => '0', 'active_status' => '1'])->first();
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
        $data['purchase_id'] = $model->purchase_id;

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.retrieve_success'),
            'data'       => (view('admin.purchases.transfers.create_return', $data)->render())
        ]);
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
        if ($row->status == '1') {
            $action .= '<li><a href="' . route('purchaseTransfers.edit', ['purchaseTransfer' => $row->transfers->id]) . '" data-id="' . $row->transfers->id . '" class="ajaxModalPopup" data-modal_size="modal-lg" data-modal_title="GENERATE NEW RETURN">CREATE RETURN</a></li>';
        }
        $action  .= '</ul>';
        $action  .= '</div>';
        return $action;
    }

    public function getTransferPurchasesList(Request $request)
    {
        $postData = $request->all();
        $data = Purchase::select('id', DB::raw('CONCAT(sku,"-", dc_number, "-", vin_number,"-",hsn_number) AS text'))
            ->where(['transfer_status' => '0', 'status' => '1']);
        if (isset($postData['search']) && ($postData['search'] != "")) {
            $data = $data->where('sku', 'LIKE', '%' . $postData['search'] . '%')
                ->orwhere('dc_number', 'LIKE', '%' . $postData['search'] . '%')
                ->orwhere('vin_number', 'LIKE', '%' . $postData['search'] . '%')
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
