<?php

namespace App\Traits;

use App\Models\BankFinancer;
use App\Models\Purchase;
use App\Models\PurchaseTransfer;
use App\Models\Quotation;
use App\Models\RtoRegistration;
use App\Models\Sale;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

trait DownloadReportHelper
{

    public static function getReport()
    {
        try {
            $query = self::setQuery();
            $query = self::setDate();
            $query = $query->get()->toArray();
            // echo '<pre>';print_r($query);die;
            $heading = config('heading');
            $heading = array_map('strtoupper', $heading);
            if (count($heading)) {
                array_unshift($query, $heading);
            }
            $result = [];
            //SWITCH CASE IF NEEDED SOME CUSTOMIZATION INTO RESULT ELSE DEFAULT CASE RUN
            switch (request('type')) {
                case 'customer_wise_payment':
                    if ($query && count($query)) {
                        $result = self::customer_wise_payment_data_formate($query);
                    }
                    break;
                case 'financer_wise_payment':
                    if ($query && count($query)) {
                        $result = self::financer_wise_payment_data_formate($query);
                    }
                    break;
                default:
                    if ($query && count($query)) {
                        foreach ($query as $value) {
                            $result[] = (array)$value;
                        }
                    }
                    break;
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
                    $start_date = request('start_date') ?? date('Y-m-d');
                    break;
                default:
                    $end_date = null;
                    $start_date = null;
                    break;
            }
            // echo config('date_filter').'  --  '.$start_date.' -- '.$end_date;
            if ($end_date && $start_date && config('date_filter')) {
                $query = $query->whereDate(config('date_filter'), '>=', $start_date)->whereDate(config('date_filter'), '<=', $end_date);
            }
        }
        config(['query' => $query]);
        return $query;
    }

