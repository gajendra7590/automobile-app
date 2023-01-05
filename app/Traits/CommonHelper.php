<?php

namespace App\Traits;

use App\Models\BankFinancer;
use App\Models\BikeAgent;
use App\Models\BikeBrand;
use App\Models\BikeColor;
use App\Models\BikeDealer;
use App\Models\BikeModel;
use App\Models\Branch;
use App\Models\City;
use App\Models\District;
use App\Models\GstRates;
use App\Models\GstRtoRates;
use App\Models\Purchase;
use App\Models\RtoAgent;
use App\Models\State;
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

    public static function branchFilter($select_all = false)
    {
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
        if (self::getCurrentUserBranch() != '0' || self::getCurrentUserBranch() != 'null') {
            $model = $model->where('id', self::getCurrentUserBranch());
        }

        return $model->get();
    }

    /**
     * Get All Brands
     */
    public static function _getBrands($select_all = false) {
        $model = BikeBrand::where('active_status', '1');
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'name');
        }
        //Filter by branch
        if (self::getCurrentUserBranch() != '0' || self::getCurrentUserBranch() != 'null') {
            $model = $model->where('branch_id', self::getCurrentUserBranch());
        }
        if (config('type')) {
            $branch_id = Branch::value('id');
            $model = $model->where('branch_id',$branch_id);
            $model_one = clone $model;
            $model_one = $model_one->value('id');
            config(['brand_id' => $model_one]);
        }
        return $model->get();
    }


    /**
     * Get All Models
     */
    public static function _getModels($brand_id = 0, $select_all = false)
    {
        $model = BikeModel::where('active_status', '1');
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'model_name');
        }

        //Filter by brand
        if (intval($brand_id) > 0) {
            $model = $model->where('brand_id', $brand_id);
        }

        //Filter by branch
        if (self::getCurrentUserBranch() != '0' || self::getCurrentUserBranch() != 'null') {
            $model = $model->whereHas('bike_brand', function ($bb) {
                $bb->where('branch_id', self::getCurrentUserBranch());
            });
        }
        return $model->get();
    }

    /**
     * Get All Colors
     */
    public static function _getColors($model_id = 0, $select_all = false)
    {
        $model = BikeColor::where('active_status', '1');
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'color_name');
        }

        //Filter by model
        if (intval($model_id) > 0) {
            $model = $model->where('bike_model', $model_id);
        }

        //Filter by branch
        if (self::getCurrentUserBranch() != '0' || self::getCurrentUserBranch() != 'null') {
            $model = $model->whereHas('model.bike_brand', function ($bb) {
                $bb->where('branch_id', self::getCurrentUserBranch());
            });
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
     * Get All Agents
     */
    public static function _getAgents($select_all = false)
    {
        $model = BikeAgent::where('active_status', '1');
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'name', 'email');
        }
        return $model->get();
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
     * Get Active Purchases
     */
    public static function _getInStockPurchases($select_all = false)
    {
        //Filter By Status : Un Sold / InStock
        $model = Purchase::where('status', '1');
        //Select Specific
        if ($select_all == false) {
            $model = $model->select('id', 'dc_number', 'vin_number', 'sku');
        }

        //Filter by branch
        if (self::getCurrentUserBranch() != '0' || self::getCurrentUserBranch() != 'null') {
            $model = $model->where('id', self::getCurrentUserBranch());
        }
        return $model->get();
    }
}
