<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Resources\BorrowedBookResource;
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
        $borrowingBooks = BorrowedBook::where('canceled_borrowed_book', '=', 0)
            ->whereNull('receiving_date')
            ->with('book')
            ->with('user')
            ->get();
        return BorrowedBookResource::collection($borrowingBooks);
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

    public function accordingReservation(Request $request)
    {
        $borrowed_book_id = $request->borrowed_book_id;
        $borrowedBook = BorrowedBook::where('borrowed_book_id_public', '=', $borrowed_book_id)->first();

        if (!$borrowedBook) {
            return AppHelper::notFoundError($borrowed_book_id, 'Borrowed Book');
        }
        $borrowedBook->receiving_date = Carbon::now();
        // check estamted date > date system
        $borrowedBook->estimated_return_date = $request->estimated_return_date;
        if ($borrowedBook->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Book has been delivered '
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Sorry, Book Cannot be delivered for the moment '
        ], 500);

    }

    public function returningBook(Request $request)
    {

        $borrowed_book_id = $request->borrowed_book_id;
        $borrowedBook = BorrowedBook::where('borrowed_book_id_public', '=', $borrowed_book_id)->first();

        if (!$borrowedBook) {
            return AppHelper::notFoundError($borrowed_book_id, 'Borrowed Book');
        }

        $borrowedBook->return_date = Carbon::now();

        if ($borrowedBook->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Book has been returned '
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Sorry, Book Cannot be returned for the moment '
        ], 500);
    }

    public function cancelingReservationBook(Request $request)
    {

        $borrowed_book_id = $request->borrowed_book_id;
        $borrowedBook = BorrowedBook::where('borrowed_book_id_public', '=', $borrowed_book_id)->first();

        if (!$borrowedBook) {
            return AppHelper::notFoundError($borrowed_book_id, 'Borrowed Book');
        }
        $book = Book::where('id', '=', $borrowedBook->book_id)->first();

        if (!$book) {
            return AppHelper::notFoundError($borrowedBook->book_id, 'book');
        }

        $borrowedBook->canceled_borrowed_book = true;

        if ($borrowedBook->save()) {
            $book->sotck_quantity++;
            if ($book->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Book with id ' . $book->book_id_public . ' has been canceled '
                ], 200);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'Sorry, Book with id ' . $book->book_id_public . ' Cannot be reserved '
        ], 500);
    }

    public function delayedBorrowedBooks()
    {
        $borrowedBook = BorrowedBook::where('estimated_return_date', '<', Carbon::now())
            ->where('canceled_borrowed_book', '=', 0)
            ->whereNull('return_date')
            ->with('book')
            ->with('user')
            ->get();
        return BorrowedBookResource::collection($borrowedBook);
    }

}
