<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">CUSTOMER INFORMATION</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <table class="table table-bordered">
            <tr>
                <th width="30%">CUSTOMER NAME</th>
                <td>
                    {{ custFullName(isset($data['sale']) ? $data['sale'] : []) }}
                </td>
            </tr>
            <tr>
                <th width="30%">CUSTOMER ADDRESS</th>
                <td>{{ custFullAddress(isset($data['sale']) ? $data['sale'] : []) }}
                </td>
            </tr>
            <tr>
                <th width="30%">CUSTOMER PHONE</th>
                <td>{{ isset($data['sale']['customer_mobile_number']) ? $data['sale']['customer_mobile_number'] : '--' }}
                </td>
            </tr>
            <tr>
                <th width="30%">CUSTOMER ALTERNATE PHONE</th>
                <td>{{ isset($data['sale']['customer_mobile_number_alt']) ? $data['sale']['customer_mobile_number_alt'] : '--' }}
                </td>
            </tr>
            <tr>
                <th width="30%">CUSTOMER EMAIL</th>
                <td>{{ isset($data['sale']['customer_email_address']) ? $data['sale']['customer_email_address'] : '--' }}
                </td>
            </tr>
            <tr>
                <th width="30%">WITNESS PERSON NAME</th>
                <td>{{ isset($data['sale']['witness_person_name']) ? $data['sale']['witness_person_name'] : '--' }}
                </td>
            </tr>
            <tr>
                <th width="30%">WITNESS PERSON PHONE</th>
                <td>{{ isset($data['sale']['witness_person_phone']) ? $data['sale']['witness_person_phone'] : '--' }}
                </td>
            </tr>
        </table>
    </div>
    <!-- /.box-body -->
</div>
