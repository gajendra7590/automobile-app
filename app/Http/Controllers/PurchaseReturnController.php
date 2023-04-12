<?php

namespace App\Http\Controllers;

use App\Models\BikeModel;
use App\Models\Purchase;
use App\Models\PurchaseReturns;
use App\Models\User;
use App\Traits\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PurchaseReturnController extends Controller
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
            $data = array(
                'branches' => self::getAllBranchesWithInActive()
            );
            return view('admin.purchases.purchaseReturnToDealers.index', $data);
        } else {
            $postData = $request->all();
            //dd($postData['columns'][1]['search']['value']);
            $data = PurchaseReturns::select('*')
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

            //Fitler By Branch
            if (isset($postData['columns'][1]['search']['value']) && (!empty($postData['columns'][1]['search']['value']))) {
                $data->where('bike_branch', $postData['columns'][1]['search']['value']);
            }

            //Fitler By Transfer Status
            if (isset($postData['columns'][7]['search']['value']) && ($postData['columns'][7]['search']['value'] != "")) {
                $data->where('transfer_status', $postData['columns'][7]['search']['value']);
            }

            //Fitler By Stock Status
            if (isset($postData['columns'][8]['search']['value']) && (!empty($postData['columns'][8]['search']['value']))) {
                $data->where('status', $postData['columns'][8]['search']['value']);
            }

            $search_string = isset($postData['search']['value']) ? $postData['search']['value'] : "";
            return DataTables::of($data)
                ->filter(function ($query) use ($search_string) {
                    if ($search_string != "") {
                        $query->where(function ($qq) use ($search_string) {
                            $qq->where('id', $search_string)
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
                                ->orwhere('sku', 'LIKE', '%' . $search_string . '%')
                                ->orwhere('dc_number', 'LIKE', '%' . $search_string . '%')
                                ->orwhereDate('dc_date', $search_string)
                                ->orwhere('grand_total', 'LIKE', '%' . $search_string . '%');
                        });
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
                ->addColumn('created_at', function ($row) {
                    if ($row->created_at) {
                        return date('Y-m-d H:i:s',strtotime($row->created_at));
                    } else {
                        return '';
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
                'id'                     => "required|exists:purchases,id",
                'purchase_return_note'   => 'required'
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

            $getPurchase = Purchase::find($postData['id']);
            $purchaseReplicate = $getPurchase->replicate();
            $purchaseReplicate = $purchaseReplicate->toArray();
            $purchaseReplicate['purchase_return_note'] = $postData['purchase_return_note'];
            $purchaseReplicate['purchase_id'] = $postData['id'];
            //purchase_id

            $createModel = PurchaseReturns::create($purchaseReplicate);
            if($createModel) {
                $getPurchase->delete();
            }
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
        $data = array(
            'data'   => ['id' => $id],
            'action' => route('purchaseReturnToDealers.store'),
            'method' => 'POST'
        );
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => view('admin.purchases.purchaseReturnToDealers.return-confirm-dialogbox', $data)->render()
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
        $purchaseModel = PurchaseReturns::with([
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

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
        $action .= '<li><a href="' . route('purchaseReturnToDealers.edit', ['purchaseReturnToDealer' => $row->id]) . '" data-id="' . $row->id . '" class="ajaxModalPopup" data-modal_size="modal-lg" data-title="Purchase Detail" data-modal_title="View Purchase Detail">VIEW DETAIL</a></li>';
        $action  .= '</ul>';
        $action  .= '</div>';
        return $action;
    }
}
