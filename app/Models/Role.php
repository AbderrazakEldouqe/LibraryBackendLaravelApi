<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->role_id_public = Str::random(32);
        });
    }
}
