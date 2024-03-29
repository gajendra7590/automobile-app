<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentSectionTypes extends Model
{
    use HasFactory;

    protected $table = 'document_section_types';

    protected $fillable = [
        'name',
        'short_name'
    ];

    protected  $hidden = [];

    protected $casts = [];
}
