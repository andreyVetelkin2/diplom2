<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_template_id',
        'user_id',
        'form_id',
        'status',
        'date_achievement'
    ];

    public function form()
    {
        return $this->belongsTo(Form::class,'form_id');
    }
    public function fieldValues()
    {
        return $this->belongsToMany(FieldEntryValue::class,'form_entry_id');
    }


}
