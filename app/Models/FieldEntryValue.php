<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldEntryValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'template_field_id',
        'form_entry_id',
    ];
}
