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
        return [
            'Good' => "Good",
            'Damaged' => "Damaged",
            'Not Recieved' => "Not Recieved"
        ];
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

if (!function_exists('random_uuid')) {
    function random_uuid($module = "purc")
    {
        return strtolower($module . '-' . Str::random(12));
    }
}
