<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseTransfer extends Model
{
    use HasFactory;

    protected $table = "purchase_transfers";

    protected $fillable = [
        'purchase_id',
        'broker_id',
        'transfer_date',
        'transfer_note',
        'return_date',
        'return_note',
        'status',
        'created_by'
    ];

    protected $hidden = [];

    protected $casts = [];
}
