<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RtoAgent extends Model
{
    use HasFactory;

    protected $table = "rto_agents";

    protected $fillable = [
        'agent_name',
        'agent_phone',
        'agent_email',
        'active_status'
    ];

    protected $hidden = [];

    protected $casts = [];
}
