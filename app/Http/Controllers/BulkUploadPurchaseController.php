<?php

namespace App\Http\Controllers;

use App\Models\BatteryBrand;
use App\Models\BikeBrand;
use App\Models\BikeColor;
use App\Models\BikeDealer;
use App\Models\BikeModel;
use App\Models\BikeModelVariant;
use App\Models\Purchase;
use App\Models\TyreBrand;
use App\Traits\CommonHelper;
use App\Traits\CsvHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BulkUploadPurchaseController extends Controller
{
    use CommonHelper, CsvHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['branches'] = self::_getBranches();
        return view('admin.bulkUploadPurchases.index', $data);
    }

    /**
     * UPLOAD CSV FILE
     */
    public function store(Request $request)
    {
        try {
            ini_set('max_execution_time', '0');
            DB::beginTransaction();;
            $postData = $request->all();
            $validator = Validator::make($postData, [
                'branch_id' => 'required|exists:branches,id',
                'csv_file'  => 'required|file'
            ], [
                'csv_file.required' => "Please upload valid CSV file"
            ]);

            //If Validation failed
            if ($validator->fails()) {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => $validator->errors()->first(),
                    'errors'     => $validator->errors(),
                ]);
            }

            //IF FILE UPLOAD
            if ($request->hasFile('csv_file')) {
                $file = $request->file('csv_file');
                $original_ext = strtolower(trim($file->getClientOriginalExtension()));
                if ($original_ext != 'csv') {
                    DB::rollBack();
                    return response()->json([
                        'status'     => false,
                        'statusCode' => 419,
                        'message'    => "Please upload valid CSV File"
                    ], 200);
                }
                $file_name = md5(date('YmdHiss')) . '.' . $original_ext;
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }

                $uploaded = $file->move('uploads', $file_name);
                if ($uploaded) {
                    chmod("uploads/" . $file_name, 0777);
                }

                $csvReaderObj =  $this->parseCsvData('uploads/' . $file_name);
                if (is_array($csvReaderObj) && count($csvReaderObj) > 0) {
                    //VERIFY DATA
                    $verifyStatus = self::verifyCSVParseData($csvReaderObj, $postData['branch_id']);
                    if (isset($verifyStatus['status']) && ($verifyStatus['status'] == false)) {
                        DB::rollBack();
                        return response()->json([
                            'status'     => false,
                            'statusCode' => 419,
                            'message'    => $verifyStatus['message']
                        ], 200);
                    } elseif (isset($verifyStatus['status']) && ($verifyStatus['status'] == true)) {
                        foreach ($csvReaderObj as $k => $purchase) {
                            Purchase::create([
                                'bike_branch'          => isset($postData['branch_id']) ? $postData['branch_id'] : null,
                                'bike_dealer'          => isset($purchase['bike_dealer']) ? $purchase['bike_dealer'] : null,
                                'bike_brand'           => isset($purchase['bike_brand']) ? $purchase['bike_brand'] : $purchase['bike_brand'],
                                'bike_model'           => isset($purchase['bike_model']) ? $purchase['bike_model'] : null,
                                'bike_model_variant'   => isset($purchase['bike_model_variant']) ? $purchase['bike_model_variant'] : null,
                                'bike_model_color'     => isset($purchase['bike_model_color']) ? $purchase['bike_model_color'] : null,
                                'bike_type'            => isset($purchase['bike_type']) ? $purchase['bike_type'] : null,
                                'bike_fuel_type'       => isset($purchase['bike_fuel_type']) ? $purchase['bike_fuel_type'] : null,
                                'dc_number'            => isset($purchase['dc_number']) ? $purchase['dc_number'] : null,
                                'dc_date'              => isset($purchase['dc_date']) ? date('Y-m-d', strtotime($purchase['dc_date'])) : null,
                                'vin_number'           => isset($purchase['vin_number']) ? $purchase['vin_number'] : null,
                                'vin_physical_status'  => isset($purchase['vin_physical_status']) ? $purchase['vin_physical_status'] : null,
                                'variant'              => BikeModelVariant::where('id', $purchase['bike_model_variant'])->value('variant_name'),
                                'sku'                  => BikeColor::where('id', $purchase['bike_model_color'])->value('sku_code'),
                                'sku_description'      => isset($purchase['sku_description']) ? $purchase['sku_description'] : null,
                                'hsn_number'           => isset($purchase['hsn_number']) ? $purchase['hsn_number'] : null,
                                'engine_number'        => isset($purchase['engine_number']) ? $purchase['engine_number'] : null,
                                'key_number'           => isset($purchase['key_number']) ? $purchase['key_number'] : null,
                                'service_book_number'  => isset($purchase['service_book_number']) ? $purchase['service_book_number'] : null,
                                'tyre_brand_id'        => isset($purchase['tyre_brand_id']) ? $purchase['tyre_brand_id'] : null,
                                'tyre_front_number'    => isset($purchase['tyre_front_number']) ? $purchase['tyre_front_number'] : null,
                                'tyre_rear_number'     => isset($purchase['tyre_rear_number']) ? $purchase['tyre_rear_number'] : null,
                                'battery_brand_id'     => isset($purchase['battery_brand_id']) ? $purchase['battery_brand_id'] : null,
                                'battery_number'       => isset($purchase['battery_number']) ? $purchase['battery_number'] : null,
                                'gst_rate'             => isset($purchase['gst_rate']) ? $purchase['gst_rate'] : null,
                                'gst_rate_percent'     => isset($purchase['gst_rate_percent']) ? $purchase['gst_rate_percent'] : null,
                                'pre_gst_amount'       => isset($purchase['pre_gst_amount']) ? $purchase['pre_gst_amount'] : null,
                                'gst_amount'           => isset($purchase['gst_amount']) ? $purchase['gst_amount'] : null,
                                'ex_showroom_price'    => isset($purchase['ex_showroom_price']) ? $purchase['ex_showroom_price'] : null,
                                'discount_price'       => isset($purchase['discount_price']) ? $purchase['discount_price'] : null,
                                'grand_total'          => isset($purchase['grand_total']) ? $purchase['grand_total'] : null,
                                'bike_description'     => isset($purchase['bike_description']) ? $purchase['bike_description'] : null,
                                'created_by'           => Auth::id(),
                            ]);
                        }
                    }
                }
                DB::commit();
                return response()->json([
                    'status'     => true,
                    'statusCode' => 200,
                    'message'    => "ALL PURCHASES UPLOADED SUCCESSFULLY."
                ], 200);
            } else {
                DB::rollBack();
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => "Please upload valid csv file"
                ]);
            }
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
     * verify CSV Parsed Data
     */
    public function verifyCSVParseData($data, $branch_id)
    {
        if (count($data) > 0) {
            foreach ($data as $k => $pd) {

                //VERIFY BIKE DEALER
                if (!isset($pd['bike_dealer'])) {
                    return ['status' => false, 'message' => 'Please fill correct bike_dealer ID in record number - ' . ($k + 1)];
                }
                $bike_dealer = BikeDealer::select('id')->where(['branch_id' => $branch_id, 'id' => $pd['bike_dealer']])->first();
                if (!$bike_dealer) {
                    return ['status' => false, 'message' => 'Please fill correct bike_dealer ID in record number - ' . ($k + 1)];
                }

                //VERIFY BIKE BRAND
                if (!isset($pd['bike_brand'])) {
                    return ['status' => false, 'message' => 'Please fill correct bike_brand ID in record number - ' . ($k + 1)];
                }
                $bike_brand = BikeBrand::select('id')->where(['branch_id' => $branch_id, 'id' => $pd['bike_brand']])->first();
                if (!$bike_brand) {
                    return ['status' => false, 'message' => 'Please fill correct bike_brand ID in record number - ' . ($k + 1)];
                }

                //VERIFY BIKE BRAND MODEL
                if (!isset($pd['bike_model'])) {
                    return ['status' => false, 'message' => 'Please fill correct bike_model ID in record number - ' . ($k + 1)];
                }
                $bike_model = BikeModel::select('id')->where(['brand_id' => $pd['bike_brand'], 'id' => $pd['bike_model']])->first();
                if (!$bike_model) {
                    return ['status' => false, 'message' => 'Please fill correct bike_model ID in record number - ' . ($k + 1)];
                }

                //VERIFY BIKE BRAND MODEL VARIANT
                if (!isset($pd['bike_model_variant'])) {
                    return ['status' => false, 'message' => 'Please fill correct bike_model_variant ID in record number - ' . ($k + 1)];
                }
                $bike_model_variant = BikeModelVariant::select('id')->where(['model_id' => $pd['bike_model'], 'id' => $pd['bike_model_variant']])->first();
                if (!$bike_model_variant) {
                    return ['status' => false, 'message' => 'Please fill correct bike_model_variant ID in record number - ' . ($k + 1)];
                }

                //VERIFY BIKE BRAND MODEL VARIANT COLOR
                if (!isset($pd['bike_model_color'])) {
                    return ['status' => false, 'message' => 'Please fill correct bike_model_color ID in record number - ' . ($k + 1)];
                }
                $bike_model_color = BikeColor::select('id')->where(['model_variant_id' => $pd['bike_model_variant'], 'id' => $pd['bike_model_color']])->first();
                if (!$bike_model_color) {
                    return ['status' => false, 'message' => 'Please fill correct bike_model_color ID in record number - ' . ($k + 1)];
                }

                //VERIFY TYRE BRAND
                if (isset($pd['tyre_brand_id'])) {
                    $tyre_brand = TyreBrand::select('id')->where(['id' => $pd['tyre_brand_id']])->first();
                    if (!$tyre_brand) {
                        return ['status' => false, 'message' => 'Please fill correct tyre_brand ID in record number - ' . ($k + 1)];
                    }
                }

                //VERIFY BATTERY BRAND
                if (isset($pd['battery_brand_id'])) {
                    $battery_brand = BatteryBrand::select('id')->where(['id' => $pd['battery_brand_id']])->first();
                    if (!$battery_brand) {
                        return ['status' => false, 'message' => 'Please fill correct battery_brand ID in record number - ' . ($k + 1)];
                    }
                }
            }
        }
        return ['status' => true];
    }
}
