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
          <td colspan="2">{{ isset($data['contact_name']) ? $data['contact_name'] : '--' }}</td>
          <th colspan="2">CONTACT MOBILE NUMBER</th>
          <td>{{ isset($data['contact_mobile_number']) ? $data['contact_mobile_number'] : '--' }}</td>
      </tr>
      <tr>
          <th>CONTACT ADDRESS LINE</th>
          <td colspan="5">{{ isset($data['contact_address_line']) ? $data['contact_address_line'] : '--' }}</td>
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
          <th>INSTALLMENT AMOUNT</th>
          <td>
              <span class="badge bg-blue" style="padding:6px !important;">
                  {{ isset($data['emi_due_amount']) ? priceFormate($data['emi_due_amount']) : '0.00' }}
              </span>
          </td>
          <th>INSTALLMENT DUE DATE</th>
          <td>{{ isset($data['emi_due_date']) & !empty($data['emi_due_date']) ? myDateFormate($data['emi_due_date']) : '--' }}
          </td>
      </tr>

      @if (isset($data['account']['due_payment_source']) && $data['account']['due_payment_source'] == '3')
          <tr>
              <th>INSTALLMENT PRINCIPAL AMOUNT</th>
              <td> {{ priceFormate($data['emi_due_principal']) }} </td>
              <th>INTREST AMOUNT</th>
              <td> {{ priceFormate($data['emi_due_intrest']) }} </td>
          </tr>
      @endif
      <tr>
          <th>OTHER ADJUSTMENTS</th>
          <td>
              @if (isset($data['emi_other_adjustment']) && $data['emi_other_adjustment'] > 0)
                  <span class="badge bg-red" style="padding:6px !important;">
                      {{ isset($data['emi_other_adjustment']) ? priceFormate(-$data['emi_other_adjustment']) : '0.00' }}
                  </span>
              @else
                  <span class="badge bg-green" style="padding:6px !important;">
                      {{ isset($data['emi_other_adjustment']) ? '+' . priceFormate(-$data['emi_other_adjustment']) : '0.00' }}
                  </span>
              @endif
          </td>
          <th>ADJUSTMENT DATE</th>
          <td>
              {{ isset($data['emi_other_adjustment_date']) ? myDateFormate($data['emi_other_adjustment_date']) : '--' }}
          </td>
      </tr>
      <tr>
          <th>ADJUSTMENT NOTES</th>
          <td>
              {{ isset($data['emi_other_adjustment_note']) ? $data['emi_other_adjustment_note'] : '--' }}
          </td>
          <th>FINAL PAYABLE AMOUNT</th>
          <td>
              <span class="badge bg-red" style="padding:6px !important;">
                  {{ isset($data['emi_due_revised_amount']) ? priceFormate($data['emi_due_revised_amount']) : '0.00' }}
              </span>
          </td>
      </tr>
      <tr>
          <th>PAID AMOUNT</th>
          <td>
              <span class="badge bg-green" style="padding:6px !important;">
                  {{ isset($data['amount_paid']) && !empty($data['amount_paid']) ? priceFormate($data['amount_paid']) : '0.00' }}
              </span>
          </td>
          <th>PAID DATE</th>
          <td>{{ isset($data['amount_paid_date']) && !empty($data['amount_paid_date']) ? myDateFormate($data['amount_paid_date']) : '--' }}
          </td>
      </tr>
      <tr>
          <th>PAID SOURCE</th>
          <td>{{ isset($data['amount_paid_source']) ? $data['amount_paid_source'] : '--' }}</td>
          <th>PAID NOTE</th>
          <td>{{ isset($data['amount_paid_note']) ? $data['amount_paid_note'] : '--' }}</td>
      </tr>
      <tr>
          <th>STATUS</th>
          <td>
              @isset($data['status'])
                  @if ($data['status'] == '0')
                      <span class="badge bg-red">Open</span>
                  @else
                      <span class="badge bg-green">Close</span>
                  @endif
              @endisset
          </td>
          <th>PAID DUE AMOUNT(+/-)</th>
          <td>{{ isset($data['pay_due']) ? priceFormate($data['pay_due']) : '0.00' }}</td>
      </tr>
      <tr>
          <td colspan="4">Note : If you will pay more then installment amount it will adjust in next
              installment.</td>
      </tr>
  </table>
