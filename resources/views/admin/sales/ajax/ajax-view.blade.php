 <!-- CUSTOMER INFO START -->

 <div class="form-group col-md-2">
     <label>PREFIX</label>
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
     <label>CUSTOMER NAME</label>
     <input name="customer_name" type="text" class="form-control"
         value="{{ isset($data['customer_name']) ? $data['customer_name'] : '' }}" placeholder="Customer Name.."
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-2">
     <label>RELATION</label>
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
     <label>CUSTOMER GUARDIAN NAME</label>
     <input name="customer_guardian_name" type="text" class="form-control"
         value="{{ isset($data['customer_guardian_name']) ? $data['customer_guardian_name'] : '' }}"
         placeholder="Customer Guardian Name.."
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-12">
     <label>CUSTOMER ADDRESS LINE</label>
     <input name="customer_address_line" type="text" class="form-control"
         value="{{ isset($data['customer_address_line']) ? $data['customer_address_line'] : '' }}"
         placeholder="Customer Address Line.."
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-3">
     <label>CUSTOMER STATE</label>
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
     <label>CUSTOMER DISTRICT</label>
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
     <label>CUSTOMER CITY/VILLAGE
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
     <label>ZIPCODE</label>
     <input name="customer_zipcode" type="text" class="form-control"
         value="{{ isset($data['customer_zipcode']) ? $data['customer_zipcode'] : '' }}" placeholder="XXXXXX"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-4">
     <label>CUSTOMER PHONE NUMBER</label>
     <input name="customer_mobile_number" type="text" class="form-control"
         value="{{ isset($data['customer_mobile_number']) ? $data['customer_mobile_number'] : '' }}"
         placeholder="Customer Phone.."
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-4">
     <label>CUSTOMER ALTERNATE PHONE NUMBER</label>
     <input name="customer_mobile_number_alt" type="text" class="form-control"
         value="{{ isset($data['customer_mobile_number_alt']) ? $data['customer_mobile_number_alt'] : '' }}"
         placeholder="Customer Alternate Phone.."
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-4">
     <label>CUSTOMER EMAIL ADDRESS</label>
     <input name="customer_email_address" type="text" class="form-control"
         value="{{ isset($data['customer_email_address']) ? $data['customer_email_address'] : '' }}"
         placeholder="Customer Email.."
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>

 <div class="form-group col-md-6">
     <label>WITNESS PERSON NAME</label>
     <input name="witness_person_name" type="text" class="form-control"
         value="{{ isset($data['witness_person_name']) ? $data['witness_person_name'] : '' }}"
         placeholder="Witness Person Name.."
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>

 <div class="form-group col-md-6">
     <label>WITNESS PERSON PHONE NUMBER</label>
     <input name="witness_person_phone" type="text" class="form-control"
         value="{{ isset($data['witness_person_phone']) ? $data['witness_person_phone'] : '' }}"
         placeholder="Witness Person Phone.."
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <!-- CUSTOMER INFO START CLOSE -->

 <!-- BIKE START CLOSE -->
 <div class="form-group col-md-3">
     <label>BRANCH NAME</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['branch']['branch_name']) ? $purchaseModel['branch']['branch_name'] : '' }}"
         disabled />
 </div>
 <div class="form-group col-md-3">
     <label>BRAND NAME</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['brand']['name']) ? $purchaseModel['brand']['name'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-3">
     <label>MODEL NAME</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['model']['model_name']) ? $purchaseModel['model']['model_name'] : '' }}"
         disabled />
 </div>

 <div class="form-group col-md-3">
     <label>MODEL COLOR NAME</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['color']['color_name']) ? $purchaseModel['color']['color_name'] : '' }}"
         disabled />
 </div>
 <div class="form-group col-md-3">
     <label>VEHICLE TYPE</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['bike_type']) ? $purchaseModel['bike_type'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-3">
     <label>FUEL TYPE</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['bike_fuel_type']) ? $purchaseModel['bike_fuel_type'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-3">
     <label>BREAK TYPE</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['break_type']) ? $purchaseModel['break_type'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-3">
     <label>WHEAL TYPE</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['wheel_type']) ? $purchaseModel['wheel_type'] : '' }}" disabled />
 </div>


 <div class="form-group col-md-4">
     <label>VIN NUMBER(CHASIS NUMBER)</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['vin_number']) ? $purchaseModel['vin_number'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-4">
     <label>VIN PHYSICAL STATUS</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['vin_physical_status']) ? $purchaseModel['vin_physical_status'] : '' }}"
         disabled />
 </div>
 <div class="form-group col-md-4">
     <label>VARIANT CODE</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['variants']['variant_name']) ? $purchaseModel['variants']['variant_name'] : '' }}"
         disabled />
 </div>
 <div class="form-group col-md-4">
     <label>SKU CODE</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['color']['sku_code']) ? $purchaseModel['color']['sku_code'] : '' }}"
         disabled />
 </div>

 <div class="form-group col-md-8">
     <label>SKU DESCRIPTION</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['sku_description']) ? $purchaseModel['sku_description'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-3">
     <label>HSN NUMBER</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['hsn_number']) ? $purchaseModel['hsn_number'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-3">
     <label>Engine NUMBER</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['engine_number']) ? $purchaseModel['engine_number'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-3">
     <label>KEY NUMBER</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['key_number']) ? $purchaseModel['key_number'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-3">
     <label>SERVICE BOOK NUMBER</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['service_book_number']) ? $purchaseModel['service_book_number'] : '' }}"
         disabled />
 </div>
 <div class="form-group col-md-3">
     <label>TYRE BRAND NAME</label>
     <input type="text" class="form-control"
         value="{{ isset($purchaseModel['tyreBrand']['name']) ? $purchaseModel['tyreBrand']['name'] : '' }}"
         disabled />
 </div>
 <div class="form-group col-md-2">
     <label>TYRE FRONT NUMBER</label>
     <input type="text" class="form-control" placeholder="Tyre Front Number"
         value="{{ isset($purchaseModel['tyre_front_number']) ? $purchaseModel['tyre_front_number'] : '' }}"
         disabled />
 </div>
 <div class="form-group col-md-2">
     <label>TYRE REAR NUMBER</label>
     <input type="text" class="form-control" placeholder="Tyre Rear Number"
         value="{{ isset($purchaseModel['tyre_rear_number']) ? $purchaseModel['tyre_rear_number'] : '' }}" disabled />
 </div>
 <div class="form-group col-md-3">
     <label>BATTERY BRAND NAME</label>
     <input type="text" class="form-control" placeholder="Tyre Rear Number"
         value="{{ isset($purchaseModel['batteryBrand']['name']) ? $purchaseModel['batteryBrand']['name'] : '' }}"
         disabled />
 </div>
 <div class="form-group col-md-2">
     <label>BATTERY NUMBER</label>
     <input type="text" class="form-control" placeholder="Battery Number"
         value="{{ isset($purchaseModel->battery_number) ? $purchaseModel->battery_number : '' }}" disabled />
 </div>
 <div class="form-group col-md-12">
     <label>VEHICLE DESCRIPTION</label>
     <textarea rows="3" class="form-control" disabled>{{ isset($purchaseModel['bike_description']) ? $purchaseModel['bike_description'] : '' }}</textarea>
 </div>
 <!-- BIKE INFO CLOSE -->


 <div class="form-group col-md-3">
     <label>IS EXCHANGE</label>
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
     <label>PAYMENT TYPE</label>
     <select name="payment_type" class="form-control ajaxChangeCDropDown" data-dep_dd_name="hyp_financer"
         data-url="{{ url('getAjaxDropdown') . '?req=financiers_list' }}" data-dep_dd2_name=""
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
         <option value="1"
             {{ isset($data['payment_type']) && $data['payment_type'] == '1' ? 'selected="selected"' : '' }}>
             By Cash
         </option>
         {{-- @if (isset($data['id'])) --}}
         <option value="2"
             {{ isset($data['payment_type']) && $data['payment_type'] == '2' ? 'selected="selected"' : '' }}>
             Bank Finance
         </option>
         <option value="3"
             {{ isset($data['payment_type']) && $data['payment_type'] == '3' ? 'selected="selected"' : '' }}>
             Personal Finance
         </option>
         {{-- @endif --}}
     </select>
 </div>
 <div class="form-group col-md-6">
     <label>HYPOTHECATION FINANCER</label>
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
     <label>HYPOTHECATION FINANCER NOTE</label>
     <input name="hyp_financer_description" type="text" class="form-control"
         value="{{ isset($data['hyp_financer_description']) ? $data['hyp_financer_description'] : '' }}"
         placeholder="Description..."
         {{ isset($data['payment_type']) && in_array($data['payment_type'], [2, 3]) ? '' : 'disabled' }}
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-4">
     <label>EX-SHOWROOM PRICE</label>
     <input name="ex_showroom_price" type="text" class="form-control totalAmountCal"
         value="{{ isset($data['ex_showroom_price']) ? $data['ex_showroom_price'] : '' }}" placeholder="₹ XXXX"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-4">
     <label>REGISTRATION AMOUNT</label>
     <input name="registration_amount" type="text" class="form-control totalAmountCal"
         value="{{ isset($data['registration_amount']) ? $data['registration_amount'] : '' }}" placeholder="₹ XXXX"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-4">
     <label>INSURANCE AMOUNT</label>
     <input name="insurance_amount" type="text" class="form-control totalAmountCal"
         value="{{ isset($data['insurance_amount']) ? $data['insurance_amount'] : '' }}" placeholder="₹ XXXX"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-3">
     <label>HYPOTHECATION AMOUNT</label>
     <input name="hypothecation_amount" type="text" class="form-control totalAmountCal"
         value="{{ isset($data['hypothecation_amount']) ? $data['hypothecation_amount'] : '' }}" placeholder="₹ XXXX"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-3">
     <label>ACCESSORIES AMOUNT</label>
     <input name="accessories_amount" type="text" class="form-control totalAmountCal"
         value="{{ isset($data['accessories_amount']) ? $data['accessories_amount'] : '' }}" placeholder="₹ XXXX"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-3">
     <label>OTHER AMOUNT</label>
     <input name="other_charges" type="text" class="form-control totalAmountCal"
         value="{{ isset($data['other_charges']) ? $data['other_charges'] : '' }}" placeholder="₹ XXXX"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
 <div class="form-group col-md-3">
     <label>GRAND TOTAL AMOUNT</label>
     <input name="total_amount" type="text" class="form-control" readonly
         value="{{ isset($data['total_amount']) ? $data['total_amount'] : '' }}" placeholder="₹ XXXX"
         {{ isset($data['sp_account_id']) && $data['sp_account_id'] > 0 ? 'disabled' : '' }}>
 </div>
