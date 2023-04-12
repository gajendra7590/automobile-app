<table class="table table-bordered">
    @if (isset($data['transfer_status']) && $data['transfer_status'] == '1')
        <caption>
            <span class="text-danger">Note : This inventory is available at
                broker({{ isset($data['transfers']['broker']) ? $data['transfers']['broker']['name'] : '' }}) showroom.
            </span>
        </caption>
    @endif
    <tr>
        <th>SOLD STATUS</th>
        <td>{!! isset($data['status']) ? convertBadgesStr($data['status'], 'purSold') : '--' !!}
        </td>
        <th>INVOICE AMOUNT</th>
        <td>{!! isset($data['invoice']['grand_total']) ? convertBadgesPrice($data['invoice']['grand_total']) : '--' !!}
        </td>
        <th>TRANSFER STATUS</th>
        <td>{!! isset($data['transfer_status']) ? convertBadgesStr($data['transfer_status']) : '--' !!}
        </td>
    </tr>
    <tr>
        <th>PURCHASE ID</th>
        <td>{{ isset($data['id']) ? leadingZero($data['id'], 6) : '--' }}</td>
        <th>BRANCH NAME</th>
        <td>{{ isset($data['branch']['branch_name']) ? $data['branch']['branch_name'] : '--' }}</td>
        <th>DEALER NAME</th>
        <td>{{ isset($data['dealer']['company_name']) ? $data['dealer']['company_name'] : '--' }}</td>
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
        <th>BIKE TYPE</th>
        <td>{{ isset($data['bike_type']) ? $data['bike_type'] : '--' }}</td>
        <th>FUEL TYPE</th>
        <td>{{ isset($data['bike_fuel_type']) ? $data['bike_fuel_type'] : '--' }}</td>
        {{-- <th>BREAK TYPE</th>
        <td>{{ isset($data['break_type']) ? $data['break_type'] : '--' }}</td> --}}
    </tr>
    <tr>
        {{-- <th>BIKE TYPE</th>
        <td>{{ isset($data['wheel_type']) ? $data['wheel_type'] : '--' }}</td> --}}
        <th>DC NUMBER</th>
        <td>{{ isset($data['dc_number']) ? $data['dc_number'] : '--' }}</td>
        <th>DC DATE</th>
        <td>{{ isset($data['dc_date']) ? $data['dc_date'] : '--' }}</td>
    </tr>
    <tr>
        <th>VIN NUMBER</th>
        <td>{{ isset($data['vin_number']) ? $data['vin_number'] : '--' }}</td>
        <th>VIN PHYSICAL STATUS</th>
        <td>{{ isset($data['vin_physical_status']) ? $data['vin_physical_status'] : '--' }}</td>
        <th>VARIANT</th>
        <td>{{ isset($data['variant']) ? $data['variant'] : '--' }}</td>
    </tr>
    <tr>
        <th>SKU</th>
        <td>{{ isset($data['sku']) ? $data['sku'] : '--' }}</td>
        <th>SKU DESCRIPTION</th>
        <td colspan="3">{{ isset($data['sku_description']) ? $data['sku_description'] : '--' }}</td>
    </tr>

    <tr>
        <th>HSN NUMBER</th>
        <td>{{ isset($data['hsn_number']) ? $data['hsn_number'] : '--' }}</td>
        <th>ENGINE NUMBER</th>
        <td>{{ isset($data['engine_number']) ? $data['engine_number'] : '--' }}</td>
        <th>KEY NUMBER</th>
        <td>{{ isset($data['key_number']) ? $data['key_number'] : '--' }}</td>
    </tr>
    <tr>
        <th>SERVICE BOOK NUMBER</th>
        <td>{{ isset($data['service_book_number']) ? $data['service_book_number'] : '--' }}</td>
        <th>TYRE BRAND</th>
        <td>{{ isset($data['tyreBrand']['name']) ? $data['tyreBrand']['name'] : '--' }}</td>
        <th>TYRE FRONT NUMBER</th>
        <td>{{ isset($data['tyre_front_number']) ? $data['tyre_front_number'] : '--' }}</td>
    </tr>

    <tr>
        <th>TYRE REAR NUMBER</th>
        <td>{{ isset($data['tyre_rear_number']) ? $data['tyre_rear_number'] : '--' }}</td>
        <th>BATTERY BRAND NAME</th>
        <td>{{ isset($data['batteryBrand']['name']) ? $data['batteryBrand']['name'] : '--' }}</td>
        <th>BATTERY NUMBER</th>
        <td>{{ isset($data['battery_number']) ? $data['battery_number'] : '--' }}</td>
    </tr>

    <tr>
        <th>GST RATE</th>
        <td>{{ isset($data['gst_rate_percent']) ? $data['gst_rate_percent'] . '%' : '--' }}</td>
        <th>PRE GST PRICE</th>
        <td>{{ isset($data['pre_gst_amount']) ? priceFormate($data['pre_gst_amount']) : '--' }}</td>
        <th>GST AMOUNT</th>
        <td>{{ isset($data['gst_amount']) ? priceFormate($data['gst_amount']) : '--' }}</td>
    </tr>
    <tr>
        <th>EX SHOWROOM PRICE</th>
        <td>{{ isset($data['ex_showroom_price']) ? priceFormate($data['ex_showroom_price']) : '--' }}</td>
        <th>DISCOUNT PRICE</th>
        <td>{{ isset($data['discount_price']) ? priceFormate($data['discount_price']) : '--' }}</td>
        <th>GRAND TOTAL</th>
        <td>{{ isset($data['grand_total']) ? priceFormate($data['grand_total']) : '--' }}</td>
    </tr>
    <tr>
        <th colspan="2">DESCRIPTION</th>
        <td colspan="4">{{ isset($data['bike_description']) ? $data['bike_description'] : '--' }}</td>
    </tr>
    @if (isset($data['purchase_return_note']))
    <tr>
        <th colspan="2">RETURN DESCRIPTION</th>
        <td colspan="4">{{ isset($data['purchase_return_note']) ? $data['purchase_return_note'] : '--' }}</td>
    </tr>
    @endif
</table>
