<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseInvoice;
use App\Traits\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseInvoiceController extends Controller
{
    use CommonHelper;
    /**
     * @param $request
     * @param $id
     * @return json
     * @description - Method is to load transfer create modal popup
     */
    public function invoiceIndex(Request $request, $id)
    {
        $models = Purchase::with(['invoice'])->where('id', $id)->first();
        $gst_rates = self::_getGstRates();
        if (!$models) {
            return response()->json([
                'status'     => false,
                'statusCode' => 409,
                'message'    => trans('messages.id_not_exist', ['ID' => $id])
            ]);
        }

        $data = array(
            'data'       => $models,
            'gst_rates'  => $gst_rates,
            'method'     => 'POST',
            'action'     => route('invoiceSave', ['id' => $id])
        );

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.retrieve_success'),
            'data'       => (view('admin.purchases.ajax.purchaseInvoice', $data)->render())
        ]);
    }

    /**
     * @param $request
     * @param $id
     * @return json
     * @description - Method is to create/save invoice data
     */
    public function invoiceSave(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $models = Purchase::with(['invoice'])->where('id', $id)->first();
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
                'gst_rate',
                'gst_rate_percent',
                'pre_gst_amount',
                'gst_amount',
                'ex_showroom_price',
                'discount_price',
                'grand_total'
            );
            $validator = Validator::make($postData, [
                'purchase_id'    => "required|exists:purchases,id",
                'purchase_invoice_number'      => "required|exists:brokers,id",
                'purchase_invoice_date'  => "required|date:Y-m-d",
                'gst_rate'  => "required|exists:gst_rates,id",
                'gst_rate_percent'  => "required|numeric|min:1",
                'gst_amount'  => "required|numeric|min:1",
                'ex_showroom_price'  => "required|numeric|min:1",
                'discount_price'  => "nullable|numeric",
                'grand_total'  => "required|numeric|min:1"
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

            $invoiceModel = PurchaseInvoice::updateOrCreate(['purchase_id' => $postData['purchase_id']])->first();
            if ($invoiceModel) {
                $postData['created_by'] = Auth::user()->id;
            }

            //Update Invoice Data
            $invoiceModel->update($postData);
            //Update Invoice Status
            $models->update(['invoice_status' => '1']);
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
