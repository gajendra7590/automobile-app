<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankFinancer extends Model
{
    use HasFactory;

    protected $table = 'bank_financers';

    protected $fillable = [
        'bank_name',
        'bank_branch_code',
        'bank_contact_number',
        'bank_email_address',
        'bank_full_address',
        'bank_manager_name',
        'bank_manager_contact',
        'bank_manager_email',
        'bank_financer_name',
        'bank_financer_contact',
        'bank_financer_email',
        'bank_financer_address',
        'bank_financer_aadhar_card',
        'bank_financer_pan_card',
        'active_status',
        'financer_type'
    ];

    protected  $hidden = [];

    protected $casts = [];
}
