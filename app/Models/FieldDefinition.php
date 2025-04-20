<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldDefinition extends Model
{
    use HasFactory;
    protected $fillable = ['kegiatan_id', 'field_name', 'field_label', 'field_type', 'options'];

    protected $casts = [
        'options' => 'array',  // Mengkonversi field options menjadi array
    ];
}
