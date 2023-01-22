<?php

namespace App\Traits;

use App\Models\Purchase;
use App\Models\Quotation;
use App\Models\Sale;

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
            if (count($heading)) {
                array_unshift($query, $heading);
            }
            $result = [];
            if ($query && count($query)) {
                foreach ($query as $value) {
                    $result[] = (array)$value;
                }
            }
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function setDate($query = null)
    {
        $query = $query ? $query : config('query');
        $end_date = null;
        $start_date = null;
        if (!empty(request('duration'))) {
            switch (request('duration')) {
                case 'last_month':
                    $end_date = date('Y-m-d');
                    $start_date = date('Y-m-d', strtotime("$end_date -1 month"));
                    break;
                case 'last_six_months':
                    $end_date = date('Y-m-d');
                    $start_date = date('Y-m-d', strtotime("$end_date -6 months"));
                    break;
                case 'last_one_year':
                    $end_date = date('Y-m-d');
                    $start_date = date('Y-m-d', strtotime("$end_date -1 year"));
                    break;
                case 'custom':
                    $end_date = request('end_date') ?? date('Y-m-d');
                    $start_date = request('end_date') ?? date('Y-m-d', strtotime("$end_date -1 month"));
                    break;
                default:
                    $end_date = null;
                    $start_date = null;
                    break;
            }
            if ($end_date && $start_date) {
                $query = $query->whereDate('purchases.created_at', '>=', config('start_date'))->whereDate('purchases.created_at', '<=', config('end_date'));
            }
        }
        config(['query' => $query]);
        return $query;
    }

    public static function setQuery()
    {
        $query = null;
        $heading = [];
        switch (request('type')) {
            case 'vehicle_purchase_register':
                $query = Purchase::join('branches', 'purchases.bike_branch', '=', 'branches.id')
                    ->join('bike_dealers', 'purchases.bike_dealer', '=', 'bike_dealers.id')
                    ->join('bike_brands', 'purchases.bike_brand', '=', 'bike_brands.id')
                    ->join('bike_models', 'purchases.bike_model', '=', 'bike_models.id')
                    ->join('bike_colors', 'purchases.bike_model_color', '=', 'bike_colors.id')
                    ->join('battery_brands', 'purchases.battery_brand_id', '=', 'battery_brands.id')
                    ->join('tyre_brands', 'purchases.tyre_brand_id', '=', 'tyre_brands.id')
                    ->when(!empty(request('model_id')), function ($q) {
                        $q->where('purchases.bike_model', request('model_id'));
                    })->when(!empty(request('brand_id')), function ($q) {
                        $q->where('purchases.bike_brand', request('brand_id'));
                    })->select('branches.branch_name as branch', 'bike_dealers.dealer_code as dealer_code', 'bike_dealers.company_name as dealer_company_name', 'bike_brands.name as brand', 'bike_models.model_name as model', 'bike_colors.color_name as color', 'battery_brands.name as battery_brand', 'tyre_brands.name as tyre_brand', 'tyre_front_number', 'tyre_rear_number', 'battery_number', 'bike_type', 'bike_fuel_type', 'break_type', 'wheel_type', 'dc_number', 'dc_date', 'vin_number', 'vin_physical_status', 'variant', 'sku', 'sku_description', 'hsn_number', 'engine_number', 'key_number', 'service_book_number', 'gst_rate', 'gst_rate_percent', 'pre_gst_amount', 'gst_amount', 'ex_showroom_price', 'discount_price', 'grand_total', 'bike_description', 'transfer_status');
                $heading = ['Branch', 'Dealer Code', 'Dealer Company Name', 'Brand', 'Model', 'Color', 'Battery Brand', 'Tyre Brand', 'Tyre front Number', 'Tyre rear Number', 'Battery Number', 'Bike Type', 'Bike fuel Type', 'Break Type', 'Wheel Type', 'Dc Number', 'Dc Date', 'Vin Number', 'Vin Physical Status', 'Variant', 'SKU', 'SKU Description', 'HSN Number', 'Engine Number', 'key Number', 'Service book Number', 'Gst Rate', 'Gst Rate Percent', 'Pre Gst Amount', 'Gst Amount', 'Ex Showroom Price', 'Discount Price', 'Grand Total', 'Bike Description', 'Transfer Status'];
                break;
            case 'pending_purchase_invoice':
                $query = Purchase::join('branches', 'purchases.bike_branch', '=', 'branches.id')->join('bike_dealers', 'purchases.bike_dealer', '=', 'bike_dealers.id')->join('bike_brands', 'purchases.bike_brand', '=', 'bike_brands.id')->join('bike_models', 'purchases.bike_model', '=', 'bike_models.id')->join('bike_colors', 'purchases.bike_model_color', '=', 'bike_colors.id')->join('battery_brands', 'purchases.battery_brand_id', '=', 'battery_brands.id')->join('tyre_brands', 'purchases.tyre_brand_id', '=', 'tyre_brands.id')
                    ->when(!empty(request('model_id')), function ($q) {
                        $q->where('purchases.bike_model', request('model_id'));
                    })->when(!empty(request('brand_id')), function ($q) {
                        $q->where('purchases.bike_brand', request('brand_id'));
                    })->doesntHave('invoice')->select('branches.branch_name as branch', 'bike_dealers.dealer_code as dealer_code', 'bike_dealers.company_name as dealer_company_name', 'bike_brands.name as brand', 'bike_models.model_name as model', 'bike_colors.color_name as color', 'battery_brands.name as battery_brand', 'tyre_brands.name as tyre_brand', 'tyre_front_number', 'tyre_rear_number', 'battery_number', 'bike_type', 'bike_fuel_type', 'break_type', 'wheel_type', 'dc_number', 'dc_date', 'vin_number', 'vin_physical_status', 'variant', 'sku', 'sku_description', 'hsn_number', 'engine_number', 'key_number', 'service_book_number', 'gst_rate', 'gst_rate_percent', 'pre_gst_amount', 'gst_amount', 'ex_showroom_price', 'discount_price', 'grand_total', 'bike_description', 'transfer_status');
                $heading = ['Branch', 'Dealer Code', 'Dealer Company Name', 'Brand', 'Model', 'Color', 'Battery Brand', 'Tyre Brand', 'Tyre front Number', 'Tyre rear Number', 'Battery Number', 'Bike Type', 'Bike fuel Type', 'Break Type', 'Wheel Type', 'Dc Number', 'Dc Date', 'Vin Number', 'Vin Physical Status', 'Variant', 'SKU', 'SKU Description', 'HSN Number', 'Engine Number', 'key Number', 'Service book Number', 'Gst Rate', 'Gst Rate Percent', 'Pre Gst Amount', 'Gst Amount', 'Ex Showroom Price', 'Discount Price', 'Grand Total', 'Bike Description', 'Transfer Status'];
                break;
            case 'vehicle_stock_inventory':
                $query = Purchase::join('branches', 'purchases.bike_branch', '=', 'branches.id')->join('bike_dealers', 'purchases.bike_dealer', '=', 'bike_dealers.id')->join('bike_brands', 'purchases.bike_brand', '=', 'bike_brands.id')->join('bike_models', 'purchases.bike_model', '=', 'bike_models.id')->join('bike_colors', 'purchases.bike_model_color', '=', 'bike_colors.id')->join('battery_brands', 'purchases.battery_brand_id', '=', 'battery_brands.id')->join('tyre_brands', 'purchases.tyre_brand_id', '=', 'tyre_brands.id')
                    ->when(!empty(request('model_id')), function ($q) {
                        $q->where('purchases.bike_model', request('model_id'));
                    })->when(!empty(request('brand_id')), function ($q) {
                        $q->where('purchases.bike_brand', request('brand_id'));
                    })->when(!empty(request('status')), function ($q) {
                        $q->where('purchases.status', request('status'));
                    })->when(!empty(request('broker_id')), function ($q) {
                        $q->whereHas('purchase_transfer_latest', function ($q) {
                            $q->where('broker_id', request('broker_id'));
                        });
                    })->when(!empty(request('age')), function ($q) {
                        $q->whereDate('created_at', '<=', date('Y-m-d'))
                            ->whereDate('created_at', '>=', date('Y-m-d', strtotime(date('Y-m-d') . '+1 year')));
                    })->select('branches.branch_name as branch', 'bike_dealers.dealer_code as dealer_code', 'bike_dealers.company_name as dealer_company_name', 'bike_brands.name as brand', 'bike_models.model_name as model', 'bike_colors.color_name as color', 'battery_brands.name as battery_brand', 'tyre_brands.name as tyre_brand', 'tyre_front_number', 'tyre_rear_number', 'battery_number', 'bike_type', 'bike_fuel_type', 'break_type', 'wheel_type', 'dc_number', 'dc_date', 'vin_number', 'vin_physical_status', 'variant', 'sku', 'sku_description', 'hsn_number', 'engine_number', 'key_number', 'service_book_number', 'gst_rate', 'gst_rate_percent', 'pre_gst_amount', 'gst_amount', 'ex_showroom_price', 'discount_price', 'grand_total', 'bike_description', 'transfer_status');

                $heading = ['Branch', 'Dealer Code', 'Dealer Company Name', 'Brand', 'Model', 'Color', 'Battery Brand', 'Tyre Brand', 'Tyre front Number', 'Tyre rear Number', 'Battery Number', 'Bike Type', 'Bike fuel Type', 'Break Type', 'Wheel Type', 'Dc Number', 'Dc Date', 'Vin Number', 'Vin Physical Status', 'Variant', 'SKU', 'SKU Description', 'HSN Number', 'Engine Number', 'key Number', 'Service book Number', 'Gst Rate', 'Gst Rate Percent', 'Pre Gst Amount', 'Gst Amount', 'Ex Showroom Price', 'Discount Price', 'Grand Total', 'Bike Description', 'Transfer Status'];
                break;
            case 'quotation_list':
                $query = Quotation::join('branches', 'branches.id', '=', 'quotations.branch_id')
                    ->join('salesmans',  'salesmans.id',  '=',  'quotations.salesman_id')
                    ->join('u_states',    'u_states.id',    '=',  'quotations.customer_state')
                    ->join('u_districts', 'u_districts.id', '=',  'quotations.customer_district')
                    ->join('u_cities',    'u_cities.id',    '=',  'quotations.customer_city')
                    ->join('bike_brands', 'bike_brands.id', '=',  'quotations.bike_brand')
                    ->join('bike_models', 'bike_models.id', '=',  'quotations.bike_color')
                    ->join('bike_colors', 'bike_colors.id', '=',  'quotations.bike_model')
                    ->when(!empty(request('brand_id')), function ($q) {
                        $q->where('bike_brand', request('brand_id'));
                    })->when(!empty(request('model_id')), function ($q) {
                        $q->where('bike_model', request('model_id'));
                    })->when(!empty(request('status')), function ($q) {
                        $q->where('status', request('status'));
                    })->when(!empty(request('financer_id')), function ($q) {
                        $q->where('hyp_financer', request('hyp_financer'));
                    })
                    ->select([
                        'branches.branch_name as branch_name',
                        'salesmans.name as salesman_name',
                        'customer_gender',
                        'customer_name',
                        'customer_relationship',
                        'customer_guardian_name',
                        'customer_address_line',
                        'u_states.state_name',
                        'u_districts.district_name',
                        'u_cities.city_name',
                        'customer_zipcode',
                        'customer_mobile_number',
                        'customer_mobile_number_alt',
                        'customer_email_address',
                        'payment_type',
                        'is_exchange_avaliable',
                        'hyp_financer',
                        'hyp_financer_description',
                        'purchase_visit_date',
                        'purchase_est_date',
                        'bike_brands.name',
                        'bike_models.model_name',
                        'bike_colors.color_name',
                        'ex_showroom_price',
                        'registration_amount',
                        'insurance_amount',
                        'hypothecation_amount',
                        'accessories_amount',
                        'other_charges',
                        'total_amount',
                        'status',
                        'close_note'
                    ]);
                $heading = [
                    'Branch',
                    'Salsesman',
                    'Customer Gender',
                    'Customer Name',
                    'Customer Relationship',
                    'Customer Guardian Name',
                    'Customer Address Line',
                    'Customer State',
                    'Customer District',
                    'Customer City',
                    'Customer Zipcode',
                    'Customer Mobile Number',
                    'Customer Mobile Number Alt',
                    'Customer Email Address',
                    'Payment Type',
                    'is Exchange Avaliable',
                    'hyp Financer',
                    'hyp Financer Description',
                    'Purchase Visit Date',
                    'Purchase EST Date',
                    'Bike Brand Name',
                    'Bike Model Name',
                    'Bike Color Name',
                    'Ex Showroom Price',
                    'Registration Amount',
                    'Insurance Amount',
                    'Hypothecation Amount',
                    'Accessories Amount',
                    'Other Charges',
                    'Total Amount',
                    'Status',
                    'Close Note'
                ];
                break;
            case 'sales_register':
                $query = Sale::leftJoin('branches', 'branches.id', '=', 'sales.branch_id')
                    ->leftJoin('purchases',  'purchases.id',  '=',  'sales.purchase_id')
                    ->leftJoin('quotations',  'quotations.id',  '=',  'sales.quotation_id')
                    ->leftJoin('salesmans',  'salesmans.id',  '=',  'sales.salesman_id')
                    ->leftJoin('u_states',    'u_states.id',    '=',  'sales.customer_state')
                    ->leftJoin('u_districts', 'u_districts.id', '=',  'sales.customer_district')
                    ->leftJoin('u_cities',    'u_cities.id',    '=',  'sales.customer_city')
                    ->whereHas('purchases', function ($q) {
                        $q->when(!empty(request('brand_id')), function ($q) {
                            $q->where('bike_brand', request('brand_id'));
                        })->when(!empty(request('model_id')), function ($q) {
                            $q->where('bike_model', request('model_id'));
                        })->when(!empty(request('financer_id')), function ($q) {
                            $q->where('hyp_financer', request('hyp_financer'));
                        });
                    })->when(!empty(request('payment_type')), function ($q) {
                        $q->where('payment_type', request('payment_type'));
                    })->when(!empty(request('salesman_id')), function ($q) {
                        $q->where('salesman_id', request('salesman_id'));
                    })
                    ->select([
                        'branches.branch_name as branch_name',
                        'purchases.dc_number as dc_number',
                        'quotations.uuid as quotation_uuid',
                        'salesmans.name as salesman_name',
                        'sales.customer_address_line',
                        'u_states.state_name',
                        'u_districts.district_name',
                        'u_cities.city_name',
                        'sales.customer_zipcode',
                        'sales.customer_mobile_number',
                        'sales.customer_mobile_number_alt',
                        'sales.customer_email_address',
                        'sales.witness_person_name',
                        'sales.witness_person_phone',
                        'sales.payment_type',
                        'sales.is_exchange_avaliable',
                        'sales.hyp_financer',
                        'sales.hyp_financer_description',
                        'sales.ex_showroom_price',
                        'sales.registration_amount',
                        'sales.insurance_amount',
                        'sales.hypothecation_amount',
                        'sales.accessories_amount',
                        'sales.other_charges',
                        'sales.total_amount',
                        'sales.status',
                    ]);
                $heading = [
                    'branch name',
                    'dc number',
                    'quotation uuid',
                    'salesman name',
                    'customer address line',
                    'state name',
                    'district name',
                    'city name',
                    'customer zipcode',
                    'customer mobile number',
                    'customer mobile number alt',
                    'customer email address',
                    'witness person name',
                    'witness person phone',
                    'payment type',
                    'is exchange avaliable',
                    'hyp financer',
                    'hyp financer description',
                    'ex showroom price',
                    'registration amount',
                    'insurance amount',
                    'hypothecation amount',
                    'accessories amount',
                    'other charges',
                    'total amount',
                    'status',
                    'customer name'
                ];
                break;
            case 'brokers_agents':
                $query = Purchase::join('branches', 'purchases.bike_branch', '=', 'branches.id')->join('bike_dealers', 'purchases.bike_dealer', '=', 'bike_dealers.id')->join('bike_brands', 'purchases.bike_brand', '=', 'bike_brands.id')->join('bike_models', 'purchases.bike_model', '=', 'bike_models.id')->join('bike_colors', 'purchases.bike_model_color', '=', 'bike_colors.id')->join('battery_brands', 'purchases.battery_brand_id', '=', 'battery_brands.id')->join('tyre_brands', 'purchases.tyre_brand_id', '=', 'tyre_brands.id')
                    ->when(!empty(request('model_id')), function ($q) {
                        $q->where('purchases.bike_model', request('model_id'));
                    })
                    ->when(!empty(request('brand_id')), function ($q) {
                        $q->where('purchases.bike_brand', request('brand_id'));
                    })
                    ->when(!empty(request('type')), function ($q) {
                        if (request('type') == 'sold') {
                            $q->has('sale');
                        }
                        if (request('type') == 'unsold') {
                            $q->doesntHave('sale');
                        }
                    })
                    // ->has('purchase_transfer_latest')
                    ->select('branches.branch_name as branch', 'bike_dealers.dealer_code as dealer_code', 'bike_dealers.company_name as dealer_company_name', 'bike_brands.name as brand', 'bike_models.model_name as model', 'bike_colors.color_name as color', 'battery_brands.name as battery_brand', 'tyre_brands.name as tyre_brand', 'tyre_front_number', 'tyre_rear_number', 'battery_number', 'bike_type', 'bike_fuel_type', 'break_type', 'wheel_type', 'dc_number', 'dc_date', 'vin_number', 'vin_physical_status', 'variant', 'sku', 'sku_description', 'hsn_number', 'engine_number', 'key_number', 'service_book_number', 'gst_rate', 'gst_rate_percent', 'pre_gst_amount', 'gst_amount', 'ex_showroom_price', 'discount_price', 'grand_total', 'bike_description', 'transfer_status');
                $heading = ['Branch', 'Dealer Code', 'Dealer Company Name', 'Brand', 'Model', 'Color', 'Battery Brand', 'Tyre Brand', 'Tyre front Number', 'Tyre rear Number', 'Battery Number', 'Bike Type', 'Bike fuel Type', 'Break Type', 'Wheel Type', 'Dc Number', 'Dc Date', 'Vin Number', 'Vin Physical Status', 'Variant', 'SKU', 'SKU Description', 'HSN Number', 'Engine Number', 'key Number', 'Service book Number', 'Gst Rate', 'Gst Rate Percent', 'Pre Gst Amount', 'Gst Amount', 'Ex Showroom Price', 'Discount Price', 'Grand Total', 'Bike Description', 'Transfer Status'];
                break;
            default:
                //
                break;
        }
        config(['query' => $query, 'heading' => $heading]);
        return $query;
    }
}
