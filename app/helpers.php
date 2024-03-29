<?php

use App\Http\Controllers\SalesAccountController;
use App\Models\PurchaseTransfer;
use App\Models\Sale;
use App\Models\SalePaymentAccounts;
use App\Models\SalePaymentBankFinanace;
use App\Models\SalePaymentCash;
use App\Models\SalePaymentPersonalFinanace;
use Illuminate\Support\Str;
use NumberToWords\NumberToWords;


if (!function_exists('action_buttons')) {
    function action_buttons()
    {
        return '<div class="d-flex align-items-center list-action">
            <a class="badge badge-info mr-2" data-toggle="tooltip" data-placement="top"
                title="" data-original-title="View" href="#"><i
                    class="ri-eye-line mr-0"></i></a>
            <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top"
                title="" data-original-title="Edit" href="#"><i
                    class="ri-pencil-line mr-0"></i></a>
            <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top"
                title="" data-original-title="Delete" href="#"><i
                class="ri-delete-bin-line mr-0"></i></a>
        </div>';
    }
}

if (!function_exists('bike_types')) {
    function bike_types()
    {
        return [
            'Motorcycle' => "Motorcycle",
            'Scooter' => "Scooter"
        ];
    }
}

if (!function_exists('bike_fuel_types')) {
    function bike_fuel_types()
    {
        return [
            'Petrol' => "Petrol",
            'Electric' => "Electric",
            'CNG' => "CNG",
            'Diesel' => "Diesel"
        ];
    }
}

if (!function_exists('break_types')) {
    function break_types()
    {
        return [
            'Normal' => "Normal",
            'Disk' => "Disk"
        ];
    }
}

if (!function_exists('wheel_types')) {
    function wheel_types()
    {
        return [
            'Alloy' => "Alloy",
            'Spoke' => "Spoke"
        ];
    }
}

if (!function_exists('vin_physical_statuses')) {
    function vin_physical_statuses()
    {
        $data = [
            'Good' => "Good",
            'Damaged' => "Damaged",
            'Not Recieved' => "Not Recieved"
        ];
        return $data;
    }
}

if (!function_exists('depositeSources')) {
    function depositeSources($id = 0)
    {
        $data = [
            'Cash'        => "Cash",
            'Cheque'      => "Cheque",
            'Netbanking'  => "Netbanking",
            'UPI'         => "UPI",
            'Credit Card' => "Credit Card",
            'Debit Card'  => "Debit Card",
            'Vehicle Exchange'  => "Vehicle Exchange"
        ];

        if ($id > 0) {
            return $data[$id];
        }
        return $data;
    }
}

if (!function_exists('duePaySources')) {
    function duePaySources($id = 0)
    {
        $data = [
            '1'  => "Self Pay",
            '2'  => "Bank Finance",
            '3'  => "Personal Finance"
        ];

        if ($id > 0) {
            return $data[$id];
        }
        return $data;
    }
}

if (!function_exists('duePaySourcesQuotation')) {
    function duePaySourcesQuotation($id = 0)
    {
        $data = [
            '1'  => "By Cash",
            '2'  => "Bank Finance",
            '3'  => "Personal Finance"
        ];
        if ($id > 0) {
            return $data[$id];
        }
        return $data;
    }
}

if (!function_exists('paymentAccountTypes')) {
    function paymentAccountTypes($id = 0)
    {
        $data = [
            '1'  => "Cash / Credit",
            '2'  => "Bank Finance",
            '3'  => "Personal Finance"
        ];
        if ($id > 0) {
            return isset($data[$id]) ? $data[$id] : "";
        }
        return $data;
    }
}

if (!function_exists('emiTerms')) {
    function emiTerms($id = 0)
    {
        $data = [
            '1'  => "Monthy",
            '2'  => "Quaterly",
            '3'  => "Half Yearly",
            '4'  => "Yearly Yearly",
            '5'  => 'Every 2 Month',
            '6'  => 'Every 4 Month'
        ];

        if ($id > 0) {
            return isset($data[$id]) ? $data[$id] : '';
        }
        return $data;
    }
}

if (!function_exists('emiTermsMonths')) {
    function emiTermsMonths($id)
    {
        $data = [
            '1'  => 1,
            '2'  => 3,
            '3'  => 6,
            '4'  => 12,
            '5'  => 2,
            '6'  => 4
        ];
        return isset($data[$id]) ? $data[$id] : 1;
    }
}

