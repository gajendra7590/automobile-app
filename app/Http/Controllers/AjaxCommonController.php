<?php

namespace App\Http\Controllers;

use App\Models\BankFinancer;
use App\Models\BatteryBrand;
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
use App\Models\Purchase;
use App\Models\Quotation;
use App\Models\RtoAgent;
use App\Models\Sale;
use App\Models\Salesman;
use App\Models\SkuSalePrice;
use App\Models\State;
use App\Models\TyreBrand;
use App\Models\User;
use Illuminate\Http\Request;

use App\Traits\DropdownHelper;
use Illuminate\Support\Facades\DB;

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
                    case 'dealers':
                        return $this->getDealers($postData);
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
                    case 'salesman':
                        $model = new Salesman;
                        break;
                    case 'batteryBrands':
                        $model = new BatteryBrand;
                        break;
                    case 'tyreBrands':
                        $model = new TyreBrand;
                        break;
                    case 'skuSalesPrice':
                        $model = new SkuSalePrice;
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

    /**
     * @param Query Param
     * @return Json Array
     * @description - Function is created for get all list of enitities by type for select2 ajax search
     */
    public function select2DropdownByType(Request $request)
    {
        $postData = $request->all();
        $data = array();
        if (isset($postData['type']) && ($postData['type'] != '')) {
            switch ($postData['type']) {
                case 'quotations':
                    $data = Quotation::select('id', DB::raw('customer_name	 as text'));
                    if (isset($postData['search']) && ($postData['search'] != "")) {
                        $data = $data->where('customer_name', 'LIKE', '%' . $postData['search'] . '%');
                    }
                    $data = $data->get();
                    break;
                case 'purchases':
                    $data = Purchase::select('id', DB::raw('CONCAT(sku,"-", dc_number, "-", vin_number,"-",hsn_number) AS text'));
                    if (isset($postData['search']) && ($postData['search'] != "")) {
                        $data = $data->where('sku', 'LIKE', '%' . $postData['search'] . '%')
                            ->orwhere('dc_number', 'LIKE', '%' . $postData['search'] . '%')
                            ->orwhere('vin_number', 'LIKE', '%' . $postData['search'] . '%')
                            ->orwhere('hsn_number', 'LIKE', '%' . $postData['search'] . '%');
                    }
                    $data = $data->get();
                    break;
                case 'sales':
                    $Query = Sale::select('id', 'purchase_id', 'customer_name')->with([
                        'purchases' => function ($q) {
                            $q->select('id', 'sku', 'vin_number', 'hsn_number', 'engine_number');
                        }
                    ]);
                    $data = array();
                    if (isset($postData['search']) && ($postData['search'] != "")) {
                        $search_string = $postData['search'];
                        $Query = $Query->whereHas('purchases', function ($q) use ($search_string) {
                            $q->where('sku', 'LIKE', '%' . $search_string . '%')
                                ->orWhere('vin_number', 'LIKE', '%' . $search_string . '%')
                                ->orWhere('hsn_number', 'LIKE', '%' . $search_string . '%')
                                ->orWhere('engine_number', 'LIKE', '%' . $search_string . '%');
                        });
                    }
                    $results = $Query->limit(100)->get();
                    foreach ($results as $k => $result) {
                        $data[$k] = array(
                            'id' => $result->id,
                            'text' => createStringSales($result)
                        );
                    }
                    break;
                case 'users':
                    $data = User::select('id', DB::raw('name as text'));
                    if (isset($postData['search']) && ($postData['search'] != "")) {
                        $data = $data->where('name', 'LIKE', '%' . $postData['search'] . '%');
                    }
                    $data = $data->get();
                    break;
                case 'brokers':
                    $data = Broker::select('id', DB::raw('name as text'));
                    if (isset($postData['search']) && ($postData['search'] != "")) {
                        $data = $data->where('name', 'LIKE', '%' . $postData['search'] . '%');
                    }
                    $data = $data->get();
                    break;
                case 'bankFinancers':
                    $data = BankFinancer::select('id', DB::raw('bank_name as text'));
                    if (isset($postData['search']) && ($postData['search'] != "")) {
                        $data = $data->where('bank_name', 'LIKE', '%' . $postData['search'] . '%');
                    }
                    $data = $data->get();
                    break;
                case 'bikeDealers':
                    $data = BikeDealer::select('id', DB::raw('company_name as text'));
                    if (isset($postData['search']) && ($postData['search'] != "")) {
                        $data = $data->where('company_name', 'LIKE', '%' . $postData['search'] . '%');
                    }
                    $data = $data->get();
                    break;
                default:
                    # code...
                    break;
            }
        }

        return response()->json([
            'status'  => true,
            'results' => $data,
            'message' => "List retrieved successfully"
        ]);
    }
}
