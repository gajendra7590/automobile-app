<?php

namespace App\Traits;

use App\Models\BankAccounts;
use App\Models\BankFinancer;
use App\Models\BatteryBrand;
use App\Models\Broker;
use App\Models\BikeBrand;
use App\Models\BikeColor;
use App\Models\BikeDealer;
use App\Models\BikeModel;
use App\Models\BikeModelVariant;
use App\Models\Branch;
use App\Models\City;
use App\Models\District;
use App\Models\GstRates;
use App\Models\GstRtoRates;
use App\Models\Purchase;
use App\Models\RtoAgent;
use App\Models\Salesman;
use App\Models\State;
use App\Models\TyreBrand;
use Illuminate\Support\Facades\Auth;

trait CommonHelper
{

    public static function getCurrentUserBranch()
    {
        $branch_id = 0;
        if (Auth::user()) {
            $branch_id = intval(Auth::user()->branch_id);
        }
        return $branch_id;
    }

    public static function getAllBranchesWithInActive($select_all = false)
    {
        $model = null;
        if ($select_all == false) {
            $model = Branch::select('id', 'branch_name');
        } else {
            $model = Branch::select('*');
        }

        //Filter by branch
        if (self::getCurrentUserBranch() != '0') {
            $model = $model->where('id', self::getCurrentUserBranch());
        }
        return $model->get();
    }


