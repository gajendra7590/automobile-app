<?php

namespace App\Http\Controllers;

use App\Models\BankFinancer;
use App\Models\BikeBrand;
use App\Models\BikeColor;
use App\Models\BikeDealer;
use App\Models\BikeModel;
use App\Models\Branch;
use App\Models\Broker;
use App\Models\City;
use App\Models\District;
use App\Models\GstRates;
use App\Models\GstRtoRates;
use App\Models\RtoAgent;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;

use App\Traits\DropdownHelper;

class AjaxCommonController extends Controller
{
    use DropdownHelper;

    /**
     * Common function for handle common ajax dropdowns
     */
    public function index(Request $request)
    {
        try {
            $postData = $request->all();
            if (isset($postData['req']) && ($postData['req'] != '')) {

                switch ($postData['req']) {
                    case 'states':
                        return $this->getStates($postData);
                        break;
                    case 'districts':
                        return $this->getDistricts($postData);
                        break;
                    case 'cities':
                        return $this->getCities($postData);
                        break;
                    case 'branches':
                        return $this->getBranches($postData);
                        break;
                    case 'brands':
                        return $this->getBrands($postData);
                        break;
                    case 'models':
                        return $this->getModels($postData);
                        break;
                    case 'colors':
                        return $this->getColors($postData);
                        break;
                    case 'financiers_list':
                        return $this->getFinanciersList($postData);
                        break;
                    default:
                        # code...
                        break;
                }
            } else {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => "Sorry! invalid request"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => $e->getMessage(),
                'data'       => ['file' => $e->getFile(), 'line' => $e->getLine()]
            ]);
        }
    }

    public function status(Request $request, $id)
    {
        $model = null;
        try {
            $postData = $request->all();
            if (isset($postData['type']) && ($postData['type'] != '')) {
                switch ($postData['type']) {
                    case 'state':
                        $model = new State;
                        break;
                    case 'district':
                        $model = new District;
                        break;
                    case 'city':
                        $model = new City;
                        break;
                    case 'branch':
                        $model = new Branch;
                        break;
                    case 'brand':
                        $model = new BikeBrand;
                        break;
                    case 'model':
                        $model = new BikeModel;
                        break;
                    case 'color':
                        $model = new BikeColor;
                        break;
                    case 'bankFinancer':
                        $model = new BankFinancer;
                        break;
                    case 'Broker':
                        $model = new Broker;
                        break;
                    case 'dealer':
                        $model = new BikeDealer;
                        break;
                    case 'gstRate':
                        $model = new GstRates;
                        break;
                    case 'gstRtoRate':
                        $model = new GstRtoRates;
                        break;
                    case 'RtoAgent':
                        $model = new RtoAgent;
                        break;
                    case 'user':
                        $model = new User;
                        break;
                    default:
                        # code...
                        break;
                }
            } else {
                return response()->json([
                    'status'     => false,
                    'statusCode' => 419,
                    'message'    => "Sorry! invalid request"
                ]);
            }

            $model = $model->where('id', $id);
            if ((clone $model)->value('active_status')) {
                $model->update(['active_status' => 0]);
            } else {
                $model->update(['active_status' => 1]);
            }
            return response()->json([
                'status'     => false,
                'statusCode' => 419,
                'message'    => 'Update Successfully'
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

    public function plusAction(Request $request)
    {
        $data = "";
        switch (request('type')) {
            case 'city':
                $data = view('admin.models.plusModals.cityModal', [
                    'action' => route('cityStore'),
                    'district_id' => request('district_id'),
                    'ddname' => request('ddname'),
                    'redirect' => 'closeModal',
                    'type' => 'customer_city',
                    'modalId' => request('modalId') ? request('modalId') : 'ajaxModalCommon',
                ])->render();
                break;
            default:
                break;
        }
        return response()->json([
            'status'     => true,
            'statusCode' => 200,
            'message'    => trans('messages.ajax_model_loaded'),
            'data'       => $data
        ]);
    }
}
