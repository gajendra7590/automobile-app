<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ strip_tags(config('constants.APP_NAME')) }} | Print Pay Reciept</title>
</head>

<body>
    <table style="border:1px solid; border-collapse: collapse;width:100%;">
        <tr>
            <td style=" height: 2rem; text-align: center;border: 1px solid black;font-size: 2.5rem; color: red;font-weight: 700; padding-top: 0.5rem;
            padding-bottom: 0.5rem; font-weight: 600; "
                colspan="2">
                {{ isset($data->sale->branch) ? $data->sale->branch->branch_name : 'No Branch' }}
            </td>
        </tr>
        <tr>
            <td style="text-align: center;border: 1px solid; height: 2rem; height: 2rem;" colspan="2">
                {{ isset($data->sale->branch) ? $data->sale->branch->branch_address_line : ' ' }}
                {{ isset($data->sale->branch) ? $data->sale->branch->branch_pincode : ' ' }}
            </td>
        </tr>
        <tr>
            <td style="text-align: center;border: 1px solid; height: 2rem; height: 2rem;" colspan="2">
                MO: {{ isset($data->sale->branch) ? $data->sale->branch->branch_phone : ' ' }}
            </td>
        </tr>
        <tr>
            <td style="text-align: center;border: 1px solid; padding-left: 0.5rem; height: 2rem;" colspan="2">
                GSTIN : {{ isset($data->sale->branch) ? $data->sale->branch->gstin_number : ' ' }}
            </td>
        </tr>
        <tr>
            <td style="width:100%;" colspan="2">
                <table style="width:100%;border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid;">
                        <td style="width: 50%; padding-left: 0.5rem; height: 2rem; text-align: center;">
                            TRANSACTION TYPE :
                        </td>
                        <td style="border-left: 1px solid; text-align: center; height: 2rem;">
                            PAY.NO
                        </td>
                        <td style="border-left: 1px solid; text-align: center; height: 2rem;">
                            {{ isset($data) ? leadingZero($data->id, 6) : ' ' }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid;">
                        <td
                            style="width: 50%; padding-left: 0.5rem; height: 2rem; text-align: center; font-size: 1.5rem; font-weight: 800;">
                            Payment Receipts
                        </td>
                        <td style="border-left: 1px solid;text-align: center; height: 2rem;">
                            DATE:
                        </td>
                        <td style="border-left: 1px solid; text-align: center; height: 2rem;">
                            {{ isset($data) ? date('d/m/Y', strtotime($data->created_at)) : ' ' }}
                        </td>
                    </tr>

                </table>
                <table style="width: 100%; ">
                    <tr>
                        <td
                            style="border-bottom: 1px solid; width:25%; padding-left: 0.5rem; height: 2rem; border-right: 1px solid;">
                            NAME :
                        </td>
                        <td
                            style="border-bottom: 1px solid; width:75%;text-align: center; height: 2rem; font-weight: bold;">
                            {{ isset($data->sale->cust_name) ? strtoupper($data->sale->cust_name) : ' ' }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="border-bottom: 1px solid; width:25%; padding-left: 0.5rem; height: 2rem; border-right: 1px solid; ">
                            ADDRESS : </td>
                        <td style="border-bottom: 1px solid; width:75%;text-align: center; height: 2rem;">
                            {{ isset($data->sale->customer_address_line) ? ucwords($data->sale->customer_address_line) . ',' : ' ' }}
                            {{ isset($data->sale->city->city_name) ? ucwords($data->sale->city->city_name) . ',' : ' ' }}
                            {{ isset($data->sale->district->district_name) ? ucwords($data->sale->district->district_name) . ',' : ' ' }}
                            {{ isset($data->sale->state->state_name) ? ucwords($data->sale->state->state_name) . ',' : ' ' }}
                            {{ isset($data->sale->customer_zipcode) ? $data->sale->customer_zipcode : ' ' }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="border-bottom: 1px solid; width:25%; padding-left: 0.5rem; height: 2rem; border-right: 1px solid; ">
                            MOBILE NO :
                        </td>
                        <td style="border-bottom: 1px solid; width:75%;text-align: center; height: 2rem;">
                            {{ isset($data->sale->customer_mobile_number) ? $data->sale->customer_mobile_number : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td style=" width:25%; padding-left: 0.5rem; height: 2rem; border-right: 1px solid; ">
                            HYP:
                        </td>
                        <td style=" width:75%;text-align: center; height: 2rem;font-weight:bold;">
                            {{ isset($data->account->financer) && $data->account->financer ? $data->sale->financer->bank_name : '' }}
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
                            style="border-right: 1px solid;width:20%;text-align: center; height: 2rem; border-bottom: 1px solid;">
                            MODEL & SKU :
                        </td>
                        <td
                            style="border-right: 1px solid;width:20%;text-align: center; height: 2rem; border-bottom: 1px solid;">
                            CHASIS NUMBER :
                        </td>
                        <td
                            style="border-right: 1px solid;width:20%;text-align: center; height: 2rem; border-bottom: 1px solid;">
                            ENGINE NUMBER :
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="border-right: 1px solid;width:20%;text-align: center; height: 2rem;font-weight:bold;font-size:14px;">
                            {{ isset($data->sale->purchase->model) && $data->sale->purchase->model->model_name ? $data->sale->purchase->model->model_name : '' }}
                            &
                            {{ isset($data->sale->purchase->sku) && $data->sale->purchase->sku ? $data->sale->purchase->sku : '' }}
                        </td>
                        <td
                            style="border-right: 1px solid;width:20%;text-align: center; height: 2rem;font-weight:bold;font-size:14px;">
                            {{ isset($data->sale->purchase->hsn_number) && $data->sale->purchase->hsn_number ? $data->sale->purchase->hsn_number : '' }}
                        </td>
                        <td
                            style="text-align: center; height: 2rem; border-right: 1px solid;font-weight:bold;font-size:14px;">
                            {{ isset($data->sale->purchase->engine_number) && $data->sale->purchase->engine_number ? $data->sale->purchase->engine_number : '' }}
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
                            style="border-right: 1px solid;width:20%;text-align: center; height: 2rem; border-bottom: 1px solid;">
                            PAY MODE :
                        </td>
                        <td
                            style="border-right: 1px solid;width:20%;text-align: center; height: 2rem; border-bottom: 1px solid;">
                            PAYMENT NAME :
                        </td>
                        <td
                            style="border-right: 1px solid;width:20%;text-align: center; height: 2rem; border-bottom: 1px solid;">
                            REFERENCE/UTR/CHQ/NO :
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="border-right: 1px solid;width:20%;text-align: center; height: 2rem;font-weight:bold;font-size:14px;">
                            {{ isset($data->amount_paid_source) && $data->amount_paid_source ? $data->amount_paid_source : '' }}
                        </td>
                        <td
                            style="border-right: 1px solid;width:20%;text-align: center; height: 2rem;font-weight:bold;font-size:14px;">
                            {{ isset($data->emi_title) && $data->emi_title ? $data->emi_title : '' }}
                        </td>
                        <td style="text-align: center; height: 2rem; border-right: 1px solid;font-size:14px;">
                            {{ isset($data->amount_paid_note) && $data->amount_paid_note ? $data->amount_paid_note : '' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="text-align: left; border: 1px solid;width:100%; height: 2rem;" colspan="2">
                <table style="width:100%;border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid;">
                        <td style="width:33%;  text-align: center; height: 2rem; padding-left: 0.5rem;" colspan="2">
                            TOTAL AMOUNT
                        </td>
                        <td
                            style="width:60%; border-left: 1px solid; text-align: center; height: 2rem;  padding-left: 0.5rem; font-weight: bold; color: black;">
                            {{ isset($data->amount_paid) && $data->amount_paid ? $data->amount_paid : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width:33%;  text-align: center; height: 2rem; padding-left: 0.5rem;">
                            AMOUNT IN WORD
                        </td>
                        <td style="text-align: center; height: 2rem;  padding-left: 0.5rem;"></td>
                        <td
                            style="width:70%; border-left: 1px solid; text-align: center; height: 2.5rem; padding-left: 0.5rem;">

                            {{ isset($data->amount_paid) && $data->amount_paid ? number2WordConvert($data->amount_paid) : '' }}
                        </td>
                        <td style="text-align: center; height: 2rem;  padding-left: 0.5rem;"></td>
                    </tr>

                </table>
            </td>
        </tr>

        <tr>
            <td style="text-align: left; width:70%;padding: 50px 15px; height: 2rem;">
                <table style="border-collapse: collapse;">
                    <h2>
                        Terms & Conditions
                    </h2>
                    <tr>
                        <td style="height: 2rem;" colspan="2">
                            1. E & O.E.
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 2rem;" colspan="2">
                            2. All disputes are subjected to the jurisdiction of courts of law at KHANDWA
                        </td>
                    </tr>
                    <tr>
                        <td style=" height: 2rem;" colspan="2">
                            3. Prices as applicable at the time of purchase
                        </td>
                    </tr>
                    <tr>
                        <td style=" height: 2rem;" colspan="2">
                            4. Delivery on cheque & Draft will be after realisation only
                        </td>
                    </tr>

                </table>
            </td>
            <td style="width: 30%; height: 2rem;border: 1px solid;">
                <table>
                    <tr style="text-align: center;">
                        <td style="text-align: center; height: 2rem;padding-left: 45px;">
                            {{ isset($data->sale->branch) ? $data->sale->branch->branch_name : 'No Branch' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; height: 2rem;padding-left: 45px;padding-top:100px;">
                            Signature
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!-- ./wrapper -->
</body>

</html>
