<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    use HasFactory;

    protected $table = "purchase_invoices";

    protected $fillable = [
        'purchase_id',
        'purchase_invoice_number',
        'purchase_invoice_date',
        'gst_rate',
        'gst_rate_percent',
        'pre_gst_amount',
        'gst_amount',
        'ex_showroom_price',
        'discount_price',
        'grand_total',
        'created_by'
    ];

    protected $hidden = [];

    protected $casts = [];
}