if (!function_exists('getStatus')) {
    function getStatus($id = 0)
    {
        $data = [
            '0'  => "Open",
            '1'  => "Close"
        ];
        if ($id > 0) {
            return $data[$id];
        }
        return $data;
    }
}

if (!function_exists('custPrefix')) {
    function custPrefix($id = 0)
    {
        $data = [
            '1'  => "Mr",
            '2'  => "Mrs",
            '3'  => "Miss"
        ];
        if ($id > 0) {
            return $data[$id];
        }
        return $data;
    }
}

if (!function_exists('custRel')) {
    function custRel($id = 0)
    {
        $data = [
            '1'  => "S/o",
            '2'  => "W/o",
            '3'  => "D/o"
        ];
        if ($id > 0) {
            return $data[$id];
        }
        return $data;
    }
}


if (!function_exists('models_list')) {
    function models_list($models, $selected_id = 0)
    {
        $options = "<option value=''>---Select Model---</option>";
        if (count($models)) {
            foreach ($models as $model) {
                $selected = ($model['id'] == $selected_id) ? 'selected="selected"' : '';

                $options .= "<option value='" . $model['id'] . "' " . $selected . ">" . $model['model_name'] . "</option>";
            }
        }
        return $options;
    }
}

if (!function_exists('colors_list')) {
    function colors_list($colors, $selected_id = 0)
    {
        $options = "<option value=''>---Select Model---</option>";
        if (count($colors)) {
            foreach ($colors as $color) {
                $selected = ($color['id'] == $selected_id) ? 'selected="selected"' : '';
                $options .= "<option value='" . $color['id'] . "' " . $selected . ">" . $color['color_name'] . "</option>";
            }
        }
        return $options;
    }
}

if (!function_exists('random_uuid')) {
    function random_uuid($module = "purc", $sep = '_')
    {
        return strtolower($module . $sep . Str::random(16));
    }
}

if (!function_exists('custFullAddress')) {
    function custFullAddress($data = array())
    {
        $str = "";

        if (isset($data['customer_address_line'])) {
            $str .= $data['customer_address_line'] . ',';
        }

        if (isset($data['city']['city_name'])) {
            $str .= $data['city']['city_name'] . ' ';
        }

        if (isset($data['district']['district_name'])) {
            $str .= $data['district']['district_name'] . ' ';
        }

        if (isset($data['state']['state_name'])) {
            $str .= $data['state']['state_name'] . ' ';
        }

        if (isset($data['customer_zipcode'])) {
            $str .= $data['customer_zipcode'];
        }

        return ucwords(strtolower($str));
    }
}

if (!function_exists('custFullName')) {
    function custFullName($data = array())
    {
        $str = "";
        if (isset($data['customer_gender'])) {
            $str .= custPrefix($data['customer_gender']) . '. ';
        }

        if (isset($data['customer_name'])) {
            $str .= $data['customer_name'] . ' ';
        }

        if (isset($data['customer_relationship'])) {
            $str .= custRel($data['customer_relationship']) . ' ';
        }

        if (isset($data['customer_guardian_name'])) {
            $str .= $data['customer_guardian_name'];
        }

        return ucwords(strtolower($str));
    }
}

if (!function_exists('priceFormate')) {
    function priceFormate($price = 0, $only_symbol = false)
    {
        return ($only_symbol == true) ? "₹" : "₹" . number_format($price, 2);
    }
}

if (!function_exists('leadingZero')) {
    function leadingZero($number, $len = 5, $pos = STR_PAD_LEFT)
    {
        return str_pad($number, $len, 0, $pos);
    }
}

if (!function_exists('convertBadgesStr')) {
    function convertBadgesStr($value, $type = "")
    {
        switch ($value) {
            case '0':
            case 'open':
                return '<span class="label label-danger">NO</span>';
                break;
            case '1':
            case 'close':
                if ($type == 'purSold') {
                    return '<span class="label label-danger">NO</span>';
                } else {
                    return '<span class="label label-success">YES</span>';
                }
                break;
            case '2':
                return '<span class="label label-success">YES</span>';
                break;
        }
    }
}

if (!function_exists('convertBadgesPrice')) {
    function convertBadgesPrice($value, $class = 'success')
    {
        switch (strtolower($class)) {
            case 'danger':
                return '<span class="label label-danger">' . priceFormate($value) . '</span>';
                break;
            case 'success':
                return '<span class="label label-success">' . priceFormate($value) . '</span>';
                break;
            case 'warning':
                return '<span class="label label-warning">' . priceFormate($value) . '</span>';
                break;
            case 'info':
                return '<span class="label label-info">' . priceFormate($value) . '</span>';
                break;
            case 'primary':
                return '<span class="label label-primary">' . priceFormate($value) . '</span>';
                break;
        }
    }
}

