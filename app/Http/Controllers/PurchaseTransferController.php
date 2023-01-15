<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseTransfer;
use App\Traits\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseTransferController extends Controller
{
    use CommonHelper;


    /**
     * @param $request
     * @param $id
     * @return json
     * @description - Method is to load transfer create modal popup
     */
    public function transferIndex(Request $request, $id)
    {
        $models = Purchase::find($id);
        if (!$models) {
            return response()->json([
                'status'     => false,
                'statusCode' => 409,
                'message'    => trans('messages.id_not_exist', ['id' => $id])
            ]);
        }


        $data['action']  = route('transferSave', ['id' => $id]);
        $data['method']  = "POST";
        $data['data'] = $models;
        $data['purchase_id'] = $id;
        $data['brokers'] = self::_getBrokers();

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.retrieve_success'),
            'data'       => (view('admin.purchases.ajax.purchaseTransfer', $data)->render())
        ]);
    }

    /**
     * @param $request
     * @param $id
     * @return json
     * @description - Method is to create/save new transfer data
     */
    public function transferSave(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $purchaseModel = Purchase::find($id);
            if (!$purchaseModel) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 409,
                    'message'    => trans('messages.id_not_exist', ['ID' => $id])
                ]);
            }

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
            PurchaseTransfer::create($postData); // Save Transfer
            $purchaseModel->update(['transfer_status' => '1']); //Log In Purchase
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
     * @param $request
     * @param $id
     * @return json
     * @description - Method is to load return create modal popup
     */
    public function returnIndex(Request $request, $id)
    {
        $models = Purchase::whereHas('transfers', function ($tr) {
            $tr->where('status', '0')->where('active_status', '1');
        })->where(['id' => $id, 'transfer_status' => '1'])->first();
        if (!$models) {
            return response()->json([
                'status'     => false,
                'statusCode' => 409,
                'message'    => trans('messages.id_not_exist', ['id' => $id])
            ]);
        }

        $data['action']  = route('returnSave', ['id' => $id]);
        $data['method']  = "POST";
        $data['data'] = $models;
        $data['purchase_id'] = $id;
        $data['brokers'] = self::_getBrokers();

        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.retrieve_success'),
            'data'       => (view('admin.purchases.ajax.purchaseReturn', $data)->render())
        ]);
    }


    /**
     * @param $request
     * @param $id
     * @return json
     * @description - Method is to create/save return data
     */
    public function returnSave(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $purchaseModel = Purchase::find($id);
            if (!$purchaseModel) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 409,
                    'message'    => trans('messages.id_not_exist', ['ID' => $id])
                ]);
            }


            $postData = $request->only('transfer_id', 'return_date', 'return_note');
            $validator = Validator::make($postData, [
                'transfer_id'  => "required|exists:purchase_transfers,id",
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

            $postData['updated_by'] = Auth::user()->id;
            PurchaseTransfer::where(['id' => $postData['transfer_id']])->update([
                'return_date' => $postData['return_date'],
                'return_note' => $postData['return_note'],
                'status' => '1',
                'active_status' => '0'
            ]); // Save Transfer
            $purchaseModel->update(['transfer_status' => '0']); //Log In Purchase
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