    public static function setQuery()
    {
        // die('hellloooo');
        $query = null;
        $heading = [];
        switch (request('type')) {
            case 'vehicle_purchase_register':
                $query = Purchase::leftJoin('branches', 'purchases.bike_branch', '=', 'branches.id')
                    ->leftJoin('bike_dealers', 'purchases.bike_dealer', '=', 'bike_dealers.id')
                    ->leftJoin('bike_brands', 'purchases.bike_brand', '=', 'bike_brands.id')
                    ->leftJoin('bike_models', 'purchases.bike_model', '=', 'bike_models.id')
                    ->leftJoin('bike_model_variants', 'purchases.bike_model_variant', '=', 'bike_model_variants.id')
                    ->leftJoin('bike_colors', 'purchases.bike_model_color', '=', 'bike_colors.id')
                    ->leftJoin('battery_brands', 'purchases.battery_brand_id', '=', 'battery_brands.id')
                    ->leftJoin('tyre_brands', 'purchases.tyre_brand_id', '=', 'tyre_brands.id')
                    ->leftJoin('purchase_transfers', 'purchases.id', '=', 'purchase_transfers.purchase_id')
                    ->leftJoin('brokers', 'brokers.id', '=', 'purchase_transfers.broker_id')
                    ->when(!empty(request('broker_id')), function ($q) {
                        $q->where('purchase_transfers.broker_id', request('broker_id'));
                    })->when(!empty(request('model_id')), function ($q) {
                        $q->where('purchases.bike_model', request('model_id'));
                    })->when(!empty(request('brand_id')), function ($q) {
                        $q->where('purchases.bike_brand', request('brand_id'));
                    })
                    ->select(DB::raw("CASE purchases.status WHEN '1' THEN 'UNSOLD' ELSE 'SOLD' END AS status"), 'brokers.name as broker_name', 'branches.branch_name as branch', 'bike_dealers.company_name as dealer_company_name', 'bike_brands.name as brand', 'bike_models.model_name as model', 'bike_model_variants.variant_name', 'bike_colors.color_name as color', 'bike_type', 'bike_fuel_type', 'vin_number', 'vin_physical_status', 'hsn_number', 'engine_number', 'variant', 'sku', 'sku_description', 'key_number', 'service_book_number', 'battery_brands.name as battery_brand', 'battery_number', 'tyre_brands.name as tyre_brand', 'tyre_front_number', 'tyre_rear_number', 'dc_number', 'dc_date', 'gst_rate', 'pre_gst_amount', 'discount_price', 'gst_amount', 'ex_showroom_price', 'grand_total', 'bike_description');
                $heading = ['STOCK STATUS', 'BROKER NAME', 'BRANCH NAME', 'DEALER NAME', 'BRAND NAME', 'MODEL NAME', 'MODEL VARIANT', 'VARIANT COLOR', 'VEHICLE TYPE', 'FUEL TYPE', 'VIN NUMBER(CHASIS NUMBER) ', 'VIN PHYSICAL STATUS', 'HSN NUMBER', 'ENGINE NUMBER', 'VARIANT CODE', 'SKU CODE', 'SKU DESCRIPTION', 'KEY NUMBER', 'SERVICE BOOK NUMBER', 'BATTERY BRAND', 'BATTERY NUMBER', 'TYRE BRAND', 'TYRE FRONT NUMBER', 'TYRE REAR NUMBER', 'DC NUMBER', 'DC DATE', 'GST RATE', 'ACTUAL PRICE(PRE GST)', 'DISCOUNT AMOUNT(-)', 'GST AMOUNT', 'EX SHOWROOM PRICE(+GST)', 'GRAND TOTAL', 'VEHICLE DESCRIPTION'];
                config(['date_filter' => 'purchases.created_at']);
                break;
            case 'pending_purchase_invoice':
                $query = Purchase::leftJoin('branches', 'purchases.bike_branch', '=', 'branches.id')
                    ->leftJoin('bike_dealers', 'purchases.bike_dealer', '=', 'bike_dealers.id')
                    ->leftJoin('bike_brands', 'purchases.bike_brand', '=', 'bike_brands.id')
                    ->leftJoin('bike_models', 'purchases.bike_model', '=', 'bike_models.id')
                    ->leftJoin('bike_model_variants', 'purchases.bike_model_variant', '=', 'bike_model_variants.id')
                    ->leftJoin('bike_colors', 'purchases.bike_model_color', '=', 'bike_colors.id')
                    ->leftJoin('battery_brands', 'purchases.battery_brand_id', '=', 'battery_brands.id')
                    ->leftJoin('tyre_brands', 'purchases.tyre_brand_id', '=', 'tyre_brands.id')
                    ->leftJoin('purchase_transfers', 'purchases.id', '=', 'purchase_transfers.purchase_id')
                    ->leftJoin('brokers', 'brokers.id', '=', 'purchase_transfers.broker_id')
                    ->when(!empty(request('broker_id')), function ($q) {
                        $q->where('purchase_transfers.broker_id', request('broker_id'));
                    })->when(!empty(request('model_id')), function ($q) {
                        $q->where('purchases.bike_model', request('model_id'));
                    })->when(!empty(request('brand_id')), function ($q) {
                        $q->where('purchases.bike_brand', request('brand_id'));
                    })
                    ->doesntHave('invoice')
                    ->select(DB::raw("CASE purchases.status WHEN '1' THEN 'UNSOLD' ELSE 'SOLD' END AS status"), 'brokers.name as broker_name', 'branches.branch_name as branch', 'bike_dealers.company_name as dealer_company_name', 'bike_brands.name as brand', 'bike_models.model_name as model', 'bike_model_variants.variant_name', 'bike_colors.color_name as color', 'bike_type', 'bike_fuel_type', 'vin_number', 'vin_physical_status', 'hsn_number', 'engine_number', 'variant', 'sku', 'sku_description', 'key_number', 'service_book_number', 'battery_brands.name as battery_brand', 'battery_number', 'tyre_brands.name as tyre_brand', 'tyre_front_number', 'tyre_rear_number', 'dc_number', 'dc_date', 'gst_rate', 'pre_gst_amount', 'discount_price', 'gst_amount', 'ex_showroom_price', 'grand_total', 'bike_description');
                $heading = ['STOCK STATUS', 'BROKER NAME', 'BRANCH NAME', 'DEALER NAME', 'BRAND NAME', 'MODEL NAME', 'MODEL VARIANT', 'VARIANT COLOR', 'VEHICLE TYPE', 'FUEL TYPE', 'VIN NUMBER(CHASIS NUMBER) ', 'VIN PHYSICAL STATUS', 'HSN NUMBER', 'ENGINE NUMBER', 'VARIANT CODE', 'SKU CODE', 'SKU DESCRIPTION', 'KEY NUMBER', 'SERVICE BOOK NUMBER', 'BATTERY BRAND', 'BATTERY NUMBER', 'TYRE BRAND', 'TYRE FRONT NUMBER', 'TYRE REAR NUMBER', 'DC NUMBER', 'DC DATE', 'GST RATE', 'ACTUAL PRICE(PRE GST)', 'DISCOUNT AMOUNT(-)', 'GST AMOUNT', 'EX SHOWROOM PRICE(+GST)', 'GRAND TOTAL', 'VEHICLE DESCRIPTION'];
                config(['date_filter' => 'purchases.created_at']);
                break;
            case 'vehicle_stock_inventory':
                $query = Purchase::leftJoin('branches', 'purchases.bike_branch', '=', 'branches.id')
                    ->leftJoin('bike_dealers', 'purchases.bike_dealer', '=', 'bike_dealers.id')
                    ->leftJoin('bike_brands', 'purchases.bike_brand', '=', 'bike_brands.id')
                    ->leftJoin('bike_models', 'purchases.bike_model', '=', 'bike_models.id')
                    ->leftJoin('bike_model_variants', 'purchases.bike_model_variant', '=', 'bike_model_variants.id')
                    ->leftJoin('bike_colors', 'purchases.bike_model_color', '=', 'bike_colors.id')
                    ->leftJoin('battery_brands', 'purchases.battery_brand_id', '=', 'battery_brands.id')
                    ->leftJoin('tyre_brands', 'purchases.tyre_brand_id', '=', 'tyre_brands.id')
                    ->leftJoin('purchase_transfers', 'purchases.id', '=', 'purchase_transfers.purchase_id')
                    ->leftJoin('brokers', 'brokers.id', '=', 'purchase_transfers.broker_id')
                    ->when(!empty(request('broker_id')), function ($q) {
                        $q->where('purchase_transfers.broker_id', request('broker_id'));
                    })->when(!empty(request('model_id')), function ($q) {
                        $q->where('purchases.bike_model', request('model_id'));
                    })->when(!empty(request('brand_id')), function ($q) {
                        $q->where('purchases.bike_brand', request('brand_id'));
                    })->when(!empty(request('status')), function ($q) {
                        $q->where('purchases.status', ((request('status') == 'unsold') ? 1 : 2));
                    })->doesntHave('invoice')
                    ->select(DB::raw("CASE purchases.status WHEN '1' THEN 'UNSOLD' ELSE 'SOLD' END AS status"), 'brokers.name as broker_name', 'branches.branch_name as branch', 'bike_dealers.company_name as dealer_company_name', 'bike_brands.name as brand', 'bike_models.model_name as model', 'bike_model_variants.variant_name', 'bike_colors.color_name as color', 'bike_type', 'bike_fuel_type', 'vin_number', 'vin_physical_status', 'hsn_number', 'engine_number', 'variant', 'sku', 'sku_description', 'key_number', 'service_book_number', 'battery_brands.name as battery_brand', 'battery_number', 'tyre_brands.name as tyre_brand', 'tyre_front_number', 'tyre_rear_number', 'dc_number', 'dc_date', 'gst_rate', 'pre_gst_amount', 'discount_price', 'gst_amount', 'ex_showroom_price', 'grand_total', 'bike_description');
                $heading = ['STOCK STATUS', 'BROKER NAME', 'BRANCH NAME', 'DEALER NAME', 'BRAND NAME', 'MODEL NAME', 'MODEL VARIANT', 'VARIANT COLOR', 'VEHICLE TYPE', 'FUEL TYPE', 'VIN NUMBER(CHASIS NUMBER) ', 'VIN PHYSICAL STATUS', 'HSN NUMBER', 'ENGINE NUMBER', 'VARIANT CODE', 'SKU CODE', 'SKU DESCRIPTION', 'KEY NUMBER', 'SERVICE BOOK NUMBER', 'BATTERY BRAND', 'BATTERY NUMBER', 'TYRE BRAND', 'TYRE FRONT NUMBER', 'TYRE REAR NUMBER', 'DC NUMBER', 'DC DATE', 'GST RATE', 'ACTUAL PRICE(PRE GST)', 'DISCOUNT AMOUNT(-)', 'GST AMOUNT', 'EX SHOWROOM PRICE(+GST)', 'GRAND TOTAL', 'VEHICLE DESCRIPTION'];

                break;
            case 'brokers_agents':
                $query = Purchase::leftJoin('branches', 'purchases.bike_branch', '=', 'branches.id')
                    ->leftJoin('bike_dealers', 'purchases.bike_dealer', '=', 'bike_dealers.id')
                    ->leftJoin('bike_brands', 'purchases.bike_brand', '=', 'bike_brands.id')
                    ->leftJoin('bike_models', 'purchases.bike_model', '=', 'bike_models.id')
                    ->leftJoin('bike_model_variants', 'purchases.bike_model_variant', '=', 'bike_model_variants.id')
                    ->leftJoin('bike_colors', 'purchases.bike_model_color', '=', 'bike_colors.id')
                    ->leftJoin('battery_brands', 'purchases.battery_brand_id', '=', 'battery_brands.id')
                    ->leftJoin('tyre_brands', 'purchases.tyre_brand_id', '=', 'tyre_brands.id')
                    ->leftJoin('purchase_transfers', 'purchases.id', '=', 'purchase_transfers.purchase_id')
                    ->leftJoin('brokers', 'brokers.id', '=', 'purchase_transfers.broker_id')
                    ->when(!empty(request('broker_id')), function ($q) {
                        $q->where('purchase_transfers.broker_id', request('broker_id'));
                    })->when(!empty(request('model_id')), function ($q) {
                        $q->where('purchases.bike_model', request('model_id'));
                    })->when(!empty(request('brand_id')), function ($q) {
                        $q->where('purchases.bike_brand', request('brand_id'));
                    })
                    ->when(!empty(request('sale_type')), function ($q) {
                        $q->where('purchases.status', ((request('sale_type') == 'unsold') ? 1 : 2));
                    })
                    ->select('brokers.name as broker_name', 'branches.branch_name as branch', 'bike_dealers.company_name as dealer_company_name', 'bike_brands.name as brand', 'bike_models.model_name as model', 'bike_model_variants.variant_name', 'bike_colors.color_name as color', 'bike_type', 'bike_fuel_type', 'vin_number', 'vin_physical_status', 'hsn_number', 'engine_number', 'variant', 'sku', 'sku_description', 'key_number', 'service_book_number', 'battery_brands.name as battery_brand', 'battery_number', 'tyre_brands.name as tyre_brand', 'tyre_front_number', 'tyre_rear_number', 'dc_number', 'dc_date', 'gst_rate', 'pre_gst_amount', 'discount_price', 'gst_amount', 'ex_showroom_price', 'grand_total', 'bike_description');
                $heading = ['BROKER NAME', 'BRANCH NAME', 'DEALER NAME', 'BRAND NAME', 'MODEL NAME', 'MODEL VARIANT', 'VARIANT COLOR', 'VEHICLE TYPE', 'FUEL TYPE', 'VIN NUMBER(CHASIS NUMBER) ', 'VIN PHYSICAL STATUS', 'HSN NUMBER', 'ENGINE NUMBER', 'VARIANT CODE', 'SKU CODE', 'SKU DESCRIPTION', 'KEY NUMBER', 'SERVICE BOOK NUMBER', 'BATTERY BRAND', 'BATTERY NUMBER', 'TYRE BRAND', 'TYRE FRONT NUMBER', 'TYRE REAR NUMBER', 'DC NUMBER', 'DC DATE', 'GST RATE', 'ACTUAL PRICE(PRE GST)', 'DISCOUNT AMOUNT(-)', 'GST AMOUNT', 'EX SHOWROOM PRICE(+GST)', 'GRAND TOTAL', 'VEHICLE DESCRIPTION'];
                config(['date_filter' => 'purchases.created_at']);
                break;
            case 'financers':
                $query = Sale::leftJoin('branches', 'sales.branch_id', '=', 'branches.id')
                    ->leftJoin('purchases', 'purchases.id', '=', 'sales.purchase_id')
                    ->leftJoin('bike_brands', 'purchases.bike_brand', '=', 'bike_brands.id')
                    ->leftJoin('bike_models', 'purchases.bike_model', '=', 'bike_models.id')
                    ->leftJoin('bike_colors', 'purchases.bike_model_color', '=', 'bike_colors.id')
                    ->leftJoin('tyre_brands', 'purchases.tyre_brand_id', '=', 'tyre_brands.id')
                    ->leftJoin('battery_brands', 'purchases.battery_brand_id', '=', 'battery_brands.id')
                    ->leftJoin('purchase_transfers', 'purchases.id', '=', 'purchase_transfers.purchase_id')
                    ->leftJoin('brokers', 'purchase_transfers.broker_id', '=', 'brokers.id')
                    ->leftJoin('bank_financers', 'sales.hyp_financer', '=', 'bank_financers.id')
                    ->leftJoin('u_states', 'sales.customer_state', '=', 'u_states.id')
                    ->leftJoin('u_cities', 'sales.customer_district', '=', 'u_cities.id')
                    ->leftJoin('u_districts', 'sales.customer_city', '=', 'u_districts.id')
                    ->select([
                        'brokers.name as broker_name', 'customer_gender', 'customer_name', 'customer_relationship', 'customer_guardian_name', 'customer_address_line', 'u_states.state_name', 'u_cities.city_name', 'u_districts.district_name', 'customer_zipcode', 'customer_mobile_number', 'customer_mobile_number_alt', 'customer_email_address', 'witness_person_name', 'witness_person_phone', 'branches.branch_name', 'bike_brands.name as brand_name', 'bike_models.model_name', 'bike_colors.color_name', 'purchases.bike_type', 'purchases.bike_fuel_type', 'purchases.vin_number', 'purchases.vin_physical_status', 'purchases.variant', 'purchases.sku', 'purchases.sku_description', 'purchases.hsn_number', 'purchases.engine_number', 'purchases.key_number', 'purchases.service_book_number', 'tyre_brands.name as tyre_brands_name', 'purchases.tyre_front_number', 'purchases.tyre_rear_number', 'battery_brands.name as battery_brands_name', 'purchases.battery_number', 'purchases.bike_description', 'sales.is_exchange_avaliable', 'sales.is_exchange_avaliable', 'sales.payment_type', 'bank_financers.bank_name', 'sales.hyp_financer_description', 'sales.ex_showroom_price', 'sales.registration_amount', 'sales.insurance_amount', 'sales.hypothecation_amount', 'sales.accessories_amount', 'sales.other_charges', 'sales.total_amount',
                    ])->when(!empty(request('broker_id')), function ($q) {
                        $q->where('brokers.id', request('broker_id'));
                    })->when(!empty(request('transfer_status')), function ($q) {
                        $q->where('purchases.transfer_status', request('transfer_status'));
                    })->when(!empty(request('branch_id')), function ($q) {
                        $q->where('branches.id', request('branch_id'));
                    })->when(!empty(request('financer_id')), function ($q) {
                        $q->where('bank_financers.id', request('financer_id'));
                    })->when(!empty(request('finance_type')), function ($q) {
                        $q->where('payment_type', request('finance_type'));
                    })->when(!empty(request('status')), function ($q) {
                        $q->where('status', request('status'));
                    });
                $heading = [
                    'Broker Name', 'Customer Gender', 'Customer Name', 'Customer Relationship', 'Customer Guardian Name', 'Customer Address Line', 'State Name', 'City Name', 'District Name', 'Customer Zipcode', 'Customer Mobile Number', 'Customer Mobile Number Alt', 'Customer Email Address', 'Witness Person Name', 'Witness Person Phone', 'Branch Name', 'Brand Name', 'Model Name', 'Color Name', 'Bike Type', 'Bike Fuel Type', 'Vin Number', 'Vin Physical Status', 'Variant', 'Sku', 'Sku Description', 'Hsn Number', 'Engine Number', 'Key Number', 'Service Book Number', 'Tyre Brands Name', 'Tyre Front Number', 'Tyre Rear Number', 'Battery Brands Name', 'Battery Number', 'Bike Description', 'Is Exchange Avaliable', 'Payment Type', 'Bank Name', 'Hyp Financer Description', 'Ex Showroom Price', 'Registration Amount', 'Insurance Amount', 'Hypothecation Amount', 'Accessories Amount', 'Other Charges', 'Total Amount', 'Cust Name',
                ];
                config(['date_filter' => 'sales.created_at']);
                break;
            case 'rto':
                $query = Sale::select(
                    'branches.branch_name as bike_branch_name','bike_brands.name as bike_brand_name','bike_models.model_name as bike_model_name',
                    'rto_agents.agent_name','rto_registration.contact_name','rto_registration.contact_mobile_number','rto_registration.contact_address_line','u_cities.city_name', 'u_districts.district_name',
                    'u_states.state_name','rto_registration.contact_zipcode', 'rto_registration.chasis_number', 'rto_registration.engine_number', 'rto_registration.broker_name', 'rto_registration.sku',
                    'rto_registration.financer_name', 'gst_rto_rates.gst_rate','rto_registration.ex_showroom_amount','rto_registration.tax_amount','rto_registration.hyp_amount','rto_registration.tr_amount',
                    'rto_registration.fees','rto_registration.total_amount','rto_registration.rc_number', 'rto_registration.rc_status','sales.sale_date','rto_registration.submit_date','rto_registration.recieved_date',
                    'rto_registration.customer_given_name','rto_registration.customer_given_date','rto_registration.customer_given_note','rto_registration.remark','sale_payment_accounts.status as sale_payment_account_status'
                    )
                    ->join('rto_registration', 'rto_registration.sale_id', '=', 'sales.id')
                    ->leftJoin('purchases', 'purchases.id', '=', 'sales.purchase_id')
                    ->leftJoin('branches', 'branches.id', '=', 'purchases.bike_branch')
                    ->leftJoin('bike_brands', 'bike_brands.id', '=', 'purchases.bike_brand')
                    ->leftJoin('bike_models', 'bike_models.id', '=', 'purchases.bike_model')
                    ->leftJoin('sale_payment_accounts', 'sale_payment_accounts.sale_id', '=', 'sales.id')
                    ->leftJoin('rto_agents', 'rto_agents.id', '=', 'rto_registration.rto_agent_id')
                    ->leftJoin('u_cities', 'u_cities.id', '=', 'rto_registration.contact_city_id')
                    ->leftJoin('u_states', 'u_states.id', '=', 'rto_registration.contact_state_id')
                    ->leftJoin('u_districts', 'u_districts.id', '=', 'rto_registration.contact_district_id')
                    ->leftJoin('gst_rto_rates', 'gst_rto_rates.id', '=', 'rto_registration.gst_rto_rate_id')
                    ->when(!empty(request('brand_id')), function ($q) {
                        $q->where('purchases.bike_brand',request('brand_id'));
                    })
                    ->when(!empty(request('model_id')), function ($q) {
                        $q->where('purchases.bike_model',request('model_id'));
                    })
                    ->when(!empty(request('agent_id')), function ($q) {
                        $q->where('rto_agent_id', request('agent_id'));
                    })
                    ->when(!empty(request('sent_to_rto')), function ($q) {
                        if( (request('sent_to_rto') == 'yes')) {
                            $q->where(function($q1){
                                $q1->whereNotNull('rto_registration.submit_date')->orWhere('rto_registration.submit_date','!=','');
                            });
                        } else if( (request('sent_to_rto') == 'no')){
                            $q->where(function($q1){
                                $q1->whereNull('rto_registration.submit_date')->orWhere('rto_registration.submit_date','=','');
                            });
                        }
                    })
                    ->when(!empty(request('pending_registration_number')), function ($q) {
                        if( (request('pending_registration_number') == 'no')) {
                            $q->where(function($q1){
                                $q1->whereNotNull('rto_registration.rc_number')->orWhere('rto_registration.rc_number','!=','');
                            });
                        } else if( (request('pending_registration_number') == 'yes')){
                            $q->where(function($q1){
                                $q1->whereNull('rto_registration.rc_number')->orWhere('rto_registration.rc_number','=','');
                            });
                        }
                    })
                    ->when(!empty(request('rc_status')), function ($q) {
                        $status = (request('rc_status') == 'yes')?1:0;
                        $q->whereRcStatus($status);
                    })
                    ->when(!empty(request('payment_outstanding')), function ($q) {
                        $status = (request('payment_outstanding') == 'close')?1:0;
                        $q->where('sale_payment_accounts.status', $status);
                    });
                $heading = ['BRANCH NAME','BRAND NAME','MODEL NAME','AGENT NAME','CONTACT NAME', 'CONTACT MOBILE NUMBER', 'CONTACT ADDRESS LINE', 'CONTACT CITY', 'CONTACT DISTRICT', 'CONTACT STATE',
                'CONTACT ZIPCODE', 'CHASSIS NUMBER', 'ENGINE NUMBER', 'BROKER NAME', 'SKU','FINANCER NAME', 'GST RATE (TAX RATE)', 'EX SHOWROOM AMOUNT', 'TAX AMOUNT',
                'HYP AMOUNT', 'TR AMOUNT', 'FEES', 'TOTAL AMOUNT', 'RC NUMBER', 'RC STATUS', 'SALE DATE','SUBMIT DATE', 'RECIEVED DATE','CUSTOMER GIVEN NAME(WHOM GIVEN)',
                'CUSTOMER GIVEN DATE', 'CUSTOMER GIVEN NOTE(IF ANY)','REMARK','SALES PAYMENT ACCOUNT STATUS','',''];
                break;
            case 'accounts':
                $query = RtoRegistration::select(['contact_name', 'contact_mobile_number', 'contact_address_line', 'u_cities.city_name', 'u_states.state_name', 'u_districts.district_name', 'contact_zipcode', 'sku', 'financer_name', 'gst_rto_rates.gst_rate', 'ex_showroom_amount', 'tax_amount', 'hyp_amount', 'tr_amount', 'fees', 'total_amount', 'remark', 'rc_number', 'rc_status', 'customer_given_name', 'customer_given_date', 'customer_given_note'])
                    ->leftJoin('u_cities', 'u_cities.id', '=', 'rto_registration.contact_city_id')
                    ->leftJoin('u_states', 'u_states.id', '=', 'rto_registration.contact_state_id')
                    ->leftJoin('u_districts', 'u_districts.id', '=', 'rto_registration.contact_district_id')
                    ->leftJoin('gst_rto_rates', 'gst_rto_rates.id', '=', 'rto_registration.gst_rto_rate_id')
                    ->leftJoin('sale_payment_accounts', 'sale_payment_accounts.sale_id', '=', 'rto_registration.sale_id')
                    ->when(!empty(request('rto_status')), function ($q) {
                        $q->whereNotIn('recieved_date', [null, '']);
                    })
                    ->when(!empty(request('sent_to_rto')), function ($q) {
                        $q->whereNotIn('submit_date', [null, '']);
                    })
                    ->when(!empty(request('pending_registration_number')), function ($q) {
                        $q->whereNotIn('rc_number', [null, '']);
                    })
                    ->when(!empty(request('rc_status')), function ($q) {
                        $q->whereRcStatus(request('rc_status'));
                    })
                    ->when(!empty(request('payment_outstanding')), function ($q) {
                        $q->whereNotIn('sale_payment_accounts', [null, '']);
                    });
                $heading = ['CONTACT NAME', 'CONTACT MOBILE NUMBER', 'CONTACT ADDRESS LINE', 'CONTACT STATE', 'CONTACT DISTRICT', 'CONTACT CITY', 'CONTACT ZIPCODE', 'SKU', 'FINANCER NAME', 'GST RATE (TAX RATE)', 'EX SHOWROOM AMOUNT', 'TAX AMOUNT', 'HYP AMOUNT', 'TR AMOUNT', 'FEES', 'TOTAL AMOUNT', 'RTO REGISTRATION REMARK(IF ANY)', 'RC NUMBER', 'RC STATUS', 'SUBMIT DATE', 'RECIEVED DATE', 'CUSTOMER GIVEN NAME(WHOM GIVEN)', 'Customer Given Name', 'CUSTOMER GIVEN DATE', 'CUSTOMER GIVEN NOTE(IF ANY)'];
                break;
            case 'customer_wise_payment':
                $query = Sale::select(
                    // 'sales.*',
                    'sale_date',
                    'customer_address_line as address_line',
                    'customer_zipcode',
                    'customer_mobile_number',
                    'witness_person_name',
                    'witness_person_phone',
                    'hyp_financer',
                    'total_amount',
                    'payment_type',
                    'customer_gender',
                    'customer_name',
                    'customer_relationship',
                    'customer_guardian_name',
                    'branch_id',
                    'u_cities.city_name',
                    'u_states.state_name',
                    'u_districts.district_name',
                    'purchases.id as sale_purchase_id',
                    'purchases.sku_description',
                    'purchases.vin_number',
                    'purchases.engine_number',
                    'purchases.transfer_status',
                    'purchases.invoice_status',
                    'salesmans.name as salesman_name',
                    'bank_financers.bank_name as bank_financer_name',
                    'sa.id as sales_account_id',
                    'sa.sales_total_amount',
                    'sa.down_payment',
                    'sa.due_payment_source',
                    'sa.status',
                    'sa.cash_outstaning_balance',
                    'sa.cash_paid_balance',
                    'sa.cash_status',
                    'sa.bank_finance_outstaning_balance',
                    'sa.bank_finance_paid_balance',
                    'sa.bank_finance_status',
                    'sa.bank_finance_amount',
                    'sa.personal_finance_outstaning_balance',
                    'sa.personal_finance_paid_balance',
                    'sa.personal_finance_status',
                    'sa.personal_finance_amount',
                    'branches.branch_name as sale_branch_name',
                )
                    ->leftJoin('branches', 'branches.id', '=', 'sales.branch_id')
                    ->leftJoin('u_cities', 'u_cities.id', '=', 'sales.customer_city')
                    ->leftJoin('u_states', 'u_states.id', '=', 'sales.customer_state')
                    ->leftJoin('u_districts', 'u_districts.id', '=', 'sales.customer_district')
                    ->leftJoin('purchases', 'purchases.id', '=', 'sales.purchase_id')
                    ->leftJoin('salesmans', 'salesmans.id', '=', 'sales.salesman_id')
                    ->leftJoin('sale_payment_accounts as sa', 'sa.id', '=', 'sales.sp_account_id')
                    ->leftJoin('bank_financers', 'bank_financers.id', '=', 'sa.financier_id')
                    //->where('sales.id',"256")
                    ->when(!empty(request('customer_account_status')), function ($q) {
                        $status = (request('customer_account_status') == 'due') ? 0 : 1;
                        $q->where('sa.status', $status);
                    })
                    ->when(!empty(request('branch_id')), function ($q) {
                        $q->where('sales.branch_id', request('branch_id'));
                    });
                $heading = [
                    'BRANCH NAME', 'SALES DATE', 'FULL NAME', 'ADDRESS', 'MOBILE NUMBER', 'REFERENCE DETAIL', 'MODEL SKU DETAIL', 'CHASIS NUMBER|ENGINE NUMBER', 'BROKER NAME',
                    'SALESMAN NAME', 'SALES PRICE', 'PAYMENT TYPE', 'FINANCER NAME', 'DOWN PAYMENT', 'TOTAL CASH PAID', 'TOTAL CASH DUE', 'CASH STATUS', 'TOTAL BANK FINANCE AMOUNT',
                    'TOTAL BANK FINANCE PAID', 'TOTAL BANK FINANCE DUE', 'BANK FINANCE STATUS', 'TOTAL PERSONAL FINANCE AMOUNT', 'TOTAL PERSONAL FINANCE PAID', 'TOTAL PERSONAL FINANCE DUE',
                    'PERSONAL FINANCE STATUS', 'TOTAL PAID', 'TOTAL DUE', 'OVERALL STATUS'
                ];
                config(['date_filter' => 'sales.sale_date']);
                break;
            case 'financer_wise_payment':
                $query = Sale::select(
                    'sale_date',
                    'customer_address_line as address_line',
                    'customer_zipcode',
                    'customer_mobile_number',
                    'witness_person_name',
                    'witness_person_phone',
                    'hyp_financer',
                    'total_amount',
                    'payment_type',
                    'customer_gender',
                    'customer_name',
                    'customer_relationship',
                    'customer_guardian_name',
                    'branch_id',
                    'u_cities.city_name',
                    'u_states.state_name',
                    'u_districts.district_name',
                    'purchases.id as sale_purchase_id',
                    'purchases.sku_description',
                    'purchases.vin_number',
                    'purchases.engine_number',
                    'purchases.transfer_status',
                    'purchases.invoice_status',
                    'salesmans.name as salesman_name',
                    'bank_financers.bank_name as bank_financer_name',
                    'sa.id as sales_account_id',
                    'sa.sales_total_amount',
                    'sa.down_payment',
                    'sa.due_payment_source',
                    'sa.status',
                    'sa.cash_outstaning_balance',
                    'sa.cash_paid_balance',
                    'sa.cash_status',
                    'sa.bank_finance_outstaning_balance',
                    'sa.bank_finance_paid_balance',
                    'sa.bank_finance_status',
                    'sa.bank_finance_amount',
                    'sa.personal_finance_outstaning_balance',
                    'sa.personal_finance_paid_balance',
                    'sa.personal_finance_status',
                    'sa.personal_finance_amount',
                    'branches.branch_name as sale_branch_name',
                )
                    ->leftJoin('branches', 'branches.id', '=', 'sales.branch_id')
                    ->leftJoin('u_cities', 'u_cities.id', '=', 'sales.customer_city')
                    ->leftJoin('u_states', 'u_states.id', '=', 'sales.customer_state')
                    ->leftJoin('u_districts', 'u_districts.id', '=', 'sales.customer_district')
                    ->leftJoin('purchases', 'purchases.id', '=', 'sales.purchase_id')
                    ->leftJoin('salesmans', 'salesmans.id', '=', 'sales.salesman_id')
                    ->leftJoin('sale_payment_accounts as sa', 'sa.id', '=', 'sales.sp_account_id')
                    ->leftJoin('bank_financers', 'bank_financers.id', '=', 'sa.financier_id')
                    //->where('sales.id',"256")
                    ->where('sa.financier_id',">",0) // CHECK IF FINANCER IS EXISTS
                    ->when(!empty(request('customer_account_status')), function ($q) {
                        $q->where(function($q){
                            $status = (request('customer_account_status') == 'due') ? 0 : 1;
                            if(intval(request('financer_id')) > 0) {
                                $finance = BankFinancer::find(request('financer_id'));
                                if($finance && $finance->financer_type == '1') {
                                    $q->where('sa.bank_finance_status', $status);
                                } else if($finance && $finance->financer_type == '2') {
                                    $q->where('sa.personal_finance_status', $status);
                                } else {
                                    $q->where('sa.bank_finance_status', $status)->orWhere('sa.personal_finance_status', $status);
                                }
                            } else {
                                $q->where('sa.bank_finance_status', $status)->orWhere('sa.personal_finance_status', $status);
                            }
                        });
                    })
                    ->when(!empty(request('branch_id')), function ($q) {
                        $q->where('sales.branch_id', request('branch_id'));
                    })
                    ->when(!empty(request('financer_id')), function ($q) {
                        $q->where('sa.financier_id', request('financer_id'));
                    });
                $heading = [
                    'BRANCH NAME', 'SALES DATE', 'FULL NAME', 'ADDRESS', 'MOBILE NUMBER', 'REFERENCE DETAIL', 'MODEL SKU DETAIL', 'CHASIS NUMBER|ENGINE NUMBER', 'BROKER NAME',
                    'SALESMAN NAME', 'SALES PRICE', 'PAYMENT TYPE', 'FINANCER NAME', 'DOWN PAYMENT', 'TOTAL CASH PAID', 'TOTAL CASH DUE', 'CASH STATUS', 'TOTAL BANK FINANCE AMOUNT',
                    'TOTAL BANK FINANCE PAID', 'TOTAL BANK FINANCE DUE', 'BANK FINANCE STATUS', 'TOTAL PERSONAL FINANCE AMOUNT', 'TOTAL PERSONAL FINANCE PAID', 'TOTAL PERSONAL FINANCE DUE',
                    'PERSONAL FINANCE STATUS', 'TOTAL PAID', 'TOTAL DUE', 'OVERALL STATUS'
                ];
                config(['date_filter' => 'sales.sale_date']);
                break;
            case 'quotation_list':
                $query = Quotation::join('branches', 'branches.id', '=', 'quotations.branch_id')
                    ->leftJoin('salesmans',  'salesmans.id',  '=',  'quotations.salesman_id')
                    ->leftJoin('u_states',    'u_states.id',    '=',  'quotations.customer_state')
                    ->leftJoin('u_districts', 'u_districts.id', '=',  'quotations.customer_district')
                    ->leftJoin('u_cities',    'u_cities.id',    '=',  'quotations.customer_city')
                    ->leftJoin('bike_brands', 'bike_brands.id', '=',  'quotations.bike_brand')
                    ->leftJoin('bike_models', 'bike_models.id', '=',  'quotations.bike_color')
                    ->leftJoin('bike_colors', 'bike_colors.id', '=',  'quotations.bike_model')
                    ->leftJoin('bank_financers',  'bank_financers.id',  '=',  'quotations.hyp_financer')
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
                        'branches.branch_name as branch_name', 'salesmans.name as salesman_name', 'customer_gender', 'customer_name', 'customer_relationship', 'customer_guardian_name', 'customer_address_line', 'u_states.state_name', 'u_districts.district_name', 'u_cities.city_name', 'customer_zipcode', 'customer_mobile_number', 'customer_mobile_number_alt', 'customer_email_address', 'payment_type', 'is_exchange_avaliable', 'bank_financers.bank_name as hyp_financer', 'hyp_financer_description', 'purchase_visit_date', 'purchase_est_date', 'bike_brands.name', 'bike_models.model_name', 'bike_colors.color_name', 'ex_showroom_price', 'registration_amount', 'insurance_amount', 'hypothecation_amount', 'accessories_amount', 'other_charges', 'total_amount', 'status', 'close_note']);
                $heading = ['Branch Name', 'Salsesman Name', 'Customer Gender', 'Customer Name', 'Customer Relationship', 'Customer Guardian Name', 'Customer Address Line', 'Customer State', 'Customer District', 'Customer City', 'Customer Zipcode', 'Customer Mobile Number', 'Customer Mobile Number Alt', 'Customer Email Address', 'Payment Type', 'is Exchange Avaliable', 'hyp Financer', 'hyp Financer Description', 'Purchase Visit Date', 'Purchase EST Date', 'Bike Brand Name', 'Bike Model Name', 'Bike Color Name', 'Ex Showroom Price', 'Registration Amount', 'Insurance Amount', 'Hypothecation Amount', 'Accessories Amount', 'Other Charges', 'Total Amount', 'Status', 'Close Note'];
                config(['date_filter' => 'quotations.created_at']);
                break;
            case 'sales_register':
                $query = Sale::leftJoin('branches', 'branches.id', '=', 'sales.branch_id')
                    ->leftJoin('purchases',  'purchases.id',  '=',  'sales.purchase_id')
                    ->leftJoin('bike_brands', 'bike_brands.id','=','purchases.bike_brand')
                    ->leftJoin('bike_models', 'bike_models.id', '=','purchases.bike_model')
                    ->leftJoin('bike_dealers', 'bike_dealers.id', '=','purchases.bike_dealer')
                    ->leftJoin('purchase_transfers', 'purchase_transfers.purchase_id', '=','purchases.id')
                    ->leftJoin('brokers', 'brokers.id', '=','purchase_transfers.broker_id')
                    ->leftJoin('quotations',  'quotations.id',  '=',  'sales.quotation_id')
                    ->leftJoin('salesmans',  'salesmans.id',  '=',  'sales.salesman_id')
                    ->leftJoin('u_states',    'u_states.id',    '=',  'sales.customer_state')
                    ->leftJoin('u_districts', 'u_districts.id', '=',  'sales.customer_district')
                    ->leftJoin('u_cities',    'u_cities.id',    '=',  'sales.customer_city')
                    ->leftJoin('bank_financers',  'bank_financers.id',  '=',  'sales.hyp_financer')
                    ->leftJoin('name_prefix',  'name_prefix.id',  '=',  'sales.customer_gender')
                    ->leftJoin('relationships',  'relationships.id',  '=',  'sales.customer_relationship')
                    ->leftJoin('payment_types',  'payment_types.id',  '=',  'sales.payment_type')
                    ->whereHas('purchases', function ($q) {
                        $q->when(!empty(request('branch_id')), function ($q) {
                            $q->where('bike_branch', request('branch_id'));
                        })->when(!empty(request('brand_id')), function ($q) {
                            $q->where('bike_brand', request('brand_id'));
                        })->when(!empty(request('model_id')), function ($q) {
                            $q->where('bike_model', request('model_id'));
                        })->when(!empty(request('broker_id')), function ($q) {
                            $q->where('purchase_transfers.broker_id', request('broker_id'))->where('purchase_transfers.status', '0');
                        });
                    })
                    ->when(!empty(request('salesman_id')), function ($q) {
                        $q->where('sales.salesman_id', request('salesman_id'));
                    })
                    ->when(!empty(request('payment_type')), function ($q) {
                        $q->where('sales.payment_type', request('payment_type'));
                    })
                    ->when(!empty(request('financer_id')), function ($q) {
                        $q->where('sales.hyp_financer', request('financer_id'));
                    })
                    ->select(
                        'branches.branch_name','bike_dealers.company_name as bike_dealer_name','brokers.name as broker_name','bike_brands.name as bike_brand_name','bike_models.model_name as bike_model_name','purchases.variant','purchases.sku',
                        'purchases.sku_description','purchases.dc_number','purchases.dc_date','purchases.vin_number as chassis_number','purchases.engine_number','purchases.dc_number',
                        'salesmans.name as salesman_name','name_prefix.name as customer_gender_prefix','sales.customer_name','relationships.name as customer_relationship_name',
                        'sales.customer_guardian_name','sales.customer_address_line as address_line','u_cities.city_name as address_city_name','u_districts.district_name as address_district_name',
                        'u_states.state_name as address_state_name','sales.customer_zipcode as address_customer_zipcode','sales.customer_mobile_number','sales.witness_person_name',
                        'sales.witness_person_phone','sales.witness_person_name','payment_types.name as payment_mode','sales.is_exchange_avaliable','bank_financers.bank_name as bank_financer_name',
                        'sales.hyp_financer_description as bank_finance_description','sales.ex_showroom_price','sales.registration_amount','sales.insurance_amount','sales.hypothecation_amount',
                        'sales.accessories_amount','sales.other_charges','sales.total_amount','sales.status','sales.sale_date'
                    );
                $heading = ['branch name', 'bike dealer name', 'broker name', 'brand name', 'model name', 'varaint name', 'sku code', 'sku description',
                'dc number', 'dc date', 'chassis number', 'engine number', 'salesman name', 'customer gender','customer name','customer relationship','customer guardian name',
                'address line', 'address city', 'address district', 'address state','zipcode','customer mobile number','witness person name','witness person mobile','payment mode',
                'is exchange avaliable','bank financer name','bank finance description','ex showroom price', 'registration amount', 'insurance amount', 'hypothecation amount',
                'accessories amount', 'other charges', 'total amount', 'status', 'sales date','customer name','serial number'];
                config(['date_filter' => 'sales.sale_date']);
                break;
            default:
                //
                break;
        }
        config(['query' => $query, 'heading' => $heading]);
        return $query;
    }

    //Function for formate data - customer_wise_payment_data_formate
    public static function customer_wise_payment_data_formate($customer_payments)
    {
        if (count($customer_payments) > 1) {
            foreach ($customer_payments as  $k => $cpay) {
                if ($k == 0) {
                    continue;
                }
                // echo '<pre>';print_r($cpay);
                $total_due  = ($cpay['bank_finance_outstaning_balance'] + $cpay['cash_outstaning_balance'] + $cpay['personal_finance_outstaning_balance']);
                $total_paid = ($cpay['cash_paid_balance'] + $cpay['bank_finance_paid_balance'] + $cpay['personal_finance_paid_balance']);

                $BROKER_NAME = "";
                if ($cpay['transfer_status'] == '1') {
                    $transfer_data = PurchaseTransfer::where(['purchase_id' => $cpay['sale_purchase_id'], 'status' => '0'])
                        ->select('id', 'purchase_id', 'broker_id', 'status')->with('brokr:id,name')->has('brokr')->first();
                    if ($transfer_data) {
                        $transfer_data = $transfer_data->toArray();
                        $BROKER_NAME = isset($transfer_data['brokr']['name']) ? strtoupper($transfer_data['brokr']['name']) : "";
                    }
                }
                $customer_payments[$k] = array(
                    'BRANCH NAME'                   => $cpay['sale_branch_name'],
                    'SALES DATE'                    => $cpay['sale_date'],
                    'FULL NAME'                     => strtoupper($cpay['cust_name']),
                    'ADDRESS'                       => $cpay['address_line'] . ' ' . $cpay['city_name'] . ' ' . $cpay['district_name'] . ' ' . $cpay['state_name'] . ' ' . $cpay['customer_zipcode'],
                    'MOBILE NUMBER'                 => $cpay['customer_mobile_number'],
                    'REFERENCE DETAIL'              => $cpay['witness_person_name'] . ' | PHONE-' . $cpay['witness_person_phone'],
                    'MODEL SKU DETAIL'              => $cpay['sku_description'],
                    'CHASIS NUMBER|ENGINE NUMBER'   => $cpay['vin_number'] . ' | ' . $cpay['engine_number'],
                    'BROKER NAME'                   => $BROKER_NAME,
                    'SALESMAN NAME'                 => $cpay['salesman_name'],
                    'SALES PRICE'                   => $cpay['sales_total_amount'],
                    'PAYMENT TYPE'                  => strtoupper(duePaySourcesQuotation($cpay['due_payment_source'])),
                    'FINANCER NAME'                 => $cpay['bank_financer_name'],
                    'DOWN PAYMENT'                  => $cpay['down_payment'],
                    //CASH
                    'TOTAL CASH PAID'               => round(($cpay['cash_paid_balance'] - $cpay['down_payment']), 2),
                    'TOTAL CASH DUE'                => $cpay['cash_outstaning_balance'],
                    'CASH STATUS'                   => ($cpay['cash_status'] == '0') ? 'DUE' : "CLOSED",
                    //BANK FINANCE
                    'TOTAL BANK FINANCE AMOUNT'     => $cpay['bank_finance_amount'],
                    'TOTAL BANK FINANCE PAID'       => $cpay['bank_finance_paid_balance'],
                    'TOTAL BANK FINANCE DUE'        => $cpay['bank_finance_outstaning_balance'],
                    'BANK FINANCE STATUS'           => ($cpay['bank_finance_status'] == '0') ? "DUE" : "CLOSED",
                    //PERSONAL FINANCE
                    'TOTAL PERSONAL FINANCE AMOUNT' => $cpay['personal_finance_amount'],
                    'TOTAL PERSONAL FINANCE PAID'   => $cpay['personal_finance_paid_balance'],
                    'TOTAL PERSONAL FINANCE DUE'   => $cpay['personal_finance_outstaning_balance'],
                    'PERSONAL FINANCE STATUS'       => ($cpay['personal_finance_status'] == '0') ? "DUE" : "CLOSED",
                    //OVER ALL
                    'TOTAL PAID'                    => $total_paid,
                    'TOTAL DUE'                     => $total_due,
                    'OVERALL STATUS'                => ($cpay['status'] == '0') ? "DUE" : "CLOSED",
                );
            }
        }
        return $customer_payments;
    }

    //Function for formate data - financer_wise_payment_data_formate
    public static function financer_wise_payment_data_formate($customer_payments)
    {
        // die('financer_wise_payment_data_formate');
        if (count($customer_payments) > 1) {
            foreach ($customer_payments as  $k => $cpay) {
                if ($k == 0) {
                    continue;
                }
                // echo '<pre>';print_r($cpay);
                $total_due  = ($cpay['bank_finance_outstaning_balance'] + $cpay['cash_outstaning_balance'] + $cpay['personal_finance_outstaning_balance']);
                $total_paid = ($cpay['cash_paid_balance'] + $cpay['bank_finance_paid_balance'] + $cpay['personal_finance_paid_balance']);

                $BROKER_NAME = "";
                if ($cpay['transfer_status'] == '1') {
                    $transfer_data = PurchaseTransfer::where(['purchase_id' => $cpay['sale_purchase_id'], 'status' => '0'])
                        ->select('id', 'purchase_id', 'broker_id', 'status')->with('brokr:id,name')->has('brokr')->first();
                    if ($transfer_data) {
                        $transfer_data = $transfer_data->toArray();
                        $BROKER_NAME = isset($transfer_data['brokr']['name']) ? strtoupper($transfer_data['brokr']['name']) : "";
                    }
                }
                $customer_payments[$k] = array(
                    'BRANCH NAME'                   => $cpay['sale_branch_name'],
                    'SALES DATE'                    => $cpay['sale_date'],
                    'FULL NAME'                     => strtoupper($cpay['cust_name']),
                    'ADDRESS'                       => $cpay['address_line'] . ' ' . $cpay['city_name'] . ' ' . $cpay['district_name'] . ' ' . $cpay['state_name'] . ' ' . $cpay['customer_zipcode'],
                    'MOBILE NUMBER'                 => $cpay['customer_mobile_number'],
                    'REFERENCE DETAIL'              => $cpay['witness_person_name'] . ' | PHONE-' . $cpay['witness_person_phone'],
                    'MODEL SKU DETAIL'              => $cpay['sku_description'],
                    'CHASIS NUMBER|ENGINE NUMBER'   => $cpay['vin_number'] . ' | ' . $cpay['engine_number'],
                    'BROKER NAME'                   => $BROKER_NAME,
                    'SALESMAN NAME'                 => $cpay['salesman_name'],
                    'SALES PRICE'                   => $cpay['sales_total_amount'],
                    'PAYMENT TYPE'                  => strtoupper(duePaySourcesQuotation($cpay['due_payment_source'])),
                    'FINANCER NAME'                 => $cpay['bank_financer_name'],
                    'DOWN PAYMENT'                  => $cpay['down_payment'],
                    //CASH
                    'TOTAL CASH PAID'               => round(($cpay['cash_paid_balance'] - $cpay['down_payment']), 2),
                    'TOTAL CASH DUE'                => $cpay['cash_outstaning_balance'],
                    'CASH STATUS'                   => ($cpay['cash_status'] == '0') ? 'DUE' : "CLOSED",
                    //BANK FINANCE
                    'TOTAL BANK FINANCE AMOUNT'     => $cpay['bank_finance_amount'],
                    'TOTAL BANK FINANCE PAID'       => $cpay['bank_finance_paid_balance'],
                    'TOTAL BANK FINANCE DUE'        => $cpay['bank_finance_outstaning_balance'],
                    'BANK FINANCE STATUS'           => ($cpay['bank_finance_status'] == '0') ? "DUE" : "CLOSED",
                    //PERSONAL FINANCE
                    'TOTAL PERSONAL FINANCE AMOUNT' => $cpay['personal_finance_amount'],
                    'TOTAL PERSONAL FINANCE PAID'   => $cpay['personal_finance_paid_balance'],
                    'TOTAL PERSONAL FINANCE DUE'   => $cpay['personal_finance_outstaning_balance'],
                    'PERSONAL FINANCE STATUS'       => ($cpay['personal_finance_status'] == '0') ? "DUE" : "CLOSED",
                    //OVER ALL
                    'TOTAL PAID'                    => $total_paid,
                    'TOTAL DUE'                     => $total_due,
                    'OVERALL STATUS'                => ($cpay['status'] == '0') ? "DUE" : "CLOSED",
                );
            }
        }
        // echo '<pre>';print_r($customer_payments);die;
        return $customer_payments;
    }



}
