<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function fields()
    {
        return $this->hasMany(TemplateField::class);
    }
}
