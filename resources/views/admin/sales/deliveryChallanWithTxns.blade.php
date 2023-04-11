<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ strip_tags(config('constants.APP_NAME')) }} | Delivery Challan</title>
</head>

<body>
    <table style="border:1px solid; border-collapse: collapse;width:100%;">
        <tr>
            <td style=" height: 2rem; text-align: center;border: 1px solid black;font-size: 2.5rem; color: red;font-weight: 700; padding-top: 0.5rem;
            padding-bottom: 0.5rem; font-weight: 600; "
                colspan="2">
                {{ isset($data->branch) ? $data->branch->branch_name : 'No Branch' }}
            </td>
        </tr>
        <tr>
            <td style="text-align: center;border: 1px solid; height: 2rem; height: 2rem;" colspan="2">
                {{ isset($data->branch) ? $data->branch->branch_address_line : ' ' }}
                {{ isset($data->branch) ? $data->branch->branch_pincode : ' ' }}
            </td>
        </tr>
        <tr>
            <td style="text-align: center;border: 1px solid; height: 2rem; height: 2rem;" colspan="2">
                MO: {{ isset($data->branch) ? $data->branch->branch_phone : ' ' }}
            </td>
        </tr>
        <tr>
            <td style="text-align: center;border: 1px solid; padding-left: 0.5rem; height: 2rem;" colspan="2">
                GSTIN : {{ isset($data->branch) ? $data->branch->gstin_number : ' ' }}
            </td>
        </tr>

        <tr>
            <td style="width:100%;" colspan="2">
                <table style="width:100%;border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid;" colspan="2">
                        <td style="width: 20%; padding-left: 0.5rem; height: 2rem; text-align: center;">S.NO</td>
                        <td style="border-left: 1px solid; text-align: center; height: 2rem; font-size: 1.5rem; font-weight: 800;"
                            Rowspan="2">DELIVERY CHALLAN</td>
                        <td style="width: 20%; border-left: 1px solid; text-align: center; height: 2rem;">DATE</td>
                    </tr>
                    <tr style="border-bottom: 1px solid;">
                        <td style="width: 20%; padding-left: 0.5rem; height: 2rem; text-align: center;">
                            {{ isset($data->id) ? leadingZero($data->id, 6) : '---' }}
                        </td>
                        <td style="width: 20%; border-left: 1px solid; text-align: center; height: 2rem;">
                            {{ isset($data) ? date('d/m/Y', strtotime($data->created_at)) : ' ' }}
                        </td>
                    </tr>

                </table>
                <table style="width: 100%; ">
                    <tr>
                        <td
                            style="border-bottom: 1px solid; width:25%; padding-left: 0.5rem; height: 2rem; border-right: 1px solid;">
                            NAME
                        </td>
                        <td
                            style="border-bottom: 1px solid; width:75%;text-align: center; height: 2rem; font-weight: bold;">
                            {{ isset($data->cust_name) ? strtoupper($data->cust_name) : ' ' }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="border-bottom: 1px solid; width:25%; padding-left: 0.5rem; height: 2rem; border-right: 1px solid; ">
                            ADDRESS :
                        </td>
                        <td style="border-bottom: 1px solid; width:75%;text-align: center; height: 2rem;">
                            {{ isset($data->customer_address_line) ? ucwords($data->customer_address_line) . ',' : ' ' }}
                            {{ isset($data->city->city_name) ? ucwords($data->city->city_name) . ',' : ' ' }}
                            {{ isset($data->district->district_name) ? ucwords($data->district->district_name) . ',' : ' ' }}
                            {{ isset($data->state->state_name) ? ucwords($data->state->state_name) . ',' : ' ' }}
                            {{ isset($data->customer_zipcode) ? ucwords($data->customer_zipcode) : ' ' }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="border-bottom: 1px solid; width:25%; padding-left: 0.5rem; height: 2rem; border-right: 1px solid; ">
                            MOBILE NO :
                        </td>
                        <td style="border-bottom: 1px solid; width:75%;text-align: center; height: 2rem;">
                            {{ isset($data->customer_mobile_number) ? $data->customer_mobile_number : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid; width:25%; padding-left: 0.5rem; height: 2rem;"></td>
                        <td style="border-bottom: 1px solid; width:75%;text-align: center; height: 2rem;"></td>
                    </tr>
                    @if (isset($data->payment_type) && ($data->payment_type != '1'))
                        <tr>
                            <td
                                style=" width:25%; padding-left: 0.5rem; height: 2rem; border-right: 1px solid;border-bottom: 1px solid; ">
                                HYP:
                            </td>
                            <td
                                style=" width:75%;text-align: center; height: 2rem;font-weight: bold;font-size:18px;border-bottom: 1px solid;">
                                {{ isset($data->financer) && $data->financer ? $data->financer->bank_name : 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                style=" width:25%; padding-left: 0.5rem; height: 2rem; border-right: 1px solid;border-bottom: 1px solid; ">
                                HYP DESCRIPTION:
                            </td>
                            <td
                                style=" width:75%;text-align: center; height: 2rem;font-size:14px;border-bottom: 1px solid;">
                                {{ isset($data->hyp_financer_description) && $data->hyp_financer_description ? $data->hyp_financer_description : 'N/A' }}
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td style=" width:25%; padding-left: 0.5rem; height: 2rem; border-right: 1px solid; ">
                            BROKER:
                        </td>
                        <td style=" width:75%;text-align: center; height: 2rem;font-weight: bold;font-size:18px;">
                            {{ isset($broker_name) ? $broker_name : '' }}
                        </td>
                    </tr>

                </table>
            </td>
        </tr>

        <tr>
            <td style="text-align: left;border: 1px solid;width:100%; height: 2rem; " colspan="2">
                <table style="width:100%;border-collapse: collapse;">
                    <tr>
                        <td
                            style="border-right: 1px solid;width:16%;text-align: center; height: 2rem; border-bottom: 1px solid;">
                            MODEL:</td>
                        <td
                            style="border-right: 1px solid;width:16%;text-align: center; height: 2rem; border-bottom: 1px solid;">
                            SKU:</td>
                        <td
                            style="border-right: 1px solid;width:15%;text-align: center; height: 2rem; border-bottom: 1px solid;">
                            CHASSIS NO:
                        </td>
                        <td
                            style="text-align: center; height: 2rem;width:16%;border-bottom: 1px solid; border-right: 1px solid;">
                            ENGINE NO:
                        </td>
                        <td
                            style="text-align: center; border-collapse: collapse;width:16%; border-bottom: 1px solid; border-right: 1px solid;">
                            KEY NO:
                        </td>
                        <td style="text-align: center; border-collapse: collapse;width:16%; border-bottom: 1px solid; ">
                            COLOUR:
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="border-right: 1px solid;text-align: center; height: 2rem; font-weight: bold;font-size:14px;">
                            {{ isset($data->purchase->model) && $data->purchase->model->model_name ? $data->purchase->model->model_name : '' }}
                        </td>
                        <td
                            style="border-right: 1px solid;text-align: center; height: 2rem; font-weight: bold;font-size:14px;">
                            {{ isset($data->purchase->sku) && $data->purchase->sku ? $data->purchase->sku : '' }}
                        </td>
                        <td
                            style="text-align: center; height: 2rem; border-right: 1px solid; font-weight: bold;font-size:14px;">
                            {{ isset($data->purchase->vin_number) && $data->purchase->vin_number ? $data->purchase->vin_number : '' }}
                        </td>
                        <td
                            style="text-align: center; height: 2rem; border-right: 1px solid; font-weight: bold;font-size:14px;">
                            {{ isset($data->purchase->engine_number) && $data->purchase->engine_number ? $data->purchase->engine_number : '' }}
                        </td>
                        <td
                            style="text-align: center; height: 2rem; border-right: 1px solid; font-weight: bold;font-size:14px;">
                            {{ isset($data->purchase->key_number) && $data->purchase->key_number ? $data->purchase->key_number : '' }}
                        </td>
                        <td
                            style="text-align: center; height: 2rem; border-right: 1px solid; font-weight: bold;font-size:14px;">
                            {{ isset($data->purchase->color) && $data->purchase->color->color_name ? $data->purchase->color->color_name : '' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="text-align: left; border: 1px solid;width:100%; height: 2rem;" colspan="2">
                <table style="width:100%;border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid;">
                        <td style=" width:60%;  text-align: left; height: 2rem; padding-left: 0.5rem;">
                            EX SHOWROOM PRICE
                        </td>
                        <td
                            style=" width:40%; border-left: 1px solid; text-align: right; height: 2.5rem; padding-left: 0.5rem; font-size: 1.2rem;padding-right:6px;">
                            {{ isset($data->ex_showroom_price) ? $data->ex_showroom_price : '' }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid;">
                        <td style="width:60%;  text-align: left; height: 2rem; padding-left: 0.5rem;">RTO</td>
                        <td
                            style="width:40%; border-left: 1px solid; text-align: right; height: 2.5rem; padding-left: 0.5rem; font-size: 1.2rem;padding-right:6px;">
                            {{ isset($data->registration_amount) ? $data->registration_amount : '' }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid;">
                        <td style="width:60%;  text-align: left; height: 2rem; padding-left: 0.5rem;">INSURANCE AMOUNT
                        </td>
                        <td
                            style="width:40%; border-left: 1px solid; text-align: right; height: 2.5rem; padding-left: 0.5rem; font-size: 1.2rem;padding-right:6px;">
                            {{ isset($data->insurance_amount) ? $data->insurance_amount : '' }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid;">
                        <td style=" width:60%;  text-align: left; height: 2rem; padding-left: 0.5rem;">HYPOTHECATION
                        </td>
                        <td
                            style=" width:40%; border-left: 1px solid; text-align: right; height: 2.5rem; padding-left: 0.5rem; font-size: 1.2rem;padding-right:6px;">
                            {{ isset($data->hypothecation_amount) ? $data->hypothecation_amount : '' }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid;">
                        <td style="width:60%;  text-align: left; height: 2rem; padding-left: 0.5rem;">ACCESSORIES</td>
                        <td
                            style="width:40%; border-left: 1px solid; text-align: right; height: 2.5rem; padding-left: 0.5rem; font-size: 1.2rem;padding-right:6px;">
                            {{ isset($data->accessories_amount) ? $data->accessories_amount : '' }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid;">
                        <td style=" width:60%;  text-align: left; height: 2rem; padding-left: 0.5rem;">OTHER</td>
                        <td
                            style=" width:40%; border-left: 1px solid; text-align: right; height: 2.5rem; padding-left: 0.5rem; font-size: 1.2rem;padding-right:6px;">
                            {{ isset($data->other_charges) ? $data->other_charges : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            style=" width:60%;  text-align: left; height: 2rem; padding-left: 0.5rem;font-weight: bold;">
                            TOTAL ON ROAD
                        </td>
                        <td
                            style=" width:40%; border-left: 1px solid; text-align: right; height: 2.5rem; padding-left: 0.5rem; font-size: 1.2rem;padding-right:6px;font-weight: bold;">
                            {{ isset($data->total_amount) ? $data->total_amount : '' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; width:60%;padding: 50px 15px; height: 2rem;">
            </td>
            <td style="width: 40%; height: 2rem; border: 1px solid; padding-left:80px;">
                <table>
                    <tr style="text-align: center;">
                        <td style="text-align: center;height: 4rem;">
                            {{ isset($data->branch) ? $data->branch->branch_name : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; height: 4rem;">
                            Signature
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
