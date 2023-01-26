<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">PURCHASE BIKE DETAIL</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <table class="table table-bordered">
            <tr>
                <th width="20%">BRANCH NAME</th>
                <td>
                    {{ isset($data['sale']['purchase']['branch']['branch_name']) ? $data['sale']['purchase']['branch']['branch_name'] : '--' }}
                </td>
                <th width="20%">DEALER NAME</th>
                <td>
                    {{ isset($data['sale']['purchase']['dealer']['company_name']) ? $data['sale']['purchase']['dealer']['company_name'] : '--' }}
                </td>
            </tr>
            <tr>
                <th width="20%">BRAND NAME</th>
                <td>
                    {{ isset($data['sale']['purchase']['brand']['name']) ? $data['sale']['purchase']['brand']['name'] : '--' }}
                </td>
                <th width="20%">BRAND MODEL NAME</th>
                <td>
                    {{ isset($data['sale']['purchase']['model']['model_name']) ? $data['sale']['purchase']['model']['model_name'] : '--' }}
                </td>
            </tr>
            <tr>
                <th width="20%">MODEL COLOR NAME</th>
                <td>
                    {{ isset($data['sale']['purchase']['modelColor']['color_name']) ? $data['sale']['purchase']['modelColor']['color_name'] : '--' }}
                </td>
            </tr>

            <tr>
                <th width="20%">VEHICLE TYPE</th>
                <td>
                    {{ isset($data['sale']['purchases']['bike_type']) ? $data['sale']['purchases']['bike_type'] : '--' }}
                </td>
                <th width="20%">FUEL TYPE</th>
                <td>
                    {{ isset($data['sale']['purchases']['bike_fuel_type']) ? $data['sale']['purchases']['bike_fuel_type'] : '--' }}
                </td>
            </tr>

            <tr>
                <th width="20%">BRAKE TYPE</th>
                <td>
                    {{ isset($data['sale']['purchases']['break_type']) ? $data['sale']['purchases']['break_type'] : '--' }}
                </td>
                <th width="20%">WHEEL TYPE</th>
                <td>
                    {{ isset($data['sale']['purchases']['wheel_type']) ? $data['sale']['purchases']['wheel_type'] : '--' }}
                </td>
            </tr>

            <tr>
                <th width="20%">VIN NUMBER</th>
                <td>
                    {{ isset($data['sale']['purchases']['vin_number']) ? $data['sale']['purchases']['vin_number'] : '--' }}
                </td>
                <th width="20%">VIN PHYSICAL STATUS</th>
                <td>
                    {{ isset($data['sale']['purchases']['vin_physical_status']) ? $data['sale']['purchases']['vin_physical_status'] : '--' }}
                </td>
            </tr>

            <tr>
                <th width="20%">SKU</th>
                <td>{{ isset($data['sale']['purchases']['sku']) ? $data['sale']['purchases']['sku'] : '--' }}
                </td>

                <th width="20%">SKU DESCRIPTION</th>
                <td>{{ isset($data['sale']['purchases']['sku_description']) ? $data['sale']['purchases']['sku_description'] : '--' }}
                </td>
            </tr>

            <tr>
                <th width="20%">HSN NUMBER</th>
                <td>{{ isset($data['sale']['purchases']['hsn_number']) ? $data['sale']['purchases']['hsn_number'] : '--' }}
                </td>

                <th width="20%">ENGINE NUMBER</th>
                <td>{{ isset($data['sale']['purchases']['engine_number']) ? $data['sale']['purchases']['engine_number'] : '--' }}
                </td>
            </tr>

            <tr>
                <th width="20%">KEY NUMBER</th>
                <td>{{ isset($data['sale']['purchases']['key_number']) ? $data['sale']['purchases']['key_number'] : '--' }}
                </td>

                <th width="20%">SERVICE BOOK NUMBER</th>
                <td>{{ isset($data['sale']['purchases']['service_book_number']) ? $data['sale']['purchases']['service_book_number'] : '--' }}
                </td>
            </tr>

            <tr>
                <th width="20%">BATTERY BRAND</th>
                <td>{{ isset($data['sale']['purchases']['batteryBrand']['name']) ? $data['sale']['purchases']['batteryBrand']['name'] : '--' }}
                </td>

                <th width="20%">BATTERY NUMBER</th>
                <td>{{ isset($data['sale']['purchases']['battery_number']) ? $data['sale']['purchases']['battery_number'] : '--' }}
                </td>
            </tr>

            <tr>
                <th width="20%">TYRE BRAND</th>
                <td>{{ isset($data['sale']['purchases']['tyreBrand']['name']) ? $data['sale']['purchases']['tyreBrand']['name'] : '--' }}
                </td>

                <th width="20%">TYRE FRONT NUMBER</th>
                <td>{{ isset($data['sale']['purchases']['tyre_front_number']) ? $data['sale']['purchases']['tyre_front_number'] : '--' }}
                </td>
            </tr>

            <tr>
                <th width="20%">TYRE REAR NUMBER</th>
                <td>{{ isset($data['sale']['purchases']['tyre_rear_number']) ? $data['sale']['purchases']['tyre_rear_number'] : '--' }}
                </td>

                <th width="20%">BIKE DESCRIPTION</th>
                <td>{{ isset($data['sale']['purchases']['bike_description']) ? $data['sale']['purchases']['bike_description'] : '--' }}
                </td>
            </tr>
        </table>
    </div>
    <!-- /.box-body -->
</div>
