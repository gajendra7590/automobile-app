  <table class="table table-bordered">
      <tr>
          <th>PAYMENT ACCOUNT ID</th>
          <td> {{ isset($data['account']['account_uuid']) ? $data['account']['account_uuid'] : '--' }}</td>
          <th>BANK FINANCE PAYMENT ID</th>
          <td>{{ leadingZero($data['id'], 6) }}</td>
      </tr>
      <tr>
          <th>PAYMENT NAME</th>
          <td colspan="3">{{ isset($data['payment_name']) ? $data['payment_name'] : '--' }}</td>
      </tr>
      <tr>
          <th>DUE DATE</th>
          <td>
              {{ isset($data['due_date']) ? myDateFormate($data['due_date']) : '--' }}
          </td>
          <th>PAID AMOUNT</th>
          <td>
              <span class="badge bg-green" style="padding:6px !important;">
                  {{ isset($data['debit_amount']) ? priceFormate($data['debit_amount']) : '0.00' }}
              </span>
          </td>
      </tr>
      <tr>
          <th>PAID DATE</th>
          <td>
              {{ isset($data['paid_date']) ? myDateFormate($data['paid_date']) : '--' }}
          </td>
          <th>PAID SOURCE</th>
          <td>
              {{ isset($data['paid_source']) ? $data['paid_source'] : '--' }}
          </td>
      </tr>
      <tr>
          <th>PAID NOTE</th>
          <td colspan="3">
              {{ isset($data['paid_note']) ? $data['paid_note'] : '--' }}
          </td>
      </tr>
      <tr>
          <th>PAYMENT COLLECTED BY</th>
          <td>{{ isset($data['salesman']['name']) ? $data['salesman']['name'] : '--' }}</td>
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
      </tr>
      @if (isset($data['received_in_bank']) && !empty($data['received_in_bank']))
          <tr>
              <th>RECEIVED IN BANK NAME</th>
              <td>{{ isset($data['receivedBank']['bank_name']) ? $data['receivedBank']['bank_name'] : '--' }}</td>
              <th>RECEIVED IN BANK ACCOUNT</th>
              <td>{{ isset($data['receivedBank']['bank_account_number']) ? $data['receivedBank']['bank_account_number'] : '--' }}
              </td>
          </tr>
      @endif
      <tr>
          <td colspan="4">Note : If you will pay more then installment amount it will adjust in next
              installment.</td>
      </tr>
  </table>
