<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PlusActionController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function cityStore(Request $request)
    {
        $data = [];
        try {
            DB::beginTransaction();
            $postData = $request->only('district_id', 'cities');
            $validator = Validator::make($postData, [
                'district_id' => 'required',
                'cities.*.city_name' => "required",
                'cities.*.city_code' => 'nullable',
                'cities.*.active_status' => 'required|in:0,1'
            ], [
                'cities.*.city_name.required' => 'The City Name field is required.',
                'cities.*.active_status.required' => 'The City status field is required.'
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

            //Bulk insert
            if (count($postData['cities']) > 0) {
                foreach ($postData['cities'] as $k => $cityObj) {
                    $cityObj['district_id'] = $postData['district_id'];
                    $city = City::create($cityObj);
                }
            }

            DB::commit();

            if (request('ddname') || request('form_type')) {
                $data['html'] = "<option value='$city->id'> $city->city_name</option>";
                $data['type'] = request('ddname') ? request('ddname') : request('form_type');
            }

            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.create_success'),
                'data' => $data
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
     * Function for openFinanceDetail
     */
    public function openFinanceDetail(Request $request)
    {
        try {
            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'message'    => trans('messages.ajax_model_loaded'),
                'data'       => view('admin.ajaxViews.financeDetail')->render()
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
}
