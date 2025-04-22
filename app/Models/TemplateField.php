<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateField extends Model
{
    use HasFactory;
    protected $fillable = [
        'form_template_id',
        'name',
        'label',
        'type',
        'required',
        'sort_order',
    ];

    public function template()
    {
        return $this->belongsTo(FormTemplate::class, 'form_template_id');
    }

    public function options()
    {
        return $this->hasMany(FieldOption::class, 'template_field_id');
    }
}
