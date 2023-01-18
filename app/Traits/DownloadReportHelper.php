<?php

namespace App\Traits;

use App\Models\Purchase;
use App\Models\Quotation;
use App\Models\RtoRegistration;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

trait DownloadReportHelper
{

    public static function getReport()
    {
        try {
            self::setDate();
            self::setQuery();
            self::setType();
            $query = config('query')->whereDate('purchases.created_at','>=',config('start_date'))->whereDate('purchases.created_at','<=',config('end_date'));
            $query = $query->get()->toArray();
            $heading = config('heading');
            if(count($heading)){
                array_unshift($query,$heading);
            }
            return $query;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function setDate(){
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-d',strtotime("$end_date -1 month"));
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
                    break;
            }
        }
        config([
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }
    public static function setQuery(){
        $query = null;
        switch (request('type')) {
            case 'purchase':
                $query = Purchase::join('branches', 'purchases.bike_branch', '=', 'branches.id')->join('bike_dealers', 'purchases.bike_dealer', '=', 'bike_dealers.id')->join('bike_brands', 'purchases.bike_brand', '=', 'bike_brands.id')->join('bike_models', 'purchases.bike_model', '=', 'bike_models.id')->join('bike_colors', 'purchases.bike_model_color', '=', 'bike_colors.id')->join('battery_brands', 'purchases.battery_brand_id', '=', 'battery_brands.id')->join('tyre_brands', 'purchases.tyre_brand_id', '=', 'tyre_brands.id')
                    ->when(!empty(request('bike_model')), function ($q) {
                        $q->where('purchases.bike_model', request('bike_model'));
                    })->select('branches.branch_name as branch','bike_dealers.dealer_code as dealer_code','bike_dealers.company_name as dealer_company_name','bike_brands.name as brand','bike_models.model_name as model','bike_colors.color_name as color','battery_brands.name as battery_brand','tyre_brands.name as tyre_brand','tyre_front_number','tyre_rear_number','battery_number','bike_type','bike_fuel_type','break_type','wheel_type','dc_number','dc_date','vin_number','vin_physical_status','variant','sku','sku_description','hsn_number','engine_number','key_number','service_book_number','gst_rate','gst_rate_percent','pre_gst_amount','gst_amount','ex_showroom_price','discount_price','grand_total','bike_description','transfer_status');
                $heading = ['Branch','Dealer Code','Dealer Company Name','Brand','Model','Color','Battery Brand','Tyre Brand','Tyre front Number','Tyre rear Number','Battery Number','Bike Type','Bike fuel Type','Break Type','Wheel Type','Dc Number','Dc Date','Vin Number','Vin Physical Status','Variant','SKU','SKU Description','HSN Number','Engine Number','key Number','Service book Number','Gst Rate','Gst Rate Percent','Pre Gst Amount','Gst Amount','Ex Showroom Price','Discount Price','Grand Total','Bike Description','Transfer Status'];
                break;
            case 'sales':
                $query = new Sale;
                break;
            case 'quotations':
                $query = new Quotation;
                break;
            case 'rto':
                $query = new RtoRegistration;
                break;
            default:
                //
                break;
        }
        config([
            'query' => $query,
            'heading' => $heading
        ]);
    }
    public static function setType(){
        $query = config('query');
        switch(request('report_type')){
            case 'purchases_register':
                //
                break;
            case 'purchase_pending_invoice':
                $query = $query->has('invoice');
                break;
        }
        config(['query' => $query ]);
    }
}
