<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RtoAgentPaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'rto_agent_payment_history';

    protected $fillable = [
        'rto_agent_id',
        'payment_amount',
        'payment_mode',
        'payment_note',
        'payment_date'
    ];

    protected  $hidden = [];

    protected $casts = [];

    public function agent()
    {
        return $this->belongsTo(RtoAgent::class, 'rto_agent_id');
    }
}
