<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Enum;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchases';

    protected $fillable = [
        'addressed',
        'first_name',
        'last_name',
        'email',
        'phone',
        'gst_no',
        'booking_type',
        'state',
        'district',
        'city',
        'address_line1',
        'address_line2',
        'pin_code',
        'age',
        'gender',
        'occupation',
        'model_in_inters',
        'varient',
        'color_code',
        'quantity',
        'existing_customer',
        'exchange_enquiry',
        'finance_requirement',
        'loyalty_customer',
        'enquiry_date',
        'expected_date_of_purchase',
        'next_follow_date',
        'dse_name',
        'order_number',
    ];

    protected  $hidden = [];

    protected $casts = [];

    public function getFullNameAttribute(){
        return $this->first_name . ' ' . $this->last_name;
    }
}



// Customer
// --------------------

//         Mr / Ms
//         First Name
//         Last Name
//         Email
//         Phone
//         GST No
//         Booking Type

//         Address
// ---------------

//         State
//         District
//         Tehsil
//         City / Town / village
//         Address Line 1
//         Address Line 2
//         Pin Code

//         Customer Profile
// ----------------------------
//         Age -
//         Gender
//         Occupation

//         Vehicle Information
// ------------
//         Model in inters
//         Variant
//         Color Code
//         Quantity

//         Existing Customer
// -------------------
//         Exising Customer - Yes / No


//         Enquiry Information
// ----------------------------
//         Exchange Enquiry - Yes / No ( If Yes - Description )
//         Finace Requirement - Yes / No ( If Yes - Description )
//         Loyalty customer ( Yes / No )
//         Enquiry Date -
//         Expected Date Of Purchase -
//         Next Follow Date -
//         DSE Name( Employee ) -
//         Order Number -
