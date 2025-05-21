<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;
        protected $fillable = [
            'user_id',
            'author_id',  // ← Добавьте эту строку
            'name',
            'affiliation',
            'email',
            'interests',
            'cited_by',
            'google_key',
            'id_user'
        ];

            public function user()
            {
                return $this->belongsTo(User::class);
            }
}
