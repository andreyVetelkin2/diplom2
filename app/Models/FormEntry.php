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
        'comment',
        'status',
        'percent',
        'date_achievement'
    ];

//    protected $casts =[
//        'date_achievement' => 'date'
//    ];

    public function form()
    {
        return $this->belongsTo(Form::class,'form_id');
    }
    public function fieldEntryValues()
    {
        return $this->hasMany(FieldEntryValue::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'review' => 'На рассмотрении',
            'approved' => 'Принято',
            'rejected' => 'Отклонено',
            default => 'Неизвестно',
        };
    }


}
