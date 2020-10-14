<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Language extends Model
{
    use HasFactory;

    protected $table = 'languages';

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->language_id_public = Str::random(32);
        });
    }
}
