<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Resources\BookResource;
use App\Http\Resources\ReservationResource;
use App\Models\Book;
use App\Models\BorrowedBook;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use JWTAuth;

class AdherentReservationController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function reserveBook(Request $request)
    {
        $book_id = $request->book_id;
        $book = Book::where('book_id_public', '=', $book_id)->first();

        if (!$book) {
            return AppHelper::notFoundError($book_id, 'book');
        }

        if ($book->sotck_quantity <= 0) {
            return AppHelper::LackOfQuantityStockBookError($book_id, 'Book');
        }

        $borrowed_book = new BorrowedBook();
        $borrowed_book->borrowing_date = Carbon::now();
        $borrowed_book->user_id = $this->user->id;
        $borrowed_book->book_id = $book->id;
        $borrowed_book->borrowed_book_id_public = Str::random(32);
        if ($borrowed_book->save()) {
            $book->sotck_quantity--;
            if ($book->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Book with id ' . $book_id . ' has been reserved '
                ], 200);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'Sorry, Book with id ' . $book_id . ' Cannot be reserved '
        ], 500);
//        $books_current_user = $this->user->books()->attach($borrowed_book,
//            [
//                'borrowing_date' => Carbon::now(),
//                'borrowed_book_id_public' => Str::random(32)
//            ]);

    }

    public function onGoingReservationByUser()
    {
        $borrowingBooks = $this->user->books()->where('canceled_borrowed_book', '=', 0)
            ->whereNull('receiving_date')
            ->get();
//        $borrowingBooks = $this->user->books()->pivot->canceled_borrowed_book
//            ->get();
        //$booksUser = $this->user->books;
//        foreach ($booksUser as $role) {
//            echo $role->pivot->user_id;
//        }
        return ReservationResource::collection($borrowingBooks);
        //return $borrowingBooks;
    }

    public function unreturnedBooksByUser()
    {
        $borrowingBooks = $this->user->books()->where('canceled_borrowed_book', '=', 0)
            ->whereNotNull('receiving_date')
            ->whereNotNull('estimated_return_date')
            ->whereNull('return_date')
            ->get();

        return ReservationResource::collection($borrowingBooks);
    }

    public function returnedBooksByUser()
    {
        $borrowingBooks = $this->user->books()->where('canceled_borrowed_book', '=', 0)
            ->whereNotNull('receiving_date')
            ->whereNotNull('estimated_return_date')
            ->whereNotNull('return_date')
            ->get();

        return ReservationResource::collection($borrowingBooks);
    }

    public function canceledBooksByUser()
    {
        $borrowingBooks = $this->user->books()->where('canceled_borrowed_book', '=', 1)
            ->get();

        return ReservationResource::collection($borrowingBooks);
    }
}
