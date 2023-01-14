<?php

use Illuminate\Support\Str;


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
            'Bike' => "Bike",
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
            'Debit Card'  => "Debit Card"
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

if (!function_exists('emiTerms')) {
    function emiTerms($id = 0)
    {
        $data = [
            '1'  => "Monthy",
            '2'  => "Quaterly",
            '3'  => "Half Yearly",
            '4'  => "Half Yearly"
        ];

        if ($id > 0) {
            return $data[$id];
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
            '4'  => 12
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

if (!function_exists('sales2RtoPayload')) {
    function sales2RtoPayload($data)
    {
        return array(
            'contact_name'   => isset($data['customer_name']) ? $data['customer_name'] : "",
            'contact_mobile_number' => isset($data['customer_mobile_number']) ? $data['customer_mobile_number'] : "",
            'contact_address_line'  => isset($data['customer_address_line']) ? $data['customer_address_line'] : "",
            'contact_state_id'      => isset($data['customer_state']) ? $data['customer_state'] : "",
            'contact_district_id'  => isset($data['customer_district']) ? $data['customer_district'] : "",
            'contact_city_id' => isset($data['customer_city']) ? $data['customer_city'] : "",
            'contact_zipcode' => isset($data['customer_zipcode']) ? $data['customer_zipcode'] : "",
            'sku' => isset($data['purchase']['sku']) ? $data['purchase']['sku'] : "",
            'financer_name' => "",
            'ex_showroom_amount' => isset($data['ex_showroom_price']) ? $data['ex_showroom_price'] : "",
            'tax_amount' => 0.00,
            'hyp_amount' => isset($data['hypothecation_amount']) ? $data['hypothecation_amount'] : "",
            'tr_amount' => 0.00,
            'fees' => 0.00,
            'total_amount' => 0.00,
        );
    }
}

if (!function_exists('createStringSales')) {
    function createStringSales($saleModel)
    {
        $string = "";
        if (isset($saleModel->customer_name)) {
            $string .= $saleModel->customer_name . ' | ';
        }

        if (isset($saleModel->purchases->sku)) {
            $string .= $saleModel->purchases->sku . ' | ';
        }

        if (isset($saleModel->purchases->vin_number)) {
            $string .= $saleModel->purchases->vin_number . ' | ';
        }

        if (isset($saleModel->purchases->hsn_number)) {
            $string .= $saleModel->purchases->hsn_number . ' | ';
        }

        if (isset($saleModel->purchases->engine_number)) {
            $string .= $saleModel->purchases->engine_number;
        }
        return strtoupper($string);
    }
}

//createStringSales
//₹
