<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Resources\BookResource;
use App\Http\Resources\CategoryResource;
use App\Models\Book;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Http\Request;
use JWTAuth;

class BookController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        $books = Book::all();

        return BookResource::collection($books);
    }

    public function store(Request $request)
    {
        $language= Language::where('language_id_public', '=', $request->language_id)->first();
        if (!$language) {
            return AppHelper::notFoundError($request->language_id, 'Language');
        }
        $book = new Book();
        $book->isbn = $request->isbn;
        $book->title = $request->title;
        $book->edition = $request->edition;
        $book->author = $request->author;
        $book->sotck_quantity = $request->sotck_quantity;
        $book->image = $request->image;
        $book->description = $request->description;

        if ($language->books()->save($book)){
            return new BookResource($book);
        }
        else
            return AppHelper::storeError('book');
    }

    public function show($id)
    {
        $book = Book::where('book_id_public', '=', $id)->first();


        if (!$book) {
            return AppHelper::notFoundError($id, 'book');
        }
        return new BookResource($book);
    }

    public function update(Request $request, $id)
    {
        $book = Book::where('book_id_public', '=', $id)->first();

        if (!$book) {
            return AppHelper::notFoundError($id, 'book');
        }

        $language= Language::where('language_id_public', '=', $request->language_id)->first();
        if (!$language) {
            return AppHelper::notFoundError($request->language_id, 'Language');
        }

        $book->isbn = $request->isbn;
        $book->title = $request->title;
        $book->edition = $request->edition;
        $book->author = $request->author;
        $book->sotck_quantity = $request->sotck_quantity;
        $book->image = $request->image;
        $book->description = $request->description;
        $book->language()->associate($language);

        $updated = $book->save();

        if ($updated) {
            return new BookResource($book);
        } else {
            return AppHelper::updateError($id, 'book');
        }
    }

    public function destroy($id)
    {
        $book = Book::where('book_id_public', '=', $id)->first();

        if (!$book) {
            return AppHelper::notFoundError($id, 'book');
        }

        if ($book->delete()) {
            return AppHelper::deleteSuccess($id, 'book');
        } else {
            return AppHelper::deleteError($id, 'book');
        }
    }
}
