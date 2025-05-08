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
        'original_name',
    ];
    // Отношение к полю шаблона
    public function templateField()
    {
        return $this->belongsTo(TemplateField::class, 'template_field_id');
    }

    // Отношение к записи формы
    public function formEntry()
    {
        return $this->belongsTo(FormEntry::class);
    }
}
