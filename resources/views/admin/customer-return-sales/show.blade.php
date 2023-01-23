<table class="table table-bordered">
    <tr>
        <th>SALESMAN</th>
        <td>{{ isset($data['salesman']['name']) ? $data['salesman']['name'] : '--' }}</td>
        <th>BRANCH</th>
        <td colspan="3">{{ isset($data['branch']['branch_name']) ? $data['branch']['branch_name'] : '--' }}</td>
    </tr>
    <tr>
        <th>#ID</th>
        <td>{{ isset($data['id']) ? leadingZero($data['id'], 6) : '--' }}</td>
        <th>QUOTATION ID</th>
        <td>{{ isset($data['quotation']['id']) ? leadingZero($data['quotation']['id'], 6) : '--' }}</td>
        <th>CUST NAME</th>
        <td colspan="3">{{ isset($data['cust_name']) ? $data['cust_name'] : '--' }}</td>
    </tr>
    <tr>
        <th>CUST ADDRESS LINE</th>
        <td>{{ isset($data['customer_address_line']) ? $data['customer_address_line'] : '--' }}</td>
        <th>CUST CITY</th>
        <td>{{ isset($data['city']['city_name']) ? $data['city']['city_name'] : '--' }}</td>
        <th>CUST DISTRICT</th>
        <td>{{ isset($data['district']['district_name']) ? $data['district']['district_name'] : '--' }}</td>
    </tr>
    <tr>
        <th>CUST STATE</th>
        <td>{{ isset($data['state']['state_name']) ? $data['state']['state_name'] : '--' }}</td>
        <th>CUST ZIPCODE</th>
        <td>{{ isset($data['customer_zipcode']) ? $data['customer_zipcode'] : '--' }}</td>
        <th>CUST PHONE NUMBER</th>
        <td>{{ isset($data['customer_mobile_number']) ? $data['customer_mobile_number'] : '--' }}</td>
    </tr>
    <tr>
        <th>CUST ALTERNATE PHONE NUMBER</th>
        <td>{{ isset($data['customer_mobile_number_alt']) ? $data['customer_mobile_number_alt'] : '--' }}</td>
        <th>CUST EMAIL ADDRESS</th>
        <td>{{ isset($data['customer_email_address']) ? $data['customer_email_address'] : '--' }}</td>
        <th>IS EXCHANGE</th>
        <td>{{ isset($data['is_exchange_avaliable']) ? $data['is_exchange_avaliable'] : '--' }}</td>
    </tr>
    <tr>
        <th>WITNESS PERSON NAME</th>
        <td colspan="2">{{ isset($data['witness_person_name']) ? $data['witness_person_name'] : '--' }}</td>
        <th>WITNESS PERSON PHONE</th>
        <td colspan="2">{{ isset($data['witness_person_phone']) ? $data['witness_person_phone'] : '--' }}</td>
    </tr>
    <tr>
        <th>PAYMENT MODE</th>
        <td>{{ isset($data['payment_type']) ? duePaySources($data['payment_type']) : '--' }}</td>
        <th>HYP FINANCER</th>
        <td>{{ isset($data['financer']['bank_name']) ? $data['financer']['bank_name'] : '--' }}</td>
        <th>HYP FINANCER DESCRIPTION</th>
        <td>{{ isset($data['hyp_financer_description']) ? $data['hyp_financer_description'] : '--' }}
        </td>
    </tr>
    <tr>
        <th>BRAND NAME</th>
        <td>{{ isset($data['purchases']['brand']['name']) ? $data['purchases']['brand']['name'] : '--' }}</td>
        <th>MODEL NAME</th>
        <td>{{ isset($data['purchases']['model']['model_name']) ? $data['purchases']['model']['model_name'] : '--' }}
        </td>
        <th>COLOR NAME</th>
        <td>{{ isset($data['purchases']['color']['color_name']) ? $data['purchases']['color']['color_name'] : '--' }}
        </td>
    </tr>
    <tr>
        <th>EX SHOWROOM PRICE</th>
        <td>{{ isset($data['ex_showroom_price']) ? priceFormate($data['ex_showroom_price']) : '--' }}</td>
        <th>REGISTRATION AMOUNT</th>
        <td>{{ isset($data['registration_amount']) ? priceFormate($data['registration_amount']) : '--' }}</td>
        <th>INSURANCE AMOUNT</th>
        <td>{{ isset($data['insurance_amount']) ? priceFormate($data['insurance_amount']) : '--' }}</td>
    </tr>
    <tr>
        <th>HYPOTHECATION AMOUNT</th>
        <td>{{ isset($data['hypothecation_amount']) ? priceFormate($data['hypothecation_amount']) : '--' }}</td>
        <th>ACCESSORIES AMOUNT</th>
        <td>{{ isset($data['accessories_amount']) ? priceFormate($data['accessories_amount']) : '--' }}</td>
        <th>OTHER CHARGES</th>
        <td>{{ isset($data['other_charges']) ? priceFormate($data['other_charges']) : '--' }}</td>
    </tr>
    <tr>
        <th colspan="2">TOTAL PAID BY CUSTOMER</th>
        <td>{!! isset($total_paid) ? convertBadgesPrice($total_paid, 'success') : convertBadgesPrice(0.0, 'success') !!}</td>
        <th colspan="2">TOTAL REFUND TO CUSTOMER</th>
        <td>{!! isset($total_refund) ? convertBadgesPrice($total_refund, 'warning') : convertBadgesPrice(0.0, 'warning') !!}</td>
    </tr>
</table>
