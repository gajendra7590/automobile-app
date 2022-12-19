<?php

namespace App\Http\Controllers;

use App\Models\BikeBrand;
use App\Models\BikeColor;
use App\Models\BikeDealer;
use App\Models\BikeModel;
use App\Models\BikePurchased;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BikePurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.purchases.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array(
            'branches' => Branch::select('id', 'branch_name')->get(),
            'dealers' => BikeDealer::select('id', 'company_name')->get(),
            'brands' => BikeBrand::select('id', 'name')->get(),
            'colors' => BikeColor::select('id', 'color_name')->get(),
            'bike_types' => bike_types(),
            'bike_fuel_types' => bike_fuel_types(),
            'break_types' => break_types(),
            'wheel_types' => wheel_types(),
            'vin_physical_statuses' => vin_physical_statuses(),
            //Other Important Data
            'method' => 'POST',
            'action' => route('purchases.store')
        );

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
        $postData = $request->all();
        $validator = Validator::make($postData, [
            'bike_branch'               => "required",
            'bike_dealer'               => "required",
            'bike_brand'                => "required",
            'bike_model'                => "required",
            'bike_model_color'          => "required",
            'bike_type'                 => "required",
            'bike_fuel_type'            => "required",
            'break_type'                => "required",
            'wheel_type'                => "required",
            'dc_number'                 => "nullable",
            'dc_date'                   => "nullable",
            'vin_number'                => "nullable",
            'vin_physical_status'       => "nullable",
            'sku'                       => "nullable",
            'sku_description'           => "nullable",
            'hsn_number'                => "nullable",
            'model_number'              => "nullable",
            'engine_number'             => "nullable",
            'key_number'                => "nullable",
            'service_book_number'       => "nullable",
            'tyre_brand_name'           => "nullable",
            'tyre_front_number'         => "nullable",
            'tyre_rear_number'          => "nullable",
            'battery_brand'             => "nullable",
            'battery_number'            => "nullable",
            'purchase_invoice_number'   => "required",
            'purchase_invoice_amount'   => "required|numeric",
            'purchase_invoice_date'     => "required|date",
            'final_price'               => "required|numeric",
            'sale_price'                => "required|numeric",
            'bike_description'          => "required|numeric",
            'status'                    => "nullable|in:1,2"
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

        //Create New Role
        BikePurchased::create($postData);
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Created Successfully."
        ]);
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
        //
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
        //
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

    public function getModelsList($id)
    {
        $models = BikeModel::where(['brand_id' => $id])->get()->toArray();
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => "Retrived Successfully.",
            'data'       => models_list($models)
        ]);
    }
}
