<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $guarded = [];

    public function books()
    {
        return $this->belongsToMany(
            Book::class,
            'category_books');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->category_id_public = Str::random(32);
            $query->books_count = 0;
        });
    }
}
