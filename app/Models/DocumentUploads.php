<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentUploads extends Model
{
    use HasFactory;

    protected $table = 'document_uploads';

    protected $fillable = [
        'section_type',
        'section_id',
        'file_name',
        'file_extention',
        'file_mime_type',
        'file_size',
        'file_description'
    ];

    protected  $hidden = [];

    protected $casts = [];

    public function getFileNameAttribute($value)
    {
        return asset("/uploads/$value");
    }


    /**
     * section_type
     */
    public function sectionType()
    {
        return $this->belongsTo(DocumentSectionTypes::class, 'section_type');
    }
}
