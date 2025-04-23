<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category_id',
        'points',
        'form_template_id',
        'is_active',
        'single_entry',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'single_entry' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(FormTemplate::class, 'form_template_id');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(FormEntry::class);
    }
}

