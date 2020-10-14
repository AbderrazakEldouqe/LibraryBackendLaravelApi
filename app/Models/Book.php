<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
