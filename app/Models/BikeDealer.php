<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikeDealer extends Model
{
    use HasFactory;

    protected $table = 'bike_dealers';

    protected $fillable = [
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
    ];

    protected  $hidden = [];

    protected $casts = [];

    public function getContactPersonDocumentFileAttribute($value){
        return env('APP_URL').'/storage'.'/' . $value;
    }
}
