<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_books');
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'borrowed_books');
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->book_id_public = Str::random(32);
        });
    }
}
