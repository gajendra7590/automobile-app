<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerReturnSaleRefund extends Model
{
    use HasFactory;
    protected $table = 'customer_return_sale_refunds';

    protected $fillable = [
        'sale_id',
        'sale_account_id',
        'amount_refund',
        'amount_refund_source',
        'amount_refund_date',
        'payment_refund_note',
        'payment_collected_by'
    ];

    protected $hidden = [];

    protected $casts = [];
}
