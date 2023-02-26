<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>TAX INVOICE</title>
</head>

<body>
    <table style="border:1px solid; width:100%;">
        <tr style="border:1px solid;">
            <td style="text-align: left;" colspan="2">

                <div style="align-content: center;">
                    {{-- <img src="https://autoshop.systrailsolutions.com/uploads/gurukripa-agency-20230226132845.png"
                        style="height: 51px;margin-top:5px;"> --}}
                    <img src="{{ isset($data->branch) ? strtoupper($data->branch->branch_logo) : '' }}"
                        style="height: 51px;width: 100px;">
                    <span style="margin-left: 180px !important;font-size:20px;">
                        <b>TAX INVOICE</b>
                    </span>
                </div>
            </td>
        </tr>
        <tr>
            <td style="width:100%;" colspan="2">
                <table style="width:100%;border:1px solid;border-collapse: collapse;">
                    <tr style="border-right: 1px solid; border-bottom: none;" colspan="2">
                        <td style="width: 50%;height: 2rem; font-weight: 700;padding-left:5px;">
                            {{ isset($data->branch) ? $data->branch->branch_name : 'No Branch' }}
                        </td>
                        <td
                            style="width: 25%;border-left: 1px solid;height: 1rem; font-size: 12px; font-weight: 800;padding-left:5px;">
                            <b>INVOICE NO.</b>
                        </td>
                        <td style="width: 25%; border-left: 1px solid;height: 2rem;font-size: 12px;padding-left:5px;">
                            <b>DATES</b>
                        </td>
                    </tr>
                    <tr style="border-right: 1px solid;border-bottom: none;" colspan="2">
                        <td style="width: 50%; height: 2rem;padding-left:5px;">
                            {{ isset($data->branch) ? $data->branch->branch_address_line : ' ' }}
                            {{ isset($data->branch) ? $data->branch->branch_pincode : ' ' }}
                        </td>
                        <td
                            style="width: 25%;border-left: 1px solid; height: 2rem; font-size: 12px; border-bottom: 1px solid;padding-left:5px;">
                            {{ isset($data->year) ? $data->year : date('Y') }}/{{ isset($data->sno) ? leadingZero($data->sno, 6) : 0 }}
                        </td>
                        <td
                            style="width: 25%; border-left: 1px solid; height: 2rem; border-bottom: 1px solid;padding-left:5px;">
                            {{ isset($data) ? date('d/m/Y', strtotime($data->created_at)) : ' ' }}
                        </td>
                    </tr>
                    <tr style="border-right: 1px solid;border-bottom: none; " colspan="2">
                        <td style="width: 50%; height: 2rem;font-size: 12px;padding-left:5px;">
                            <b>GSTIN/UIN</b>: {{ isset($data->branch) ? $data->branch->gstin_number : ' ' }}
                        </td>
                        <td
                            style="width: 25%;border-left: 1px solid; height: 2rem; font-size: 12px; border-bottom: 1px solid;padding-left:5px;">
                            <b>DELIVERY DATE</b>
                        </td>
                        <td
                            style="width: 25%; border-left: 1px solid; height: 2rem; border-bottom: 1px solid;font-size: 12px;padding-left:5px;">
                            <b>MODE/TERMS OF PAYMENT</b>
                        </td>
                    </tr>
                    <tr style="border-right: 1px solid;border-bottom: none; line-height: 0px;" colspan="2">
                        <td style="width: 50%; height: 2rem;font-size: 12px;padding-left:5px;">
                            <b>STATE NAME</b> :
                            {{ isset($data->state->state_name) ? ucwords($data->state->state_name) : ' ' }}, CODE
                            : {{ isset($data->state->id) ? ucwords($data->state->id) : ' ' }}
                        </td>
                        <td style="width: 25%;border-left: 1px solid; height: 2rem; font-size: 12px;padding-left:5px;">
                            <b> SUPPLIERS REF</b>
                        </td>
                        <td style="width: 25%; border-left: 1px solid; height: 2rem;font-size: 12px;padding-left:5px;">
                            <b>OTHER REFERENCE</b>
                        </td>
                    </tr>
                    <tr style="border-right: 1px solid;border-bottom: 1px solid;" colspan="2">
                        <td style="width: 50%; height: 2rem;font-size: 12px;padding-left:5px;">
                            <b>CONTACT</b> :
                            {{ isset($data->customer_mobile_number) ? $data->customer_mobile_number : '' }}
                        </td>
                        <td
                            style="width: 25%;border-left: 1px solid; height: 2rem; font-size: 12px; border-bottom: none;padding-left:5px;">
                        </td>
                        <td style="width: 25%; border-left: 1px solid; height: 2rem;padding-left:5px;"></td>
                    </tr>
                    <tr style="border-right: 1px solid;border-bottom: none; line-height: 0px;" colspan="2">
                        <td style="width: 50%; height: 2rem;font-size: 12px;padding-left:5px;">
                            <b>BUYER</b>
                        </td>
                        <td style="width: 25%;border-left: 1px solid; height: 2rem; font-size: 12px;padding-left:5px;">
                            <b>BUYERS ORDER NO</b>
                        </td>
                        <td style="width: 25%; border-left: 1px solid; height: 2rem;font-size: 12px;padding-left:5px;">
                            <b>DATES</b>
                        </td>
                    </tr>
                    <tr style="border-right: 1px solid;border-bottom: none; line-height: 0px;" colspan="2">
                        <td style="width: 50%; height: 1rem;font-size: 12px;padding-left:5px;">
                            <b>NAME</b>:{{ isset($data->customer_name) ? strtoupper($data->customer_name) : ' ' }}
                        </td>
                        <td
                            style="width: 25%;border-left: 1px solid; height: 1rem; font-size: 12px;;border-bottom: 1px solid;padding-left:5px;">
                        </td>
                        <td
                            style="width: 25%; border-left: 1px solid; height: 2rem; border-bottom: 1px solid;padding-left:5px;">
                        </td>
                    </tr>
                    <tr style="border-right: 1px solid; border-bottom: none;" colspan="2">
                        <td style="width: 50%; height: 1rem;font-size: 12px;padding-left:5px;">
                            <b>ADDRESS</b>:
                            {{ isset($data->city->city_name) ? ucwords($data->city->city_name) : ' ' }}
                        </td>
                        <td
                            style="width: 25%;border-left: 1px solid; height: 1rem; font-size: 12px;font-size: 12px;padding-left:5px;">
                            <b>DESPATCH DOCUMENT NO</b>
                        </td>
                        <td style="width: 25%; border-left: 1px solid; height: 1rem;font-size: 12px;padding-left:5px;">
                            <b>DELIVERY NOTE DATE</b>
                        </td>
                    </tr>
                    <tr style="border-right: 1px solid;border-bottom: none; line-height: 0px;" colspan="2">
                        <td style="width: 50%; height: 1rem;font-size: 12px;padding-left:5px;">
                            <b>DIST</b>:
                            {{ isset($data->district->district_name) ? ucwords($data->district->district_name) : ' ' }}
                        </td>
                        <td
                            style="width: 25%;border-left: 1px solid; height: 1rem; font-size: 12px;border-bottom: 1px solid;padding-left:5px;">
                        </td>
                        <td
                            style="width: 25%; border-left: 1px solid;  height: 1rem; border-bottom: 1px solid;padding-left:5px;">
                        </td>
                    </tr>
                    <tr style="border-right: 1px solid; border-bottom: none;" colspan="2">
                        <td style="width: 50%;  height: 1rem;font-size: 12px;padding-left:5px;">
                            <b>MO</b> :
                            {{ isset($data->customer_mobile_number) ? $data->customer_mobile_number : '' }}
                        </td>
                        <td
                            style="width: 25%;border-left: 1px solid;height: 1rem; font-size: 12px;font-size: 12px;padding-left:5px;">
                            <b>DESPATCHED THROUGH</b>
                        </td>
                        <td
                            style="width: 25%; border-left: 1px solid;border-top: none;height: 1rem;font-size: 12px;padding-left:5px;">
                            <b>DESTINATION</b>
                        </td>
                    </tr>
                    <tr style="border-right: 1px solid; border-bottom: none;" colspan="2">
                        <td style="width: 50%;  height: 1rem;font-size: 12px;padding-left:5px;">
                            <b>GSTIN</b>:
                        </td>
                        <td
                            style="width: 25%;border-left: 1px solid;  height: 1rem; font-size: 12px; border-bottom: 1px solid;padding-left:5px;">
                        </td>
                        <td
                            style="width: 25%; border-left: 1px solid;  height: 1rem; border-bottom: 1px solid;padding-left:5px;">
                        </td>
                    </tr>
                    <tr style="border-right: 1px solid; border-bottom: none;" colspan="2">
                        <td style="width: 50%;  height: 1rem;font-size: 12px;padding-left:5px;">
                            <b>STATE NAME</b>:
                            {{ isset($data->state->state_name) ? strtoupper($data->state->state_name) : ' ' }}
                        </td>
                        <td
                            style="width: 25%;border-left: 1px solid;height: 1rem; font-size: 12px; border-right: 1px; solid;padding-left:5px;">
                            <b>TERMS OF DELIVERY</b>
                        </td>
                        <td style="width: 25%; border-left: 1px solid;  height: 1rem;padding-left:5px;"></td>
                    </tr>
                    <tr style="border-right: 1px solid; border-bottom: none;" colspan="2">
                        <td style="width: 50%;  height: 1rem;font-size: 12px;padding-left:5px;">
                            <b>HYP</b>:
                            {{ isset($data->financer) && $data->financer ? strtoupper($data->financer->bank_name) : '' }}
                        </td>
                        <td style="width: 25%;border-left: 1px solid;height: 1rem; font-size: 12px;padding-left:5px;">
                        </td>
                        <td style="width: 25%; border-left: 1px solid;height: 1rem;padding-left:5px;"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;border: 1px solid;width:100%; height: 2rem; " colspan="3">
                @php
                    $exShowroomPrice = isset($data->ex_showroom_price) ? floatval($data->ex_showroom_price) : 0.0;
                    $otherCharges = isset($data->other_charges) ? floatval($data->other_charges) : 0.0;
                    $chargesWithoutDiscount = floatval($data->exShowroomPrice);
                @endphp
                @if ($otherCharges < 0)
                    @php
                        $chargesWithoutDiscount = floatval($exShowroomPrice + $otherCharges);
                    @endphp
                @endif
                @php
                    $sgst_rate_per = isset($data->purchase->gst_detail) && $data->purchase->gst_detail->sgst_rate ? $data->purchase->gst_detail->sgst_rate : 14;
                    $cgst_rate_per = isset($data->purchase->gst_detail) && $data->purchase->gst_detail->cgst_rate ? $data->purchase->gst_detail->cgst_rate : 14;
                    $chargesWithoutDiscount = floatval($data->ex_showroom_price);
                    $sgst_amount = floatval(($sgst_rate_per / 100) * $chargesWithoutDiscount);
                    $cgst_amount = floatval(($cgst_rate_per / 100) * $chargesWithoutDiscount);
                    $grand_total = floatval($chargesWithoutDiscount + $sgst_amount + $cgst_amount);
                @endphp
                <table style="width:100%;border-collapse: collapse;">
                    <tr>
                        <td
                            style="border-right: 1px solid;width:6%;text-align: center; height: 2rem; border-bottom: 1px solid;font-size:13px;">
                            <b>SI NO.</b>
                        </td>
                        <td
                            style="border-right: 1px solid;width:25%;text-align: center; height: 2rem; border-bottom: 1px solid;border-right: none;font-size:13px;">
                            <b>DESCRIPTION OF GOODS</b>
                        </td>
                        <td
                            style="border-right: 1px solid;width:10%;text-align: center; height: 2rem; border-bottom: 1px solid;font-size:13px;">
                        </td>
                        <td
                            style="border-right: 1px solid;width:15%;text-align: center; height: 2rem; border-bottom: 1px solid;font-size:13px;">
                            <b>HSN/SAC</b>
                        </td>
                        <td
                            style="border-right: 1px solid; width:5%; text-align: center; height: 2rem; border-bottom: 1px solid;font-size:13px;">
                            <b>QTY</b>
                        </td>
                        <td
                            style="text-align: center;width:10%; height: 2rem;  border-bottom: 1px solid; border-right: 1px solid;font-size:13px;">
                            <b>RATE</b>
                        </td>
                        <td
                            style="text-align: center;width:6%;border-collapse: collapse; border-bottom: 1px solid; border-right: 1px solid;font-size:13px;">
                            <b>PER</b>
                        </td>
                        <td
                            style="text-align: center; border-collapse: collapse; width: 10%; border-bottom: 1px solid; width:10%;font-size:13px;">
                            <b>AMOUNT</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid;width:5%; height: 2rem;text-align: center;">1</td>
                        <td
                            style="border-right: 1px solid;width:25%; height: 2rem;border-right: none;padding-left: 6px;font-size: 13px;">
                            <b>SKU</b>:
                            {{ isset($data->purchase->sku) && $data->purchase->sku ? $data->purchase->sku : '' }}
                        </td>
                        <td style="border-right: 1px solid;width:5%; height: 2rem;"></td>
                        <td style=" height: 2rem; border-right: 1px solid;text-align: center;">
                            {{ isset($data->purchase->hsn_number) && $data->purchase->hsn_number ? $data->purchase->hsn_number : '' }}
                        </td>
                        <td style=" height: 2rem; border-right: 1px solid;text-align: center;">1</td>
                        <td style=" height: 2rem; border-right: 1px solid;text-align: center;">
                            {{ number_format($exShowroomPrice, 2) }}
                        </td>
                        <td style=" height: 2rem; border-right: 1px solid;text-align: center;">
                            <i>NOS</i>
                        </td>
                        <td style=" height: 2rem; text-align: center;">
                            {{ number_format($exShowroomPrice, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid;width:5%; height: 2rem;text-align: center;"></td>
                        <td
                            style="border-right: 1px solid;width:25%; height: 2rem;border-right: none;padding-left: 6px;font-size: 13px;">
                            <b>MODEL</b>:
                            {{ isset($data->purchase->model) && $data->purchase->model->model_name ? $data->purchase->model->model_name : '' }}
                        </td>
                        <td style="border-right: 1px solid;width:5%; height: 2rem;"></td>
                        <td style="text-align: center; height: 2rem; border-right: 1px solid;"></td>
                        <td style="height: 2rem; border-right: 1px solid;"></td>
                        <td style="height: 2rem; border-right: 1px solid;"></td>
                        <td style="height: 2rem; border-right: 1px solid;"></td>
                        <td style="height: 2rem;"></td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid;width:5%; height: 2rem;text-align: center;"></td>
                        <td
                            style="border-right: 1px solid;width:25%; height: 2rem;border-right: none;padding-left: 6px;font-size: 13px;">
                            <b>CHA</b>:
                            {{ isset($data->purchase->vin_number) && $data->purchase->vin_number ? $data->purchase->vin_number : '' }}
                        </td>
                        <td style="border-right: 1px solid;width:5%; height: 2rem;font-size: 13px;">
                            <i>DISCOUNT</i>
                        </td>
                        <td style=" height: 2rem; border-right: 1px solid;"></td>
                        <td style=" height: 2rem; border-right: 1px solid;"></td>
                        <td style=" height: 2rem; border-right: 1px solid;"></td>
                        <td style=" height: 2rem; border-right: 1px solid;"></td>
                        <td style=" height: 2rem;text-align: center;">
                            {{ $otherCharges < 0 ? number_format(-$otherCharges, 2) : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid;width:5%;height: 2rem;text-align: center;"></td>
                        <td
                            style="border-right: 1px solid;width:25%;height: 2rem;border-right: none;padding-left: 6px;font-size: 13px;">
                            <b>ENG</b>:
                            {{ isset($data->purchase->engine_number) && $data->purchase->engine_number ? $data->purchase->engine_number : '' }}
                        </td>
                        <td style="border-right: 1px solid;width:5%;height: 2rem;"></td>
                        <td style="height: 2rem; border-right: 1px solid;"> </td>
                        <td style="height: 2rem; border-right: 1px solid;"></td>
                        <td style="height: 2rem; border-right: 1px solid;"></td>
                        <td style="height: 2rem; border-right: 1px solid;"></td>
                        <td style="height: 2rem; border-botton: 1px solid;text-align: center;">
                            <b>{{ number_format($chargesWithoutDiscount, 2) }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid;width:5%; height: 2rem;text-align: center;"></td>
                        <td
                            style="border-right: 1px solid;width:25%; height: 2rem;border-right: none;padding-left: 6px;font-size: 13px;">
                            <b>COLOUR</b> :
                            {{ isset($data->purchase->color) && $data->purchase->color->color_name ? $data->purchase->color->color_name : '' }}
                        </td>
                        <td style="border-right: 1px solid;width:5%; height: 2rem;font-size: 13px;">
                            <i> SGST@
                                {{ $sgst_rate_per }}%</i>
                        </td>
                        <td style=" height: 2rem; border-right: 1px solid;"> </td>
                        <td style=" height: 2rem; border-right: 1px solid;"></td>
                        <td style=" height: 2rem; border-right: 1px solid;"></td>
                        <td style=" height: 2rem; border-right: 1px solid;"></td>
                        <td style="border-top: 1px solid;border-top: 1px solid;text-align: center;">
                            {{ number_format($sgst_amount, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid;width:5%; height: 2rem;text-align: center;"></td>
                        <td
                            style="border-right: 1px solid;width:25%; height: 2rem;border-right: none;padding-left: 6px;font-size: 13px;">
                        </td>
                        <td style="border-right: 1px solid;width:5%; height: 2rem;font-size: 13px;">
                            <i>
                                CGST@
                                {{ $cgst_rate_per }}%
                            </i>
                        </td>
                        <td style=" height: 2rem; border-right: 1px solid;text-align: center;"></td>
                        <td style=" height: 2rem; border-right: 1px solid;"></td>
                        <td style=" height: 2rem; border-right: 1px solid;"></td>
                        <td style=" height: 2rem; border-right: 1px solid;"></td>
                        <td style="width:5%; height: 2rem;text-align: center;">
                            {{ number_format($cgst_amount, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid;width:5%; height: 2rem;text-align: center;"></td>
                        <td
                            style="border-right: 1px solid;width:25%; height: 2rem;border-right: none;padding-left: 6px;font-size: 13px;">
                        </td>
                        <td style="border-right: 1px solid;width:5%; height: 2rem;font-size: 13px;">
                            <i>ROUNDING </i>
                        </td>
                        <td style="height: 2rem; border-right: 1px solid;text-align: center;"></td>
                        <td style="height: 2rem; border-right: 1px solid;"></td>
                        <td style="height: 2rem; border-right: 1px solid;"></td>
                        <td style="height: 2rem; border-right: 1px solid;"></td>
                        <td style="height: 2rem;text-align: center;">
                            {{ number_format($grand_total, 2) }}
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid;text-align: center;">
                        <td style="border-right: 1px solid;width:5%;height: 2rem;"></td>
                        <td style="border-right: 1px solid;width:25%;height: 2rem;border-right: none;font-size:13px;">
                            <b>TOTAL</b>
                        </td>
                        <td style="border-right: 1px solid;width:5%;height: 2rem;text-align: center;"></td>
                        <td style="height: 2rem; border-right: 1px solid;"> </td>
                        <td style="height: 2rem; border-right: 1px solid;font-size:13px;">
                            <b>1</b>
                        </td>
                        <td style="height: 2rem; border-right: 1px solid;"></td>
                        <td style="height: 2rem; border-right: 1px solid;"></td>
                        <td style="height: 2rem; border-right: none;text-align: center;">
                            <b>{{ number_format($grand_total, 2) }}</b>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; width:80%;height: 2rem;padding-left: 6px;font-size: 13px;">
                AMOUNT CHARGABLE(IN WORDS) : <b>{{ number2WordConvert($grand_total) }}</b>
            </td>
            <td style="text-align: right;height: 2rem; width: 20%;padding-right: 13px;">
                <i>E. & O.E</i>
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid;" colspan="2">
                <b><u style="padding-left: 6px;font-size: 13px;">DECLARATION</u></b> :
                <p style="padding-left: 6px;">
                    We declare that this invoice shows the actual price of the goods described and that all
                    particulars are true and correct
                </p>
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid;text-align: center;padding: 7px 0px 0px;" width="50%">
                Customer's Seal and Signature
            </td>
            <td style="border-bottom: 1px solid;border-left: 1px solid;text-align: center;padding: 7px 0px 0px;"
                width="50%">
                <b>{{ isset($data->branch) ? strtoupper($data->branch->branch_name) : '' }}</b>
                <p>
                    Signature
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 6px;text-align: center;font-size:13px;" colspan="2">
                SUBJECT TO SUBJECT TO KHANDWA JURISDICTION JURISDICTION
                <br>
                This is a Computer Generated Invoice
            </td>
        </tr>
    </table>
</body>

</html>
