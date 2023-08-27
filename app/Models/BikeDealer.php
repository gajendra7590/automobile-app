<?php

namespace App\Models;

use App\Traits\CommonHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikeDealer extends Model
{
    use CommonHelper;
    use HasFactory;

    protected $table = 'bike_dealers';

    protected $fillable = [
        'branch_id',
        'dealer_code',
        'company_name',
        'company_email',
        'company_office_phone',
        'company_address',
        'company_gst_no',
        'company_more_detail',
        'contact_person',
        'contact_person_email',
        'contact_person_phone',
        'contact_person_phone2',
        'contact_person_address',
        'contact_person_document_type',
        'contact_person_document_file',
        'active_status'
    ];

    protected  $hidden = [];

    protected $casts = [];

    //Fitler by branch - local scope
    public function scopeBranchWise($query)
    {
        $branch_id = self::getCurrentUserBranch();
        if (!empty($branch_id)) {
            return $query->where('branch_id', $branch_id);
        }
    }

    public function purchase()
    {
        return $this->hasMany(Purchase::class, 'bike_dealer', 'id');
    }

    public function dealer_payment()
    {
        return $this->hasMany(PurchaseDealerPaymentHistory::class, 'dealer_id', 'id');
    }

    public function getContactPersonDocumentFileAttribute($value)
    {
        return env('APP_URL') . '/storage' . '/' . $value;
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
