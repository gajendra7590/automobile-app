  <table class="table table-bordered">
      <tr>
          <th>BRANCH NAME</th>
          <td> {{ isset($data['sale']['branch']['branch_name']) ? $data['sale']['branch']['branch_name'] : '--' }}
          </td>
          <th>AGENT NAME</th>
          <td> {{ isset($data['agent']['agent_name']) ? $data['agent']['agent_name'] : '--' }}</td>
          <th>SALES ID</th>
          <td>{{ isset($data['sale']['sale_uuid']) ? $data['sale']['sale_uuid'] : '--' }}</td>
      </tr>
      <tr>
          <th>CONTACT NAME</th>
          <td>{{ isset($data['contact_name']) ? $data['contact_name'] : '--' }}</td>
          <th>CONTACT MOBILE NUMBER</th>
          <td>{{ isset($data['contact_mobile_number']) ? $data['contact_mobile_number'] : '--' }}</td>
          <th>CONTACT ADDRESS LINE</th>
          <td>{{ isset($data['contact_address_line']) ? $data['contact_address_line'] : '--' }}</td>
      </tr>
      <tr>
          <th>STATE NAME</th>
          <td> {{ isset($data['state']['state_name']) ? $data['state']['state_name'] : '--' }}
          </td>
          <th>DISTRICT NAME</th>
          <td> {{ isset($data['district']['district_name']) ? $data['district']['district_name'] : '--' }}</td>
          <th>CITY NAME</th>
          <td>{{ isset($data['city']['city_name']) ? $data['city']['city_name'] : '--' }}</td>
      </tr>
      <tr>
          <th>ZIP CODE</th>
          <td> {{ isset($data['contact_zipcode']) ? $data['contact_zipcode'] : '--' }}</td>
          <th>SKU</th>
          <td> {{ isset($data['sku']) ? $data['sku'] : '--' }}</td>
          <th>FINANCER NAME</th>
          <td>{{ isset($data['financer_name']) ? $data['financer_name'] : '--' }}</td>
      </tr>
      <tr>
          <th>EX SHOWROOM PRICE</th>
          <td> {{ isset($data['ex_showroom_amount']) ? priceFormate($data['ex_showroom_amount']) : '--' }}
          </td>
          <th>TAX AMOUNT</th>
          <td> {{ isset($data['tax_amount']) ? priceFormate($data['tax_amount']) : '--' }}</td>
          <th>HYP AMOUNT</th>
          <td>{{ isset($data['hyp_amount']) ? priceFormate($data['hyp_amount']) : '--' }}</td>
      </tr>
      <tr>
          <th>TR AMOUNT</th>
          <td> {{ isset($data['tr_amount']) ? priceFormate($data['tr_amount']) : '--' }}
          </td>
          <th>FEES</th>
          <td> {{ isset($data['fees']) ? priceFormate($data['fees']) : '--' }}</td>
          <th>GRAND TOTAL</th>
          <td>{{ isset($data['total_amount']) ? priceFormate($data['total_amount']) : '--' }}</td>
      </tr>

      <tr>
          <th>REMARK</th>
          <td colspan="5">
              {{ isset($data['remark']) ? $data['remark'] : '--' }}
          </td>
      </tr>

      <tr>
          <th>RC NUMBER</th>
          <td> {{ isset($data['rc_number']) ? $data['rc_number'] : '--' }}
          </td>
          <th>RC STATUS</th>
          <td>
              @if (isset($data['rc_status']) && $data['rc_status'] == '1')
                  Yes
              @elseif (isset($data['rc_status']) && $data['rc_status'] == '0')
                  NO
              @endif
          </td>
          <th>SUBMIT DATE</th>
          <td>{{ isset($data['submit_date']) ? myDateFormate($data['submit_date']) : '--' }}</td>
      </tr>

      <tr>
          <th>RECIEVED DATE</th>
          <td>{{ isset($data['recieved_date']) ? myDateFormate($data['recieved_date']) : '--' }}</td>
          <th>CUSTOMER GIVEN NAME</th>
          <td>{{ isset($data['customer_given_name']) ? $data['customer_given_name'] : '--' }}</td>
          </td>
          <th>CUSTOMER GIVEN DATE</th>
          <td>
              {{ isset($data['customer_given_date']) ? myDateFormate($data['customer_given_date']) : '--' }}
          </td>
      </tr>
      <tr>
          <th>CUSTOMER GIVEN NOTE</th>
          <td colspan="5">{{ isset($data['customer_given_note']) ? $data['customer_given_note'] : '--' }}</td>
      </tr>
  </table>
