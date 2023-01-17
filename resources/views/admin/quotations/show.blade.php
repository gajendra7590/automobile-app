<table class="table table-bordered">
    <tr>
        <th>SALESMAN</th>
        <td>{{ isset($data['salesman']['name']) ? $data['salesman']['name'] : '--' }}</td>
        <th>BRANCH NAME</th>
        <td colspan="3">{{ isset($data['branch']['branch_name']) ? $data['branch']['branch_name'] : '--' }}</td>
    </tr>
    <tr>
        <th>#ID</th>
        <td>{{ isset($data['id']) ? leadingZero($data['id'], 6) : '--' }}</td>
        <th>CUSTOMER NAME</th>
        <td colspan="3">{{ isset($data['cust_name']) ? $data['cust_name'] : '--' }}</td>
    </tr>
    <tr>
        <th>CUSTOMER ADDRESS LINE</th>
        <td>{{ isset($data['customer_address_line']) ? $data['customer_address_line'] : '--' }}</td>
        <th>CUSTOMER CITY</th>
        <td>{{ isset($data['city']['city_name']) ? $data['city']['city_name'] : '--' }}</td>
        <th>CUSTOMER DISTRICT</th>
        <td>{{ isset($data['district']['district_name']) ? $data['district']['district_name'] : '--' }}</td>
    </tr>
    <tr>
        <th>CUSTOMER STATE</th>
        <td>{{ isset($data['state']['state_name']) ? $data['state']['state_name'] : '--' }}</td>
        <th>CUSTOMER ZIPCODE</th>
        <td>{{ isset($data['customer_zipcode']) ? $data['customer_zipcode'] : '--' }}</td>
        <th>CUSTOMER PHONE NUMBER</th>
        <td>{{ isset($data['customer_mobile_number']) ? $data['customer_mobile_number'] : '--' }}</td>
    </tr>

    <tr>
        <th>CUSTOMER ALTERNATE PHONE NUMBER</th>
        <td>{{ isset($data['customer_mobile_number_alt']) ? $data['customer_mobile_number_alt'] : '--' }}</td>
        <th>CUSTOMER EMAIL ADDRESS</th>
        <td>{{ isset($data['customer_email_address']) ? $data['customer_email_address'] : '--' }}</td>
        <th>IS EXCHANGE</th>
        <td>{{ isset($data['is_exchange_avaliable']) ? $data['is_exchange_avaliable'] : '--' }}</td>
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
        <th>SHOWROOM VISIT DATE</th>
        <td colspan="2">{{ isset($data['purchase_visit_date']) ? $data['purchase_visit_date'] : '--' }}</td>
        <th>PURCHASE EST DATE</th>
        <td colspan="2">{{ isset($data['purchase_est_date']) ? $data['purchase_est_date'] : '--' }}</td>
    </tr>
    <tr>
        <th>BRAND NAME</th>
        <td>{{ isset($data['brand']['name']) ? $data['brand']['name'] : '--' }}</td>
        <th>MODEL NAME</th>
        <td>{{ isset($data['model']['model_name']) ? $data['model']['model_name'] : '--' }}</td>
        <th>COLOR NAME</th>
        <td>{{ isset($data['color']['color_name']) ? $data['color']['color_name'] : '--' }}</td>
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
        <th>TOTAL AMOUNT</th>
        <td>{!! isset($data['total_amount']) ? convertBadgesPrice($data['total_amount'], 'success') : '--' !!}</td>
        <th>DESCRIPTION</th>
        <td>{{ isset($data['bike_description']) ? $data['bike_description'] : '--' }}</td>
    </tr>
    <tr>
        <th>CLOSED STATUS</th>
        <td>{!! isset($data['status']) ? convertBadgesStr($data['status']) : '--' !!}</td>

        <th>CLOSED BY</th>
        <td>{{ (isset($data['closedByUser']['name']) ? $data['closedByUser']['name'] : isset($data['status']) && $data['status'] == 'close') ? 'AUTO CLOSED' : '--' }}
        </td>

        <th>CLOSED NOTE</th>
        <td>{!! isset($data['close_note']) ? $data['close_note'] : '--' !!}</td>
    </tr>
</table>
