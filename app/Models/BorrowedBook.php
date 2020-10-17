<?php

namespace App\Models;

use App\Observers\BorrowedBookObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BorrowedBook extends Model
{
    use HasFactory;

    protected $table = 'borrowed_books';

    protected static function boot()
    {
        parent::boot();
        BorrowedBook::observe(BorrowedBookObserver::class);
//        static::creating(function ($query) {
//            $query->borrowed_book_id_public = Str::random(32);
//        });
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
