<?php

namespace App\Traits;

use App\Models\Purchase;
use App\Models\Quotation;

trait DownloadReportHelper
{

    public static function getReport()
    {
        try {
            self::setQuery();
            self::setDate();
            $query = config('query');
            $query = $query->get()->toArray();
            $heading = config('heading');
            if(count($heading)){
                array_unshift($query,$heading);
            }
            $result = [];
            if($query && count($query)){
                foreach($query as $value) {
                    $result[] = (array)$value;
                }
            }
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function setDate($query = null) {
        $query = $query ? $query : config('query');
        $end_date = null;
        $start_date = null;
        if(!empty(request('duration'))){
            switch(request('duration')){
                case 'last_month':
                    $end_date = date('Y-m-d');
                    $start_date = date('Y-m-d',strtotime("$end_date -1 month"));
                    break;
                case 'last_six_months':
                    $end_date = date('Y-m-d');
                    $start_date = date('Y-m-d',strtotime("$end_date -6 months"));
                    break;
                case 'last_one_year':
                    $end_date = date('Y-m-d');
                    $start_date = date('Y-m-d',strtotime("$end_date -1 year"));
                    break;
                case 'custom':
                    $end_date = request('end_date') ?? date('Y-m-d');
                    $start_date = request('end_date') ?? date('Y-m-d',strtotime("$end_date -1 month"));
                    break;
                default:
                    $end_date = null;
                    $start_date = null;
                    break;
            }
            if($end_date && $start_date){
                $query = $query->whereDate('purchases.created_at','>=',config('start_date'))->whereDate('purchases.created_at','<=',config('end_date'));
            }
        }
        config(['query' => $query]);
        return $query;
    }

    public static function setQuery(){
        $query = null;
        $heading = [];
        switch (request('type')) {
            case 'vehicle_purchase_register' :
                $query = Purchase::join('branches', 'purchases.bike_branch', '=', 'branches.id')->join('bike_dealers', 'purchases.bike_dealer', '=', 'bike_dealers.id')->join('bike_brands', 'purchases.bike_brand', '=', 'bike_brands.id')->join('bike_models', 'purchases.bike_model', '=', 'bike_models.id')->join('bike_colors', 'purchases.bike_model_color', '=', 'bike_colors.id')->join('battery_brands', 'purchases.battery_brand_id', '=', 'battery_brands.id')->join('tyre_brands', 'purchases.tyre_brand_id', '=', 'tyre_brands.id')
                    ->when(!empty(request('bike_model')), function ($q) {
                        $q->where('purchases.bike_model', request('bike_model'));
                    })->select('branches.branch_name as branch','bike_dealers.dealer_code as dealer_code','bike_dealers.company_name as dealer_company_name','bike_brands.name as brand','bike_models.model_name as model','bike_colors.color_name as color','battery_brands.name as battery_brand','tyre_brands.name as tyre_brand','tyre_front_number','tyre_rear_number','battery_number','bike_type','bike_fuel_type','break_type','wheel_type','dc_number','dc_date','vin_number','vin_physical_status','variant','sku','sku_description','hsn_number','engine_number','key_number','service_book_number','gst_rate','gst_rate_percent','pre_gst_amount','gst_amount','ex_showroom_price','discount_price','grand_total','bike_description','transfer_status');
                $heading = ['Branch','Dealer Code','Dealer Company Name','Brand','Model','Color','Battery Brand','Tyre Brand','Tyre front Number','Tyre rear Number','Battery Number','Bike Type','Bike fuel Type','Break Type','Wheel Type','Dc Number','Dc Date','Vin Number','Vin Physical Status','Variant','SKU','SKU Description','HSN Number','Engine Number','key Number','Service book Number','Gst Rate','Gst Rate Percent','Pre Gst Amount','Gst Amount','Ex Showroom Price','Discount Price','Grand Total','Bike Description','Transfer Status'];
                break;
            case 'pending_purchase_invoice' :
                $query = Purchase::join('branches', 'purchases.bike_branch', '=', 'branches.id')->join('bike_dealers', 'purchases.bike_dealer', '=', 'bike_dealers.id')->join('bike_brands', 'purchases.bike_brand', '=', 'bike_brands.id')->join('bike_models', 'purchases.bike_model', '=', 'bike_models.id')->join('bike_colors', 'purchases.bike_model_color', '=', 'bike_colors.id')->join('battery_brands', 'purchases.battery_brand_id', '=', 'battery_brands.id')->join('tyre_brands', 'purchases.tyre_brand_id', '=', 'tyre_brands.id')
                    ->when(!empty(request('bike_model')), function ($q) {
                        $q->where('purchases.bike_model', request('bike_model'));
                    })
                    ->doesntHave('invoice')
                    ->select('branches.branch_name as branch','bike_dealers.dealer_code as dealer_code','bike_dealers.company_name as dealer_company_name','bike_brands.name as brand','bike_models.model_name as model','bike_colors.color_name as color','battery_brands.name as battery_brand','tyre_brands.name as tyre_brand','tyre_front_number','tyre_rear_number','battery_number','bike_type','bike_fuel_type','break_type','wheel_type','dc_number','dc_date','vin_number','vin_physical_status','variant','sku','sku_description','hsn_number','engine_number','key_number','service_book_number','gst_rate','gst_rate_percent','pre_gst_amount','gst_amount','ex_showroom_price','discount_price','grand_total','bike_description','transfer_status');
                $heading = ['Branch','Dealer Code','Dealer Company Name','Brand','Model','Color','Battery Brand','Tyre Brand','Tyre front Number','Tyre rear Number','Battery Number','Bike Type','Bike fuel Type','Break Type','Wheel Type','Dc Number','Dc Date','Vin Number','Vin Physical Status','Variant','SKU','SKU Description','HSN Number','Engine Number','key Number','Service book Number','Gst Rate','Gst Rate Percent','Pre Gst Amount','Gst Amount','Ex Showroom Price','Discount Price','Grand Total','Bike Description','Transfer Status'];
                break;
            case 'vehicle_stock_inventory' :
                $query = Purchase::join('branches', 'purchases.bike_branch', '=', 'branches.id')->join('bike_dealers', 'purchases.bike_dealer', '=', 'bike_dealers.id')->join('bike_brands', 'purchases.bike_brand', '=', 'bike_brands.id')->join('bike_models', 'purchases.bike_model', '=', 'bike_models.id')->join('bike_colors', 'purchases.bike_model_color', '=', 'bike_colors.id')->join('battery_brands', 'purchases.battery_brand_id', '=', 'battery_brands.id')->join('tyre_brands', 'purchases.tyre_brand_id', '=', 'tyre_brands.id')
                    ->when(!empty(request('bike_model')), function ($q) {
                        $q->where('purchases.bike_model', request('bike_model'));
                    })
                    ->when(!empty(request('status')), function ($q) {
                        $q->where('purchases.status', request('status'));
                    })
                    ->when(!empty(request('broker_id')), function ($q) {
                        $q->whereHas('purchase_transfer_latest',function ($q) {
                            $q->where('broker_id',request('broker_id'));
                        });
                    })
                    ->when(!empty(request('age')), function ($q) {
                        $q->whereDate('created_at','<=',date('Y-m-d'))
                        ->whereDate('created_at','>=',date('Y-m-d' , strtotime(date('Y-m-d') . '+1 year')));
                    })
                    ->select('branches.branch_name as branch','bike_dealers.dealer_code as dealer_code','bike_dealers.company_name as dealer_company_name','bike_brands.name as brand','bike_models.model_name as model','bike_colors.color_name as color','battery_brands.name as battery_brand','tyre_brands.name as tyre_brand','tyre_front_number','tyre_rear_number','battery_number','bike_type','bike_fuel_type','break_type','wheel_type','dc_number','dc_date','vin_number','vin_physical_status','variant','sku','sku_description','hsn_number','engine_number','key_number','service_book_number','gst_rate','gst_rate_percent','pre_gst_amount','gst_amount','ex_showroom_price','discount_price','grand_total','bike_description','transfer_status');

                $heading = ['Branch','Dealer Code','Dealer Company Name','Brand','Model','Color','Battery Brand','Tyre Brand','Tyre front Number','Tyre rear Number','Battery Number','Bike Type','Bike fuel Type','Break Type','Wheel Type','Dc Number','Dc Date','Vin Number','Vin Physical Status','Variant','SKU','SKU Description','HSN Number','Engine Number','key Number','Service book Number','Gst Rate','Gst Rate Percent','Pre Gst Amount','Gst Amount','Ex Showroom Price','Discount Price','Grand Total','Bike Description','Transfer Status'];
                break;
            case 'quotation_list' :
                $query = Quotation::when(!empty(request('bike_model')), function ($q) {
                        $q->where('bike_model', request('bike_model'));
                    })->when(!empty(request('status')), function ($q) {
                        $q->where('status', request('status'));
                    })->when(!empty(request('financer_id')), function ($q) {
                        $q->where('hyp_financer', request('hyp_financer'));
                    });
                // $heading = ['Branch','Dealer Code','Dealer Company Name','Brand','Model','Color','Battery Brand','Tyre Brand','Tyre front Number','Tyre rear Number','Battery Number','Bike Type','Bike fuel Type','Break Type','Wheel Type','Dc Number','Dc Date','Vin Number','Vin Physical Status','Variant','SKU','SKU Description','HSN Number','Engine Number','key Number','Service book Number','Gst Rate','Gst Rate Percent','Pre Gst Amount','Gst Amount','Ex Showroom Price','Discount Price','Grand Total','Bike Description','Transfer Status'];
                break;
            default:
                //
                break;
        }
        config(['query' => $query,'heading' => $heading]);
        return $query;
    }
}
