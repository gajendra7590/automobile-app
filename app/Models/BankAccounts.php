<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccounts extends Model
{
    use HasFactory;

    protected $table = 'bank_accounts';

    protected $fillable = [
        'bank_name',
        'bank_account_number',
        'bank_account_holder_name',
        'bank_ifsc_code',
        'bank_branch_name'
    ];

    protected  $hidden = [];

    protected $casts = [];
}
