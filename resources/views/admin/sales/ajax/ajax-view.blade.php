 <!-- CUSTOMER INFO START -->

 <div class="form-group col-md-2">
     <label>Prefix</label>
     <select name="customer_gender" class="form-control"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
         <option {{ isset($data['customer_gender']) && $data['customer_gender'] == '1' ? 'selected' : '' }}
             value="1">Mr.</option>
         <option {{ isset($data['customer_gender']) && $data['customer_gender'] == '2' ? 'selected' : '' }}
             value="2">Mrs.</option>
         <option {{ isset($data['customer_gender']) && $data['customer_gender'] == '3' ? 'selected' : '' }}
             value="2">Miss</option>
     </select>
 </div>
 <div class="form-group col-md-4">
     <label>Customer Name</label>
     <input name="customer_name" type="text" class="form-control"
         value="{{ isset($data['customer_name']) ? $data['customer_name'] : '' }}" placeholder="Customer Name.."
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-2">
     <label>Relation</label>
     <select name="customer_relationship" class="form-control"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
         <option {{ isset($data['customer_relationship']) && $data['customer_relationship'] == '1' ? 'selected' : '' }}
             value="1">S/o</option>
         <option {{ isset($data['customer_relationship']) && $data['customer_relationship'] == '2' ? 'selected' : '' }}
             value="2">W/o</option>
         <option {{ isset($data['customer_relationship']) && $data['customer_relationship'] == '3' ? 'selected' : '' }}
             value="3">D/o</option>
     </select>
 </div>
 <div class="form-group col-md-4">
     <label>Customer Guardian Name</label>
     <input name="customer_guardian_name" type="text" class="form-control"
         value="{{ isset($data['customer_guardian_name']) ? $data['customer_guardian_name'] : '' }}"
         placeholder="Customer Guardian Name.."
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-12">
     <label>Customer Address Line</label>
     <input name="customer_address_line" type="text" class="form-control"
         value="{{ isset($data['customer_address_line']) ? $data['customer_address_line'] : '' }}"
         placeholder="Customer Address Line.."
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-3">
     <label>Customer State</label>
     <select name="customer_state" data-dep_dd_name="customer_district"
         data-url="{{ url('getAjaxDropdown') . '?req=districts' }}" data-dep_dd2_name="customer_city"
         class="form-control ajaxChangeCDropDown"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
         <option value="">---Select Customer State---</option>
         @isset($states)
             @foreach ($states as $state)
                 <option
                     {{ isset($data['customer_state']) && $data['customer_state'] == $state->id ? 'selected="selected"' : '' }}
                     value="{{ $state->id }}">{{ $state->state_name }}</option>
             @endforeach
         @endisset
     </select>
 </div>
 <div class="form-group col-md-3">
     <label>Customer District</label>
     <select name="customer_district" class="form-control ajaxChangeCDropDown" data-dep_dd_name="customer_city"
         data-url="{{ url('getAjaxDropdown') . '?req=cities' }}" data-dep_dd2_name=""
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
         <option value="">---Select District---</option>
         @isset($districts)
             @foreach ($districts as $district)
                 <option
                     {{ isset($data['customer_district']) && $data['customer_district'] == $district->id ? 'selected="selected"' : '' }}
                     value="{{ $district->id }}">{{ $district->district_name }}</option>
             @endforeach
         @endisset
     </select>
 </div>
 <div class="form-group col-md-3">
     <label>Customer City/Village
         <span style="margin-left: 40px;">
             <a href="{{ route('plusAction') }}" class="plusAction" id="city" data-type="city" aria-hidden="true"
                 data-modal_title="Add New City/Village/Town" data-modal-index="1200" data-modal_size="modal-md">
                 <i class="fa fa-plus-circle" title="Add New City/Village/Town"></i>
             </a>
         </span>
     </label>
     <select name="customer_city" class="form-control"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
         <option value="">---Select City/Village----</option>
         @isset($cities)
             @foreach ($cities as $city)
                 <option
                     {{ isset($data['customer_city']) && $data['customer_city'] == $city->id ? 'selected="selected"' : '' }}
                     value="{{ $city->id }}">{{ $city->city_name }}</option>
             @endforeach
         @endisset
     </select>
 </div>
 <div class="form-group col-md-3">
     <label>ZipCode</label>
     <input name="customer_zipcode" type="text" class="form-control"
         value="{{ isset($data['customer_zipcode']) ? $data['customer_zipcode'] : '' }}" placeholder="XXXXXX"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-4">
     <label>Customer Phone</label>
     <input name="customer_mobile_number" type="text" class="form-control"
         value="{{ isset($data['customer_mobile_number']) ? $data['customer_mobile_number'] : '' }}"
         placeholder="Customer Phone.."
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-4">
     <label>Customer Alternate Phone</label>
     <input name="customer_mobile_number_alt" type="text" class="form-control"
         value="{{ isset($data['customer_mobile_number_alt']) ? $data['customer_mobile_number_alt'] : '' }}"
         placeholder="Customer Alternate Phone.."
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-4">
     <label>Customer Email</label>
     <input name="customer_email_address" type="text" class="form-control"
         value="{{ isset($data['customer_email_address']) ? $data['customer_email_address'] : '' }}"
         placeholder="Customer Email.."
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>

 <div class="form-group col-md-6">
     <label>Witness Person Name</label>
     <input name="witness_person_name" type="text" class="form-control"
         value="{{ isset($data['witness_person_name']) ? $data['witness_person_name'] : '' }}"
         placeholder="Witness Person Name.."
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>

 <div class="form-group col-md-6">
     <label>Witness Person Phone</label>
     <input name="witness_person_phone" type="text" class="form-control"
         value="{{ isset($data['witness_person_phone']) ? $data['witness_person_phone'] : '' }}"
         placeholder="Witness Person Phone.."
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <!-- CUSTOMER INFO START CLOSE -->

 <!-- BIKE START CLOSE -->
 <div class="form-group col-md-3">
     <label>Branch</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['branch']['branch_name']) ? $purchaseModel['branch']['branch_name'] : '' }}"
         disabled />
 </div>
 <div class="form-group col-md-3">
     <label>Brand Name</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['brand']['name']) ? $purchaseModel['brand']['name'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-3">
     <label>Model Name</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['model']['model_name']) ? $purchaseModel['model']['model_name'] : '' }}"
         disabled />
 </div>

 <div class="form-group col-md-3">
     <label>Model Color</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['color']['color_name']) ? $purchaseModel['color']['color_name'] : '' }}"
         disabled />
 </div>
 <div class="form-group col-md-3">
     <label>Vehicle Type</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['bike_type']) ? $purchaseModel['bike_type'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-3">
     <label>Fuel Type</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['bike_fuel_type']) ? $purchaseModel['bike_fuel_type'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-3">
     <label>Break Type</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['break_type']) ? $purchaseModel['break_type'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-3">
     <label>Wheal Type</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['wheel_type']) ? $purchaseModel['wheel_type'] : '' }}" disabled />
 </div>


 <div class="form-group col-md-4">
     <label>VIN Number(Chasis Number)</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['vin_number']) ? $purchaseModel['vin_number'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-4">
     <label>VIN Physical Status</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['vin_physical_status']) ? $purchaseModel['vin_physical_status'] : '' }}"
         disabled />
 </div>
 <div class="form-group col-md-4">
     <label>SKU</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['sku']) ? $purchaseModel['sku'] : '' }}" disabled />
 </div>

 <div class="form-group col-md-12">
     <label>SKU Description</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['sku_description']) ? $purchaseModel['sku_description'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-3">
     <label>HSN Number</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['hsn_number']) ? $purchaseModel['hsn_number'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-3">
     <label>Engine Number</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['engine_number']) ? $purchaseModel['engine_number'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-3">
     <label>Key Number</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['key_number']) ? $purchaseModel['key_number'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-3">
     <label>Service Book Number</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['service_book_number']) ? $purchaseModel['service_book_number'] : '' }}"
         disabled />
 </div>
 <div class="form-group col-md-3">
     <label>Tyre Brand</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['tyreBrand']['name']) ? $purchaseModel['tyreBrand']['name'] : '' }}"
         disabled />
 </div>
 <div class="form-group col-md-2">
     <label>Tyre Front Number</label>
     <input type="text" class="form-control" placeholder="Tyre Front Number"
         value="{{ isset($purchaseModel['tyre_front_number']) ? $purchaseModel['tyre_front_number'] : '' }}"
         disabled />
 </div>
 <div class="form-group col-md-2">
     <label>Tyre Rear Number</label>
     <input type="text" class="form-control" placeholder="Tyre Rear Number"
         value="{{ isset($purchaseModel['tyre_rear_number']) ? $purchaseModel['tyre_rear_number'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-3">
     <label>Battery Brand</label>
     <input type="text" class="form-control" placeholder="Tyre Rear Number"
         value="{{ isset($purchaseModel['batteryBrand']['name']) ? $purchaseModel['batteryBrand']['name'] : '' }}"
         disabled />
 </div>
 <div class="form-group col-md-2">
     <label>Battery Number</label>
     <input type="text" class="form-control" placeholder="Battery Number"
         value="{{ isset($purchaseModel->battery_number) ? $purchaseModel->battery_number : '' }}" disabled />
 </div>
 <div class="form-group col-md-12">
     <label>Vehicle Description</label>
     <textarea rows="3" class="form-control" disabled>{{ isset($purchaseModel['bike_description']) ? $purchaseModel['bike_description'] : '' }}</textarea>
 </div>
 <!-- BIKE INFO CLOSE -->


 <div class="form-group col-md-3">
     <label>Is Exchange</label>
     <select class="form-control" name="is_exchange_avaliable"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
         <option value="No"
             {{ isset($purchaseModel['is_exchange_avaliable']) && $purchaseModel['is_exchange_avaliable'] == 'No' ? 'selected="selected"' : '' }}>
             No
         </option>
         <option value="Yes"
             {{ isset($purchaseModel['is_exchange_avaliable']) && $purchaseModel['is_exchange_avaliable'] == 'Yes' ? 'selected="selected"' : '' }}>
             Yes
         </option>
     </select>
 </div>
 <div class="form-group col-md-3">
     <label>Payment Type</label>
     <select name="payment_type" class="form-control ajaxChangeCDropDown" data-dep_dd_name="hyp_financer"
         data-url="{{ url('getAjaxDropdown') . '?req=financiers_list' }}" data-dep_dd2_name=""
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
         <option value="1"
             {{ isset($data['payment_type']) && $data['payment_type'] == '1' ? 'selected="selected"' : '' }}>
             By Cash
         </option>
         <option value="2"
             {{ isset($data['payment_type']) && $data['payment_type'] == '2' ? 'selected="selected"' : '' }}>
             Bank Finance
         </option>
         <option value="3"
             {{ isset($data['payment_type']) && $data['payment_type'] == '3' ? 'selected="selected"' : '' }}>
             Personal Finance
         </option>
     </select>
 </div>
 <div class="form-group col-md-6">
     <label>Hypothecation Financer</label>
     <select name="hyp_financer" class="form-control"
         {{ isset($data['payment_type']) && in_array($data['payment_type'], [2, 3]) ? '' : 'disabled' }}
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
         <option value="">---Select Hypothecation Financer----</option>
         @isset($bank_financers)
             @foreach ($bank_financers as $bank_financer)
                 <option
                     {{ isset($data['hyp_financer']) && $data['hyp_financer'] == $bank_financer->id ? 'selected="selected"' : '' }}
                     value="{{ $bank_financer->id }}">{{ $bank_financer->bank_name }}</option>
             @endforeach
         @endisset
     </select>
 </div>
 <div class="form-group col-md-12">
     <label>Hypothecation Financer Description</label>
     <input name="hyp_financer_description" type="text" class="form-control"
         value="{{ isset($data['hyp_financer_description']) ? $data['hyp_financer_description'] : '' }}"
         placeholder="Description..."
         {{ isset($data['payment_type']) && in_array($data['payment_type'], [2, 3]) ? '' : 'disabled' }}
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-4">
     <label>Ex Showroom Price</label>
     <input name="ex_showroom_price" type="text" class="form-control totalAmountCal"
         value="{{ isset($data['ex_showroom_price']) ? $data['ex_showroom_price'] : '' }}" placeholder="₹ XXXX"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-4">
     <label>Registration Amount</label>
     <input name="registration_amount" type="text" class="form-control totalAmountCal"
         value="{{ isset($data['registration_amount']) ? $data['registration_amount'] : '' }}" placeholder="₹ XXXX"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-4">
     <label>Insurance Amount</label>
     <input name="insurance_amount" type="text" class="form-control totalAmountCal"
         value="{{ isset($data['insurance_amount']) ? $data['insurance_amount'] : '' }}" placeholder="₹ XXXX"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-3">
     <label>Hypothecation Amount</label>
     <input name="hypothecation_amount" type="text" class="form-control totalAmountCal"
         value="{{ isset($data['hypothecation_amount']) ? $data['hypothecation_amount'] : '' }}" placeholder="₹ XXXX"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-3">
     <label>Accessories Amount</label>
     <input name="accessories_amount" type="text" class="form-control totalAmountCal"
         value="{{ isset($data['accessories_amount']) ? $data['accessories_amount'] : '' }}" placeholder="₹ XXXX"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-3">
     <label>Other Amount</label>
     <input name="other_charges" type="text" class="form-control totalAmountCal"
         value="{{ isset($data['other_charges']) ? $data['other_charges'] : '' }}" placeholder="₹ XXXX"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-3">
     <label>Total Amount</label>
     <input name="total_amount" type="text" class="form-control" readonly
         value="{{ isset($data['total_amount']) ? $data['total_amount'] : '' }}" placeholder="₹ XXXX"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
