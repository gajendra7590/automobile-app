<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseInvoice;
use App\Traits\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PurchaseInvoicesController extends Controller
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
            $pending_invoices = Purchase::where('invoice_status', '0')->count();
            return view('admin.purchases.invoices.index', ['pending_invoices' => $pending_invoices]);
        } else {
            $data = PurchaseInvoice::branchWise()->with([
                'purchase' => function ($purchase) {
                    $purchase->select('id', 'bike_branch', 'variant', 'sku')->with([
                        'branch' => function ($branch) {
                            $branch->select('id', 'branch_name');
                        }
                    ]);
                }
            ])
                ->select('id', 'purchase_id', 'purchase_invoice_number', 'purchase_invoice_date', 'grand_total');
            // $data = $data->limit(1)->get();
            // dd($data->toArray());
            $postData = $request->all();
            $search_string = isset($postData['search']['value']) ? $postData['search']['value'] : "";

            return DataTables::of($data)
                ->filter(function ($query) use ($search_string) {
                    if ($search_string != "") {
                        $query->where('purchase_invoice_number', 'LIKE', '%' . $search_string . '%')
                            ->orwhere('purchase_invoice_date', 'LIKE', '%' . $search_string . '%')
                            ->orwhere('grand_total', 'LIKE', '%' . $search_string . '%')
                            ->orWhereHas('purchase', function ($q) use ($search_string) {
                                $q->where('variant', 'LIKE', '%' . $search_string . '%')
                                    ->orwhere('sku', 'LIKE', '%' . $search_string . '%')
                                    ->orwhere('dc_number', 'LIKE', '%' . $search_string . '%')
                                    ->orwhere('vin_number', 'LIKE', '%' . $search_string . '%')
                                    ->orwhere('hsn_number', 'LIKE', '%' . $search_string . '%')
                                    ->orwhere('engine_number', 'LIKE', '%' . $search_string . '%');
                            })
                            ->orWhereHas('purchase.branch', function ($q) use ($search_string) {
                                $q->where('branch_name', 'LIKE', '%' . $search_string . '%');
                            });
                    }
                })
                ->addIndexColumn()
                ->addColumn('branch_name', function ($row) {
                    return isset($row->purchase->branch) ? $row->purchase->branch->branch_name : "--";
                })
                ->addColumn('purchase_sku', function ($row) {
                    return isset($row->purchase->sku) ? $row->purchase->sku : "--";
                })
                ->addColumn('purchase_variant', function ($row) {
                    return isset($row->purchase->variant) ? $row->purchase->variant : "--";
                })
                ->addColumn('action', function ($row) {
                    return $this->getActions($row);
                })
                ->rawColumns(['action'])
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
        $models = [];
        $gst_rates = self::_getGstRates();
        $purchases = Purchase::select('id', 'bike_branch', 'dc_number', 'vin_number', 'variant', 'sku')->where('invoice_status', '0')->get();
        $data = array(
            'data'       => $models,
            'gst_rates'  => $gst_rates,
            'purchases'  => $purchases,
            'method'     => 'POST',
            'action'     => route('purchaseInvoices.store')
        );

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.retrieve_success'),
            'data'       => (view('admin.purchases.invoices.create', $data)->render())
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
            $postData = $request->only(
                'purchase_id',
                'purchase_invoice_number',
                'purchase_invoice_date',
                'purchase_invoice_amount',
                'gst_rate_id',
                'gst_rate_percent',
                'pre_gst_amount',
                'gst_amount',
                'ex_showroom_price',
                'discount_price',
                'grand_total'
            );
            $validator = Validator::make($postData, [
                'purchase_id'              => "required|exists:purchases,id",
                'purchase_invoice_number'  => "required",
                'purchase_invoice_date'    => "required|date:Y-m-d",
                'gst_rate_id'              => "required|exists:gst_rates,id",
                'gst_rate_percent'         => "required|numeric|min:1",
                'gst_amount'               => "required|numeric|min:1",
                'ex_showroom_price'        => "required|numeric|min:1",
                'discount_price'           => "nullable|numeric",
                'grand_total'              => "required|numeric|min:1"
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

            //Update Invoice Data
            PurchaseInvoice::create($postData);
            Purchase::where(['id' => $postData['purchase_id']])->update(['invoice_status' => '1']);
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
        $models = PurchaseInvoice::where('id', $id)->first();
        if (!$models) {
            return response()->json([
                'status'     => false,
                'statusCode' => 409,
                'message'    => trans('messages.id_not_exist', ['ID' => $id])
            ]);
        }

        $gst_rates = [];
        if (!empty($models->gst_rate_id)) {
            $gst_rates = self::_getGstRatesById($models->gst_rate_id);
        } else {
            $gst_rates = self::_getGstRates();
        }

        $data = array(
            'data'       => $models,
            'gst_rates'  => $gst_rates,
            'method'     => 'PUT',
            'action'     => route('purchaseInvoices.update', ['purchaseInvoice' => $id])
        );

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.retrieve_success'),
            'data'       => (view('admin.purchases.invoices.create', $data)->render())
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
            DB::beginTransaction();
            $models = PurchaseInvoice::where('id', $id)->first();
            if (!$models) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 409,
                    'message'    => trans('messages.id_not_exist', ['ID' => $id])
                ]);
            }

            $postData = $request->only(
                'purchase_id',
                'purchase_invoice_number',
                'purchase_invoice_date',
                'purchase_invoice_amount',
                'gst_rate_id',
                'gst_rate_percent',
                'pre_gst_amount',
                'gst_amount',
                'ex_showroom_price',
                'discount_price',
                'grand_total'
            );
            $validator = Validator::make($postData, [
                'purchase_id'              => "required|exists:purchases,id",
                'purchase_invoice_number'  => "required",
                'purchase_invoice_date'    => "required|date:Y-m-d",
                'gst_rate_id'              => "required|exists:gst_rates,id",
                'gst_rate_percent'         => "required|numeric|min:1",
                'gst_amount'               => "required|numeric|min:1",
                'ex_showroom_price'        => "required|numeric|min:1",
                'discount_price'           => "nullable|numeric",
                'grand_total'              => "required|numeric|min:1"
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

            //Update Invoice Data
            $models->update($postData);
            Purchase::where(['id' => $postData['purchase_id']])->update(['invoice_status' => '1']);
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
        $action .= '<li><a href="' . route('purchaseInvoices.edit', ['purchaseInvoice' => $row->id]) . '" data-id="' . $row->id . '" class="ajaxModalPopup" data-modal_title="Edit Purchase Invoice Detail" data-modal_size="modal-lg">UPDATE</a></li>';
        $action  .= '</ul>';
        $action  .= '</div>';
        return $action;
    }
}
