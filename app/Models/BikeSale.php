<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikeSale extends Model
{
    use HasFactory;

    protected $table = 'bike_sales';

    protected $fillable = [
        'bike_purchased_id',
        'customer_name',
        'customer_mobile_number',
        'customer_other_number',
        'customer_email_address',
        'customer_full_address',
        'customer_aadhar_card',
        'customer_pan_card',
        'reference_name',
        'reference_mobile_number',
        'reference_address',
        'sale_date',
        'sold_price',
        'discount_price',
        'final_sale_price_on_road',
        'down_payment',
        'down_payment_mode',
        'down_payment_mode_note',
        'down_payment_collected_by',
        'due_amount',
        'due_payment_date',
        'due_payment_mode',
        'financial_bank',
        'financier_name',
        'financial_total_installments',
        'remark',
        'payment_don',
    ];

    protected  $hidden = [];

    protected $casts = [];
}