if (!function_exists('myDateFormate')) {
    function myDateFormate($date = null)
    {
        if ($date != null || ($date != "")) {
            return date('Y-m-d', strtotime($date));
        } else {
            return date('Y-m-d');
        }
    }
}

if (!function_exists('number2WordConvert')) {
    function number2WordConvert($value)
    {
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $str = $numberTransformer->toWords($value);

        return strtoupper('Rupees ' . $str . ' Only');
    }
}

if (!function_exists('maskNumberOnlyLast4')) {
    function maskNumberOnlyLast4($value, $len = 4, $char = "*")
    {
        $total_len = strlen($value);
        if ($total_len > 4) {
            $last4Digits = substr($value, - ($len));
            return str_pad($last4Digits, $total_len, $char, STR_PAD_LEFT);
        } else {
            return $value;
        }
    }
}


if (!function_exists('sales2RtoPayload')) {
    function sales2RtoPayload($data)
    {
        return array(
            'contact_name'   => isset($data['customer_name']) ? $data['customer_name'] : "",
            'contact_mobile_number' => isset($data['customer_mobile_number']) ? $data['customer_mobile_number'] : "",
            'contact_address_line'  => isset($data['customer_address_line']) ? $data['customer_address_line'] : "",
            'contact_state_id'      => isset($data['customer_state']) ? $data['customer_state'] : "",
            'contact_district_id'   => isset($data['customer_district']) ? $data['customer_district'] : "",
            'contact_city_id'       => isset($data['customer_city']) ? $data['customer_city'] : "",
            'contact_zipcode'       => isset($data['customer_zipcode']) ? $data['customer_zipcode'] : "",
            'sku'                   => isset($data['purchase']['sku']) ? $data['purchase']['sku'] : "",
            'financer_name'         => isset($data['financer']['bank_name']) ? $data['financer']['bank_name'] : "",
            'ex_showroom_amount'    => isset($data['ex_showroom_price']) ? $data['ex_showroom_price'] : "",
            'tax_amount'            => 0.00,
            'hyp_amount'            => isset($data['hypothecation_amount']) ? $data['hypothecation_amount'] : "",
            'tr_amount'             => 0.00,
            'fees'                  => 0.00,
            'total_amount'          => 0.00,
            'chasis_number'         => isset($data['purchase']['vin_number']) ? $data['purchase']['vin_number'] : "",
            'engine_number'         => isset($data['purchase']['engine_number']) ? $data['purchase']['engine_number'] : "",
            'broker_name'           => (isset($data['purchase']['purchase_transfer_latest']['brokr']['name']) && ($data['purchase']['purchase_transfer_latest']['status'] == '0')) ? $data['purchase']['purchase_transfer_latest']['brokr']['name'] : "",
            'broker_id'             => (isset($data['purchase']['purchase_transfer_latest']['brokr']['id']) && ($data['purchase']['purchase_transfer_latest']['status'] == '0')) ? $data['purchase']['purchase_transfer_latest']['brokr']['id'] : 0
        );
    }
}

if (!function_exists('createStringSales')) {
    function createStringSales($saleModel)
    {
        $string = "";
        if (isset($saleModel->purchases->vin_number)) {
            $string .= $saleModel->purchases->vin_number . ' | ';
        }

        if (isset($saleModel->purchases->engine_number)) {
            $string .= $saleModel->purchases->engine_number . ' | ';
        }

        if (isset($saleModel->purchases->hsn_number)) {
            $string .= $saleModel->purchases->hsn_number . ' | ';
        }

        if (isset($saleModel->purchases->sku)) {
            $string .= $saleModel->purchases->sku;
        }
        return strtoupper($string);
    }
}

if (!function_exists('getCashDueTotal')) {
    function getCashDueTotal($salesAccountId)
    {
        $totalCredit = SalePaymentCash::where('sale_payment_account_id', $salesAccountId)->sum('credit_amount');
        $totalDebit = SalePaymentCash::where('sale_payment_account_id', $salesAccountId)->sum('debit_amount');
        return (floatval($totalCredit - $totalDebit));
    }
}

