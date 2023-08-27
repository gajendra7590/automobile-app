<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RtoAgent extends Model
{
    use HasFactory;

    protected $table = "rto_agents";

    protected $fillable = [
        'branch_id',
        'agent_name',
        'agent_phone',
        'agent_email',
        'active_status'
    ];

    protected $hidden = [];

    protected $casts = [];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function payments()
    {
        return $this->hasMany(RtoAgentPaymentHistory::class, 'rto_agent_id');
    }

    public function registrations()
    {
        return $this->hasMany(RtoRegistration::class, 'rto_agent_id');
    }
}
