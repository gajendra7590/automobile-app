<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikeFinance extends Model
{
    use HasFactory;

    protected $table = 'bike_finance';

    protected $fillable = [
            'bike_purchased_id',
            'finance_type_id',
            'finance_company',
            'financier_person_name',
            'financier_person_phone',
            'financier_person_email',
            'financier_person_address',
            'loan_start_date',
            'loan_end_date',
            'loan_duration',
            'loan_amount',
            'loan_purpose'
    ];

    protected  $hidden = [];

    protected $casts = [];
}