    /**
     * Get All Branches
     */
    public static function _getBranches($select_all = false)
    {
        $model = Branch::where('active_status', '1');

        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'branch_name');
        }

        //Filter by branch
        if (!empty(self::getCurrentUserBranch())) {
            $model = $model->where('id', self::getCurrentUserBranch());
        }

        return $model->get();
    }

    /**
     * Get One Branch
     */
    public static function _getBranchById($branch_id, $select_all = false)
    {
        $model = null;
        if ($select_all == false) {
            $model = Branch::select('id', 'branch_name');
        } else {
            $model =  Branch::select('*');
        }
        return $model->where('id', $branch_id)->get();
    }

    /**
     * Get All Dealers
     */
    public static function _getDealers($select_all = false)
    {
        $model = BikeDealer::where('active_status', '1');
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'dealer_code', 'company_name');
        }

        //Filter by branch
        if (self::getCurrentUserBranch() != '0') {
            $model = $model->where('branch_id', self::getCurrentUserBranch());
        }
        return $model->get();
    }

    /**
     * Get One Dealer By Dealer Id
     */
    public static function _getDealerById($id, $select_all = false)
    {
        $model = BikeDealer::where('id', $id);
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'dealer_code', 'company_name');
        }
        return $model->get();
    }

    /**
     * Get All Brands
     */
    public static function _getBrands($select_all = false, $branch_id = null)
    {
        $model = BikeBrand::where('active_status', '1');
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'name');
        }
        //Filter by branch
        if (self::getCurrentUserBranch() != '0') {
            $model = $model->where('branch_id', self::getCurrentUserBranch());
        }
        return $model->get();
    }

    /**
     * Get One Brand
     */
    public static function _getBrandByBranch($branch_id, $select_all = false)
    {
        $model = null;
        if ($select_all == false) {
            $model = BikeBrand::select('id', 'name');
        } else {
            $model =  BikeBrand::select('*');
        }
        return $model->where('branch_id', $branch_id)->get();
    }

    /**
     * Get One Brand By Id
     */
    public static function _getBrandById($brand_id, $select_all = false)
    {
        $model = null;
        if ($select_all == false) {
            $model = BikeBrand::select('id', 'name');
        } else {
            $model =  BikeBrand::select('*');
        }
        return $model->where('id', $brand_id)->get();
    }

    /**
     * Get All Models
     */
    public static function _getModels($brand_id = 0, $select_all = false)
    {
        $model = BikeModel::where('active_status', '1');
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'model_name', 'variant_code');
        }

        //Filter by brand
        if (intval($brand_id) > 0) {
            $model = $model->where('brand_id', $brand_id);
        }

        //Filter by branch
        if (self::getCurrentUserBranch() != '0') {
            $model = $model->whereHas('bike_brand', function ($bb) {
                $bb->where('branch_id', self::getCurrentUserBranch());
            });
        }
        return $model->get();
    }

    /**
     * Get One Model
     */
    public static function _getModelByBrand($brand_id, $select_all = false)
    {
        $model = null;
        if ($select_all == false) {
            $model = BikeModel::select('id', 'model_name', 'variant_code');
        } else {
            $model =  BikeModel::select('*');
        }
        return $model->where('brand_id', $brand_id)->get();
    }

    /**
     * Get One Model By Id
     */
    public static function _getModelById($model_id, $select_all = false)
    {
        $model = null;
        if ($select_all == false) {
            $model = BikeModel::select('id', 'model_name', 'variant_code');
        } else {
            $model =  BikeModel::select('*');
        }
        return $model->where('id', $model_id)->get();
    }

    /**
     * Get All Variants
     */
    public static function _getVaraints($model_id, $select_all = false)
    {
        $model = BikeModelVariant::where('active_status', '1')->where('model_id', $model_id);
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'model_id', 'variant_name');
        }
        return $model->get();
    }

    /**
     * Get One Variant By Id
     */
    public static function _getVaraintById($varId, $select_all = false)
    {
        $model = null;
        if ($select_all == false) {
            $model = BikeModelVariant::select('id', 'model_id', 'variant_name');
        } else {
            $model =  BikeModelVariant::select('*');
        }
        return $model->where('id', $varId)->get();
    }

    /**
     * Get All Colors
     */
    public static function _getColors($var_id, $select_all = false)
    {
        $model = BikeColor::where('active_status', '1');
        if ($select_all == false) {
            $model = $model->select('id', 'color_name', 'sku_code');
        }
        return $model->where('model_variant_id', $var_id)->get();
    }

    /**
     * Get One Colors By Id
     */
    public static function _getColorById($color_id, $select_all = false)
    {
        $model = null;
        if ($select_all == false) {
            $model = BikeColor::select('id', 'color_name', 'sku_code');
        } else {
            $model =  BikeColor::select('*');
        }
        return $model->where('id', $color_id)->get();
    }


    /**
     * Get All Battery Brands
     */
    public static function _getBatteryBrands($select_all = false)
    {
        $model = BatteryBrand::where('active_status', '1');
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'name');
        }
        return $model->get();
    }

    /**
     * Get All Battery Brands
     */
    public static function _getTyreBrands($select_all = false)
    {
        $model = TyreBrand::where('active_status', '1');
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'name');
        }
        return $model->get();
    }


    /**
     * Get All States
     */
    public static function _getStates($country_id = 0, $select_all = false)
    {
        $model = State::where('active_status', '1');

        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'state_name');
        }

        //Filter by country
        if (intval($country_id) > 0) {
            $model = $model->where('country_id', $country_id);
        }
        return $model->get();
    }

    /**
     * Get States by country id
     */
    public static function _getStatesByCountry($country_id = 1, $select_all = false)
    {
        $model = null;
        if ($select_all == false) {
            $model = State::select('id', 'state_name');
        } else {
            $model = State::select('*');
        }
        return $model->where('country_id', $country_id)->get();
    }

    /**
     * Get All Districts
     */
    public static function _getDistricts($state_id = 0, $select_all = false)
    {
        $model = District::where('active_status', '1');
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'district_name');
        }
        //Filter by state
        if (intval($state_id) > 0) {
            $model = $model->where('state_id', $state_id);
        }
        return $model->get();
    }

    /**
     * Get District by state id
     */
    public static function _getDistrictsByStateId($state_id, $select_all = false)
    {
        $model = null;
        if ($select_all == false) {
            $model = District::select('id', 'district_name');
        } else {
            $model = District::select('*');
        }
        return $model->where('state_id', $state_id)->get();
    }

    /**
     * Get All Cities
     */
    public static function _getCities($dist_id = 0, $select_all = false)
    {
        $model = City::where('active_status', '1');

        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'city_name');
        }

        //Filter by city
        if (intval($dist_id) > 0) {
            $model = $model->where('district_id', $dist_id);
        }
        return $model->get();
    }

    /**
     * Get Cities by district id
     */
    public static function _getCitiesByDistrictId($district_id, $select_all = false)
    {
        $model = null;
        if ($select_all == false) {
            $model = City::select('id', 'city_name');
        } else {
            $model = City::select('*');
        }
        return $model->where('district_id', $district_id)->get();
    }

    /**
     * Get All GST Rates
     */
    public static function _getGstRates($select_all = false)
    {
        $model = GstRates::where('active_status', '1');

        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'gst_rate', 'cgst_rate', 'sgst_rate', 'igst_rate');
        }
        return $model->get();
    }

    /**
     * Get All GST Rates
     */
    public static function _getGstRatesById($id, $select_all = false)
    {
        $model = GstRates::where('id', $id);
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'gst_rate', 'cgst_rate', 'sgst_rate', 'igst_rate');
        }
        return $model->get();
    }


    /**
     * Get All RTO Gst Rates
     */
    public static function _getRtoGstRates($select_all = false)
    {
        $model = GstRtoRates::where('active_status', '1');
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'gst_rate', 'cgst_rate', 'sgst_rate', 'igst_rate');
        }
        return $model->get();
    }


    /**
     * Get All Users
     */
    public static function _getUsers()
    {
    }

    /**
     * Get All Brokers
     */
    public static function _getBrokers($select_all = false)
    {
        $model = Broker::where('active_status', '1');
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'name', 'email');
        }
        return $model->get();
    }


    /**
     * Get All Finaceirs
     */
    public static function _getFinaceirs($financer_type = 1, $select_all = false)
    {
        $model = BankFinancer::where('active_status', '1');
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'bank_name', 'bank_branch_code', 'financer_type');
        }

        if ($financer_type > 0 && in_array($financer_type, ['1', '2'])) {
            $model = $model->where('financer_type', $financer_type);
        }
        return $model->get();
    }

    /**
     * Get All RTo Agents
     */
    public static function _getRtoAgents($select_all = false)
    {
        $model = RtoAgent::where('active_status', '1');
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'agent_name');
        }
        return $model->get();
    }

    /**
     * Get All Salemans list
     */
    public static function _getSalesman($select_all = false)
    {
        $model = Salesman::where('active_status', '1');
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'name');
        }
        return $model->get();
    }

    /**
     * Get Saleman
     */
    public static function _getSalesmanById($id = false)
    {
        return Salesman::select('id', 'name')->where('id', $id)->get();
    }

    /**
     * Get Active Purchases
     */
    public static function _getInStockPurchases($branch_id = 0, $select_all = false)
    {
        //Filter By Status : Un Sold / InStock
        $model = Purchase::where('status', '1')->with([
            'branch' => function ($bb) {
                $bb->select('id', 'branch_name');
            },
            'brand' => function ($bb) {
                $bb->select('id', 'name');
            },
            'model' => function ($bb) {
                $bb->select('id', 'model_name');
            },
            'modelColor' => function ($bb) {
                $bb->select('id', 'color_name');
            }
        ]);
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'dc_number', 'vin_number', 'engine_number', 'sku', 'bike_branch', 'bike_brand', 'bike_model', 'bike_model_color', 'created_at');
        }

        if ($branch_id > 0) {
            $model = $model->where('bike_branch', $branch_id);
        }

        //Filter by branch
        if (self::getCurrentUserBranch() != '0') {
            $model = $model->where('bike_branch', self::getCurrentUserBranch());
        }
        return $model->get();
    }

    /**
     * Get Active Purchases
     */
    public static function _getOnePurchases($id = 0, $select_all = false)
    {
        //Filter By Status : Un Sold / InStock
        $model = Purchase::with([
            'branch' => function ($bb) {
                $bb->select('id', 'branch_name');
            },
            'brand' => function ($bb) {
                $bb->select('id', 'name');
            },
            'model' => function ($bb) {
                $bb->select('id', 'model_name');
            },
            'modelColor' => function ($bb) {
                $bb->select('id', 'color_name');
            }
        ]);
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'dc_number', 'vin_number', 'sku', 'bike_branch', 'bike_brand', 'bike_model', 'bike_model_color');
        }

        if ($id > 0) {
            $model = $model->where('id', $id);
        }

        //Filter by branch
        if (self::getCurrentUserBranch() != '0') {
            $model = $model->where('id', self::getCurrentUserBranch());
        }
        return $model->get();
    }

    /**
     * Get Active Purchases
     */
    public static function _getPurchasesById($id = 0, $select_all = false)
    {
        //Filter By Status : Un Sold / InStock
        $model = Purchase::with([
            'branch' => function ($bb) {
                $bb->select('id', 'branch_name');
            },
            'brand' => function ($bb) {
                $bb->select('id', 'name');
            },
            'model' => function ($bb) {
                $bb->select('id', 'model_name');
            },
            'modelColor' => function ($bb) {
                $bb->select('id', 'color_name');
            }
        ]);
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'dc_number', 'vin_number', 'sku', 'bike_branch', 'bike_brand', 'bike_model', 'bike_model_color');
        }

        if ($id > 0) {
            $model = $model->where('id', $id);
        }
        return $model->get();
    }

    /**
     * Get All Branches
     */
    public static function _getBankAccounts($select_all = true)
    {
        $model = [];
        //Filter by branch
        if (!empty(self::getCurrentUserBranch())) {
            $model = BankAccounts::where('id', self::getCurrentUserBranch())->get();
        } else {
            $model = BankAccounts::get();
        }

        return $model;
    }
}