if (!function_exists('getBankFinanaceDueTotal')) {
    function getBankFinanaceDueTotal($salesAccountId)
    {
        $totalCredit = SalePaymentBankFinanace::where('sale_payment_account_id', $salesAccountId)->sum('credit_amount');
        $totalDebit = SalePaymentBankFinanace::where('sale_payment_account_id', $salesAccountId)->sum('debit_amount');
        return (floatval($totalCredit - $totalDebit));
    }
}

if (!function_exists('updateDuesOrPaidBalance')) {
    function updateDuesOrPaidBalance($salesAccountId)
    {
        //Cash Account
        $totalCreditCash = SalePaymentCash::where('sale_payment_account_id', $salesAccountId)->sum('credit_amount');
        $totalDebitCash = SalePaymentCash::where('sale_payment_account_id', $salesAccountId)->sum('debit_amount');
        $totalOutStadningCash = floatval($totalCreditCash - $totalDebitCash);
        $totalPaidCash = SalePaymentCash::where('sale_payment_account_id', $salesAccountId)->whereNotIn('paid_source', ['Auto', 'auto'])->sum('debit_amount');
        $cashStatus = ($totalOutStadningCash == 0) ? 1 : 0;

        //Bank Finance
        $totalCreditBankFin = SalePaymentBankFinanace::where('sale_payment_account_id', $salesAccountId)->sum('credit_amount');
        $totalDebitBankFin = SalePaymentBankFinanace::where('sale_payment_account_id', $salesAccountId)->sum('debit_amount');
        $totalOutStadningBankFin = floatval($totalCreditBankFin - $totalDebitBankFin);
        $totalPaidBankFin = SalePaymentBankFinanace::where('sale_payment_account_id', $salesAccountId)->whereNotIn('paid_source', ['Auto', 'auto'])->sum('debit_amount');
        $bankFinStatus = ($totalOutStadningBankFin <= 0) ? 1 : 0;

        //Personal Finance
        $perFinanaceTotalUnpaid = SalePaymentPersonalFinanace::where('sale_payment_account_id', $salesAccountId)->where('status', 0)->sum('emi_total_amount');
        $perFinanaceTotalPaid  = SalePaymentPersonalFinanace::where('sale_payment_account_id', $salesAccountId)->sum('amount_paid');

        // dd(($totalCreditPerFin . '  ' . $totalDebitPerFin));
        $totalOutStadningPerFin = floatval($perFinanaceTotalUnpaid);
        $totalPaidPerFin =  $perFinanaceTotalPaid;
        $PerFinStatus = ($totalOutStadningPerFin == 0) ? 1 : 0;

        $accountStatus = (($cashStatus == 1 && $bankFinStatus == 1 && $PerFinStatus == 1)) ? 1 : 0;

        //UPDATE IN DATABASE
        $saleAccountModel = SalePaymentAccounts::find($salesAccountId);
        $saleAccountModel->where('id', $salesAccountId)->update([
            'cash_outstaning_balance'               => $totalOutStadningCash,
            'cash_paid_balance'                     => $totalPaidCash,
            'cash_status'                           => $cashStatus,
            'bank_finance_outstaning_balance'       => $totalOutStadningBankFin,
            'bank_finance_paid_balance'             => $totalPaidBankFin,
            'bank_finance_status'                   => $bankFinStatus,
            'personal_finance_outstaning_balance'   => $totalOutStadningPerFin,
            'personal_finance_paid_balance'         => $totalPaidPerFin,
            'personal_finance_status'               => $PerFinStatus,
            'status'                                => $accountStatus,
            'status_closed_note'                    => ($accountStatus == 1) ? "All dues done closed autometically by script." : null,
            'status_closed_by'                      => ($accountStatus == 1) ? 0 : null
        ]);

        //Update Sale Account Status
        Sale::where(['id' => $saleAccountModel->sale_id])->update([
            'status' => ($accountStatus == 1) ? 'close' : 'open',
        ]);

        return true;
    }
}

if (!function_exists('brokerNameByPurchase')) {
    function brokerNameByPurchase($purchaseId)
    {
        $purchsaeTransfer = PurchaseTransfer::with(['broker'])->where('purchase_id', $purchaseId)->where('active_status', '1')->orderBy('id', 'DESC')->first();
        if ($purchsaeTransfer && $purchsaeTransfer->status == '0') {
            return isset($purchsaeTransfer->broker->name) ? $purchsaeTransfer->broker->name : '';
        } else {
            return '';
        }
    }
}

//createStringSales
//₹
