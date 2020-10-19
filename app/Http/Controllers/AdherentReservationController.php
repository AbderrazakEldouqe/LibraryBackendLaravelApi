<?php

namespace App\Http\Controllers;

use App\Models\BorrowedBook;
use Illuminate\Http\Request;

class AdherentReservationController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function onGoingReservationByUser(){
        $borrowingBooks = $this->user->books()->where('canceled_borrowed_book', '=', 0)
            ->whereNull('receiving_date')
            ->get();
    }
}
