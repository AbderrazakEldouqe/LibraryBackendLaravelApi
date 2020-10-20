<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Book extends Model
{
    use HasFactory;

    protected $table = 'books';
    protected $guarded = [];

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
            'borrowed_books')
            ->as('pivot')
            ->withPivot('borrowing_date','receiving_date','estimated_return_date','return_date','canceled_borrowed_book','borrowed_book_id_public');
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->book_id_public = Str::random(32);
        });
    }
}
