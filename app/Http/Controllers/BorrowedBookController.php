<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Book;
use App\Models\BorrowedBook;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use JWTAuth;

class BorrowedBookController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
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
                return AppHelper::reservedSuccess($book_id, 'Book');
            }
        }
        return AppHelper::reservedError($book_id, 'Book');
//        $books_current_user = $this->user->books()->attach($borrowed_book,
//            [
//                'borrowing_date' => Carbon::now(),
//                'borrowed_book_id_public' => Str::random(32)
//            ]);

    }
}
