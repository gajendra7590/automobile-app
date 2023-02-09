<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDealerPaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'purchase_dealer_payment_history';

    protected $fillable = [
        'dealer_id',
        'payment_amount',
        'payment_mode',
        'payment_note',
        'payment_date'
    ];

    protected  $hidden = [];

    protected $casts = [];

    public function dealer()
    {
        return $this->belongsTo(BikeDealer::class, 'dealer_id');
    }
}
