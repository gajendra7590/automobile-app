<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Aakash Automobile | Invoice</title>
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
            <td style="text-align: left;border: 1px solid; padding-left: 0.5rem; height: 2rem;" colspan="2">
                GSTIN : {{ isset($data->branch) ? $data->branch->gstin_number : ' ' }}
            </td>
        </tr>
        <tr>
            <td style="text-align: center; border: 1px solid;font-size: 20px;font-weight: 700; height: 2rem;">
                Quotation
            </td>
            <td style="text-align: left;border: 1px solid; height: 2rem; ">
                <table style="border-collapse: collapse; ">
                    <tr style="border-bottom: 1px solid; ">
                        <td style="height: 2rem;  padding-left: 0.5rem; padding-right: 5rem;font-weight: bold;">Q. NO
                        </td>
                        <td style="border-left: 1px solid; height: 2rem;  padding-left: 0.5rem; padding-right: 5rem;">
                            {{ isset($data) ? $data->uuid : ' ' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 2rem;  padding-left: 0.5rem; padding-right: 5rem;font-weight: bold;">DATE
                        </td>
                        <td style="border-left: 1px solid; height: 2rem;  padding-left: 0.5rem; padding-right: 5rem;">
                            {{ isset($data) ? date('d/m/Y', strtotime($data->created_at)) : ' ' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>


        <tr>
            <td style="width:100%;" colspan="2">
                <table style="width:100%;border-collapse: collapse;">
                    <tr>
                        <td style="width:25%; padding-left: 0.5rem; height: 2rem;">NAME</td>
                        <td style="border-left: 1px solid;width:75%;text-align: center; height: 2rem;">
                            {{ isset($data->cust_name) ? $data->cust_name : ' ' }}
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid;">
                        <td style="width:25%; padding-left: 0.5rem; height: 2rem;">ADDRESS : </td>
                        <td style="border-left: 1px solid;width:75%;text-align: center; height: 2rem;">
                            {{ isset($data->customer_address_line) ? $data->customer_address_line . ',' : ' ' }}
                            {{ isset($data->city->city_name) ? $data->city->city_name . ',' : ' ' }}
                            {{ isset($data->district->district_name) ? $data->district->district_name . ',' : ' ' }}
                            {{ isset($data->state->state_name) ? $data->state->state_name . ',' : ' ' }}
                            {{ isset($data->customer_zipcode) ? $data->customer_zipcode : ' ' }}
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid;">
                        <td style="width:25%; padding-left: 0.5rem; height: 2rem;">MOBILE NO : </td>
                        <td style="border-left: 1px solid;width:75%;text-align: center; height: 2rem;">
                            {{ isset($data->customer_mobile_number) ? $data->customer_mobile_number : '' }}
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid;">
                        <td style="width:25%; padding-left: 0.5rem; height: 2rem;">HYP:</td>
                        <td style="border-left: 1px solid;width:75%;text-align: center; height: 2rem;">
                            {{ isset($data->financer) && $data->financer ? $data->financer->bank_name : '' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="text-align: left;border: 1px solid;width:100%; height: 2rem; " colspan="2">
                <table style="width:100%;border-collapse: collapse;">
                    <tr>
                        <td style="border-right: 1px solid;width:25%;text-align: center; height: 2rem;">
                            BRAND</td>
                        <td style="border-right: 1px solid;width:25%;text-align: center; height: 2rem;">
                            MODEL</td>
                        <td style="border-right: 1px solid;width:25%;text-align: center; height: 2rem;">
                            COLOR</td>
                        <td style="width:30%;text-align: center;height: 2rem;">
                            PAY MODE</td>
                    </tr>
                    <tr style="border-top: 1px solid;">
                        <td style="border-right: 1px solid;width:25%;text-align: center; height: 2rem;">
                            {{ isset($data->branch) && $data->branch ? $data->branch->branch_name : '' }}</td>
                        <td style="border-right: 1px solid;width:25%;text-align: center; height: 2rem;">
                            {{ isset($data->model) && $data->model ? $data->model->model_name : '' }}</td>
                        <td style="border-right: 1px solid;width:25%;text-align: center; height: 2rem;">
                            {{ isset($data->color) && $data->color ? $data->color->color_name : '' }}</td>
                        <td style="width:25%;text-align: center; height: 2rem;">
                            {{ isset($data->payment_type_accessor) ? $data->payment_type_accessor : '' }}</td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="text-align: left; border: 1px solid;width:100%; height: 2rem;" colspan="2">
                <table style="width:100%;border-collapse: collapse;">
                    <tr style="width: 100%;">
                        <td style="border-bottom: 1px solid; text-align: left; height: 2rem; padding-left: 0.5rem;">
                            EX SHOWROOM PRICE</td>
                        <td style="border-left: 1px solid; border-bottom: 1px solid; text-align: center; height: 2rem;  padding-left: 0.5rem;">
                            {{ isset($data->ex_showroom_price) ? $data->ex_showroom_price : '' }}</td>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid; text-align: left; height: 2rem;  padding-left: 0.5rem;">
                            REGISTRATION AMOUNT</td>
                        <td style="border-left: 1px solid; border-bottom: 1px solid; text-align: center; height: 2rem;  padding-left: 0.5rem;">
                            {{ isset($data->registration_amount) ? $data->registration_amount : '' }}</td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid; text-align: left; height: 2rem;  padding-left: 0.5rem;">
                            INSURANCE AMOUNT</td>
                        <td style="border-left: 1px solid; border-bottom: 1px solid; text-align: center; height: 2rem; padding: 0rem 0.5rem 0rem 0.5rem;">
                            {{ isset($data->insurance_amount) ? $data->insurance_amount : '' }}</td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid; text-align: left; height: 2rem;  padding-left: 0.5rem;">
                            HYPOTHECATION AMOUNT</td>
                        <td style="border-left: 1px solid; border-bottom: 1px solid; text-align: center; height: 2rem;">
                            {{ isset($data->hypothecation_amount) ? $data->hypothecation_amount : '' }}</td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid; text-align: left; height: 2rem;  padding-left: 0.5rem;">
                            ACCESSORIES AMOUNT</td>
                        <td style="border-left: 1px solid; border-bottom: 1px solid;  text-align: center; height: 2rem;">
                            {{ isset($data->accessories_amount) ? $data->accessories_amount : '' }}</td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid; text-align: left; height: 2rem;  padding-left: 0.5rem;">
                        OTHER CHARGES</td>
                        <td style="border-left: 1px solid; border-bottom: 1px solid; text-align: center; height: 2rem;">
                            {{ isset($data->other_charges) ? $data->other_charges : '' }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: left; height: 2rem;  padding-left: 0.5rem;font-weight: bold;">TOTAL
                            AMOUNT</td>
                        <td style="border-left: 1px solid; text-align: center; height: 2rem;font-weight: bold;">&#8377;
                            {{ isset($data->total_amount) ? $data->total_amount : '' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="text-align: left;border: 1px solid;width:100%;padding: 50px 15px; height: 2rem;">
                <table style="width:70%;border-collapse: collapse;">
                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid ;text-align: center; height: 2rem;" colspan="2">
                            BANK DETAIL
                        </td>
                    </tr>
                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid; text-align: center; height: 2rem;" colspan="2">
                            A/C : 1212121211212
                        </td>
                    </tr>
                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid; text-align: center; height: 2rem;" colspan="2">
                            IFSC CODE: 454daasdsad
                        </td>
                    </tr>
                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid; text-align: center; height: 2rem;" colspan="2">
                            BANK : BANK OF INDIA
                        </td>
                    </tr>
                    <tr style="border: 1px solid;">
                        <td style="border: 1px solid; text-align: center; height: 2rem;" colspan="2">
                            BRANCH : SINGOT
                        </td>
                    </tr>
                </table>
            </td>
            <td style="padding-left: 5rem; height: 2rem;">
                <table>
                    <tr style="border: 1px solid; text-align: center; height: 2rem;">
                        AKASH MOTORS
                    </tr>
                    <tr style="border: 1px solid;">
                        <td style="text-align: center; height: 2rem;">
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
