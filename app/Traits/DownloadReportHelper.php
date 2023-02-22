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
            $query = self::setQuery();
            $query = self::setDate();
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
            if ($end_date && $start_date && config('date_filter')) {
                $query = $query->whereDate(config('date_filter'), '>=', $start_date)->whereDate(config('date_filter'), '<=', $end_date);
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
                    })->select('brokers.name as broker_name', 'branches.branch_name as branch', 'bike_dealers.company_name as dealer_company_name', 'bike_brands.name as brand', 'bike_models.model_name as model', 'bike_model_variants.variant_name', 'bike_colors.color_name as color', 'bike_type', 'bike_fuel_type', 'vin_number', 'vin_physical_status', 'hsn_number', 'engine_number', 'variant', 'sku', 'sku_description', 'key_number', 'service_book_number', 'battery_brands.name as battery_brand', 'battery_number', 'tyre_brands.name as tyre_brand', 'tyre_front_number', 'tyre_rear_number', 'dc_number', 'dc_date', 'gst_rate', 'pre_gst_amount', 'discount_price', 'gst_amount', 'ex_showroom_price', 'grand_total', 'bike_description');
                $heading = ['BROKER NAME', 'BRANCH NAME', 'DEALER NAME', 'BRAND NAME', 'MODEL NAME', 'MODEL VARIANT', 'VARIANT COLOR', 'VEHICLE TYPE', 'FUEL TYPE', 'VIN NUMBER(CHASIS NUMBER) ', 'VIN PHYSICAL STATUS', 'HSN NUMBER', 'ENGINE NUMBER', 'VARIANT CODE', 'SKU CODE', 'SKU DESCRIPTION', 'KEY NUMBER', 'SERVICE BOOK NUMBER', 'BATTERY BRAND', 'BATTERY NUMBER', 'TYRE BRAND', 'TYRE FRONT NUMBER', 'TYRE REAR NUMBER', 'DC NUMBER', 'DC DATE', 'GST RATE', 'ACTUAL PRICE(PRE GST)', 'DISCOUNT AMOUNT(-)', 'GST AMOUNT', 'EX SHOWROOM PRICE(+GST)', 'GRAND TOTAL', 'VEHICLE DESCRIPTION'];
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
                    })->doesntHave('invoice')
                    ->select('brokers.name as broker_name', 'branches.branch_name as branch', 'bike_dealers.company_name as dealer_company_name', 'bike_brands.name as brand', 'bike_models.model_name as model', 'bike_model_variants.variant_name', 'bike_colors.color_name as color', 'bike_type', 'bike_fuel_type', 'vin_number', 'vin_physical_status', 'hsn_number', 'engine_number', 'variant', 'sku', 'sku_description', 'key_number', 'service_book_number', 'battery_brands.name as battery_brand', 'battery_number', 'tyre_brands.name as tyre_brand', 'tyre_front_number', 'tyre_rear_number', 'dc_number', 'dc_date', 'gst_rate', 'pre_gst_amount', 'discount_price', 'gst_amount', 'ex_showroom_price', 'grand_total', 'bike_description');
                $heading = ['BROKER NAME', 'BRANCH NAME', 'DEALER NAME', 'BRAND NAME', 'MODEL NAME', 'MODEL VARIANT', 'VARIANT COLOR', 'VEHICLE TYPE', 'FUEL TYPE', 'VIN NUMBER(CHASIS NUMBER) ', 'VIN PHYSICAL STATUS', 'HSN NUMBER', 'ENGINE NUMBER', 'VARIANT CODE', 'SKU CODE', 'SKU DESCRIPTION', 'KEY NUMBER', 'SERVICE BOOK NUMBER', 'BATTERY BRAND', 'BATTERY NUMBER', 'TYRE BRAND', 'TYRE FRONT NUMBER', 'TYRE REAR NUMBER', 'DC NUMBER', 'DC DATE', 'GST RATE', 'ACTUAL PRICE(PRE GST)', 'DISCOUNT AMOUNT(-)', 'GST AMOUNT', 'EX SHOWROOM PRICE(+GST)', 'GRAND TOTAL', 'VEHICLE DESCRIPTION'];
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
                    })->doesntHave('invoice')
                    ->select('brokers.name as broker_name', 'branches.branch_name as branch', 'bike_dealers.company_name as dealer_company_name', 'bike_brands.name as brand', 'bike_models.model_name as model', 'bike_model_variants.variant_name', 'bike_colors.color_name as color', 'bike_type', 'bike_fuel_type', 'vin_number', 'vin_physical_status', 'hsn_number', 'engine_number', 'variant', 'sku', 'sku_description', 'key_number', 'service_book_number', 'battery_brands.name as battery_brand', 'battery_number', 'tyre_brands.name as tyre_brand', 'tyre_front_number', 'tyre_rear_number', 'dc_number', 'dc_date', 'gst_rate', 'pre_gst_amount', 'discount_price', 'gst_amount', 'ex_showroom_price', 'grand_total', 'bike_description');
                $heading = ['BROKER NAME', 'BRANCH NAME', 'DEALER NAME', 'BRAND NAME', 'MODEL NAME', 'MODEL VARIANT', 'VARIANT COLOR', 'VEHICLE TYPE', 'FUEL TYPE', 'VIN NUMBER(CHASIS NUMBER) ', 'VIN PHYSICAL STATUS', 'HSN NUMBER', 'ENGINE NUMBER', 'VARIANT CODE', 'SKU CODE', 'SKU DESCRIPTION', 'KEY NUMBER', 'SERVICE BOOK NUMBER', 'BATTERY BRAND', 'BATTERY NUMBER', 'TYRE BRAND', 'TYRE FRONT NUMBER', 'TYRE REAR NUMBER', 'DC NUMBER', 'DC DATE', 'GST RATE', 'ACTUAL PRICE(PRE GST)', 'DISCOUNT AMOUNT(-)', 'GST AMOUNT', 'EX SHOWROOM PRICE(+GST)', 'GRAND TOTAL', 'VEHICLE DESCRIPTION'];

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
                    })->when(!empty(request('sale_type')), function ($q) {
                        if (request('type') == 'sold') {
                            $q->has('sale');
                        }
                        if (request('type') == 'unsold') {
                            $q->doesntHave('sale');
                        }
                    })->select('brokers.name as broker_name', 'branches.branch_name as branch', 'bike_dealers.company_name as dealer_company_name', 'bike_brands.name as brand', 'bike_models.model_name as model', 'bike_model_variants.variant_name', 'bike_colors.color_name as color', 'bike_type', 'bike_fuel_type', 'vin_number', 'vin_physical_status', 'hsn_number', 'engine_number', 'variant', 'sku', 'sku_description', 'key_number', 'service_book_number', 'battery_brands.name as battery_brand', 'battery_number', 'tyre_brands.name as tyre_brand', 'tyre_front_number', 'tyre_rear_number', 'dc_number', 'dc_date', 'gst_rate', 'pre_gst_amount', 'discount_price', 'gst_amount', 'ex_showroom_price', 'grand_total', 'bike_description');
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
                $query = RtoRegistration::select(['contact_name', 'contact_mobile_number', 'contact_address_line', 'u_cities.city_name', 'u_states.state_name', 'u_districts.district_name', 'contact_zipcode', 'sku', 'financer_name', 'gst_rto_rates.gst_rate', 'ex_showroom_amount', 'tax_amount', 'hyp_amount', 'tr_amount', 'fees', 'total_amount', 'remark', 'rc_number', 'rc_status', 'customer_given_name', 'customer_given_date', 'customer_given_note'])
                    ->leftJoin('u_cities', 'u_cities.id', '=', 'rto_registration.contact_city_id')
                    ->leftJoin('u_states', 'u_states.id', '=', 'rto_registration.contact_state_id')
                    ->leftJoin('u_districts', 'u_districts.id', '=', 'rto_registration.contact_district_id')
                    ->leftJoin('gst_rto_rates', 'gst_rto_rates.id', '=', 'rto_registration.gst_rto_rate_id')
                    ->leftJoin('sale_payment_accounts', 'sale_payment_accounts.sale_id', '=', 'rto_registration.sale_id')
                    ->when(!empty(request('rto_status')), function ($q) {
                        $q->whereIn('recieved_date', '!=', [null, '']);
                    })
                    ->when(!empty(request('sent_to_rto')), function ($q) {
                        $q->whereIn('submit_date', '!=', [null, '']);
                    })
                    ->when(!empty(request('pending_registration_number')), function ($q) {
                        $q->whereIn('rc_number', '!=', [null, '']);
                    })
                    ->when(!empty(request('rc_status')), function ($q) {
                        $q->whereRcStatus(request('rc_status'));
                    })
                    ->when(!empty(request('payment_outstanding')), function ($q) {
                        $q->whereIn('sale_payment_accounts', '!=', [null, '']);
                    });
                $heading = ['CONTACT NAME', 'CONTACT MOBILE NUMBER', 'CONTACT ADDRESS LINE', 'CONTACT STATE', 'CONTACT DISTRICT', 'CONTACT CITY', 'CONTACT ZIPCODE', 'SKU', 'FINANCER NAME', 'GST RATE (TAX RATE)', 'EX SHOWROOM AMOUNT', 'TAX AMOUNT', 'HYP AMOUNT', 'TR AMOUNT', 'FEES', 'TOTAL AMOUNT', 'RTO REGISTRATION REMARK(IF ANY)', 'RC NUMBER', 'RC STATUS', 'SUBMIT DATE', 'RECIEVED DATE', 'CUSTOMER GIVEN NAME(WHOM GIVEN)', 'Customer Given Name', 'CUSTOMER GIVEN DATE', 'CUSTOMER GIVEN NOTE(IF ANY)'];
                break;
            case 'accounts':
                $query = RtoRegistration::select(['contact_name', 'contact_mobile_number', 'contact_address_line', 'u_cities.city_name', 'u_states.state_name', 'u_districts.district_name', 'contact_zipcode', 'sku', 'financer_name', 'gst_rto_rates.gst_rate', 'ex_showroom_amount', 'tax_amount', 'hyp_amount', 'tr_amount', 'fees', 'total_amount', 'remark', 'rc_number', 'rc_status', 'customer_given_name', 'customer_given_date', 'customer_given_note'])
                    ->leftJoin('u_cities', 'u_cities.id', '=', 'rto_registration.contact_city_id')
                    ->leftJoin('u_states', 'u_states.id', '=', 'rto_registration.contact_state_id')
                    ->leftJoin('u_districts', 'u_districts.id', '=', 'rto_registration.contact_district_id')
                    ->leftJoin('gst_rto_rates', 'gst_rto_rates.id', '=', 'rto_registration.gst_rto_rate_id')
                    ->leftJoin('sale_payment_accounts', 'sale_payment_accounts.sale_id', '=', 'rto_registration.sale_id')
                    ->when(!empty(request('rto_status')), function ($q) {
                        $q->whereIn('recieved_date', '!=', [null, '']);
                    })
                    ->when(!empty(request('sent_to_rto')), function ($q) {
                        $q->whereIn('submit_date', '!=', [null, '']);
                    })
                    ->when(!empty(request('pending_registration_number')), function ($q) {
                        $q->whereIn('rc_number', '!=', [null, '']);
                    })
                    ->when(!empty(request('rc_status')), function ($q) {
                        $q->whereRcStatus(request('rc_status'));
                    })
                    ->when(!empty(request('payment_outstanding')), function ($q) {
                        $q->whereIn('sale_payment_accounts', '!=', [null, '']);
                    });
                $heading = ['CONTACT NAME', 'CONTACT MOBILE NUMBER', 'CONTACT ADDRESS LINE', 'CONTACT STATE', 'CONTACT DISTRICT', 'CONTACT CITY', 'CONTACT ZIPCODE', 'SKU', 'FINANCER NAME', 'GST RATE (TAX RATE)', 'EX SHOWROOM AMOUNT', 'TAX AMOUNT', 'HYP AMOUNT', 'TR AMOUNT', 'FEES', 'TOTAL AMOUNT', 'RTO REGISTRATION REMARK(IF ANY)', 'RC NUMBER', 'RC STATUS', 'SUBMIT DATE', 'RECIEVED DATE', 'CUSTOMER GIVEN NAME(WHOM GIVEN)', 'Customer Given Name', 'CUSTOMER GIVEN DATE', 'CUSTOMER GIVEN NOTE(IF ANY)'];
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
                    ->select(['branches.branch_name as branch_name', 'salesmans.name as salesman_name', 'customer_gender', 'customer_name', 'customer_relationship', 'customer_guardian_name', 'customer_address_line', 'u_states.state_name', 'u_districts.district_name', 'u_cities.city_name', 'customer_zipcode', 'customer_mobile_number', 'customer_mobile_number_alt', 'customer_email_address', 'payment_type', 'is_exchange_avaliable', 'hyp_financer', 'hyp_financer_description', 'purchase_visit_date', 'purchase_est_date', 'bike_brands.name', 'bike_models.model_name', 'bike_colors.color_name', 'ex_showroom_price', 'registration_amount', 'insurance_amount', 'hypothecation_amount', 'accessories_amount', 'other_charges', 'total_amount', 'status', 'close_note']);
                $heading = ['Branch', 'Salsesman', 'Customer Gender', 'Customer Name', 'Customer Relationship', 'Customer Guardian Name', 'Customer Address Line', 'Customer State', 'Customer District', 'Customer City', 'Customer Zipcode', 'Customer Mobile Number', 'Customer Mobile Number Alt', 'Customer Email Address', 'Payment Type', 'is Exchange Avaliable', 'hyp Financer', 'hyp Financer Description', 'Purchase Visit Date', 'Purchase EST Date', 'Bike Brand Name', 'Bike Model Name', 'Bike Color Name', 'Ex Showroom Price', 'Registration Amount', 'Insurance Amount', 'Hypothecation Amount', 'Accessories Amount', 'Other Charges', 'Total Amount', 'Status', 'Close Note'];
                config(['date_filter' => 'quotations.created_at']);
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
                    ->select(['branches.branch_name as branch_name', 'purchases.dc_number as dc_number', 'quotations.uuid as quotation_uuid', 'salesmans.name as salesman_name', 'sales.customer_address_line', 'u_states.state_name', 'u_districts.district_name', 'u_cities.city_name', 'sales.customer_zipcode', 'sales.customer_mobile_number', 'sales.customer_mobile_number_alt', 'sales.customer_email_address', 'sales.witness_person_name', 'sales.witness_person_phone', 'sales.payment_type', 'sales.is_exchange_avaliable', 'sales.hyp_financer', 'sales.hyp_financer_description', 'sales.ex_showroom_price', 'sales.registration_amount', 'sales.insurance_amount', 'sales.hypothecation_amount', 'sales.accessories_amount', 'sales.other_charges', 'sales.total_amount', 'sales.status']);
                $heading = ['branch name', 'dc number', 'quotation uuid', 'salesman name', 'customer address line', 'state name', 'district name', 'city name', 'customer zipcode', 'customer mobile number', 'customer mobile number alt', 'customer email address', 'witness person name', 'witness person phone', 'payment type', 'is exchange avaliable', 'hyp financer', 'hyp financer description', 'ex showroom price', 'registration amount', 'insurance amount', 'hypothecation amount', 'accessories amount', 'other charges', 'total amount', 'status', 'customer name'];
                config(['date_filter' => 'sales.created_at']);
                break;
            default:
                //
                break;
        }
        config(['query' => $query, 'heading' => $heading]);
        return $query;
    }
}
