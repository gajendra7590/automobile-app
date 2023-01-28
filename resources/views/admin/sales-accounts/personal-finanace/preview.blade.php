  <table class="table table-bordered">
      <tr>
          <th>ACCOUNT ID</th>
          <td> {{ isset($data['account']['account_uuid']) ? $data['account']['account_uuid'] : '--' }}</td>
          <th>DUE PAYMENT ID</th>
          <td>{{ leadingZero($data['id'], 6) }}</td>
      </tr>
      <tr>
          <th>TITLE</th>
          <td colspan="3">{{ isset($data['payment_name']) ? $data['payment_name'] : '--' }}</td>
      </tr>
      <tr>
          <th>INSTALLMENT AMOUNT</th>
          <td>
              <span class="badge bg-blue" style="padding:6px !important;">
                  {{ isset($data['emi_total_amount']) ? priceFormate($data['emi_total_amount']) : '0.00' }}
              </span>
          </td>
          <th>INSTALLMENT DUE DATE</th>
          <td>{{ isset($data['emi_due_date']) & !empty($data['emi_due_date']) ? myDateFormate($data['emi_due_date']) : '--' }}
          </td>
      </tr>

      @if (isset($data['account']['due_payment_source']) && $data['account']['due_payment_source'] == '3')
          <tr>
              <th>INSTALLMENT PRINCIPAL AMOUNT</th>
              <td> {{ priceFormate($data['emi_principal_amount']) }} </td>
              <th>INTREST AMOUNT</th>
              <td> {{ priceFormate($data['emi_intrest_amount']) }} </td>
          </tr>
      @endif
      <tr>
          <th>ADJUSTMENT AMOUNT</th>
          <td>
              @if (isset($data['adjust_amount']) && $data['adjust_amount'] > 0)
                  <span class="badge bg-red" style="padding:6px !important;">
                      {{ isset($data['adjust_amount']) ? priceFormate(-$data['adjust_amount']) : '0.00' }}
                  </span>
              @else
                  <span class="badge bg-green" style="padding:6px !important;">
                      {{ isset($data['adjust_amount']) ? '+' . priceFormate(-$data['adjust_amount']) : '0.00' }}
                  </span>
              @endif
          </td>
          <th>ADJUSTMENT DATE</th>
          <td>
              {{ isset($data['adjust_date']) ? myDateFormate($data['adjust_date']) : '--' }}
          </td>
      </tr>
      <tr>
          <th>ADJUSTMENT NOTES</th>
          <td>
              {{ isset($data['adjust_note']) ? $data['adjust_note'] : '--' }}
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
          <th>PAYMENT COLLECTED BY</th>
          <td>{{ isset($data['salesman']['name']) ? $data['salesman']['name'] : '--' }}</td>
      </tr>
      <tr>
          <td colspan="4">Note : If you will pay more then installment amount it will adjust in next
              installment.</td>
      </tr>
  </table>
