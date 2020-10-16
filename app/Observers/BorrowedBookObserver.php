<?php

namespace App\Observers;

use App\Helpers\AppHelper;
use App\Models\Book;
use App\Models\BorrowedBook;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Routing\ResponseFactory;

class BorrowedBookObserver
{
    public function creating(BorrowedBook $borrowedBook)
    {

    }

    /**
     * Handle the borrowed book "created" event.
     *
     * @param \App\Models\BorrowedBook $borrowedBook
     * @return void
     */
    public function created(BorrowedBook $borrowedBook)
    {

    }

    /**
     * Handle the borrowed book "updated" event.
     *
     * @param \App\Models\BorrowedBook $borrowedBook
     * @return void
     */
    public function updated(BorrowedBook $borrowedBook)
    {
        //
    }

    /**
     * Handle the borrowed book "deleted" event.
     *
     * @param \App\Models\BorrowedBook $borrowedBook
     * @return void
     */
    public function deleted(BorrowedBook $borrowedBook)
    {
        //
    }

    /**
     * Handle the borrowed book "restored" event.
     *
     * @param \App\Models\BorrowedBook $borrowedBook
     * @return void
     */
    public function restored(BorrowedBook $borrowedBook)
    {
        //
    }

    /**
     * Handle the borrowed book "force deleted" event.
     *
     * @param \App\Models\BorrowedBook $borrowedBook
     * @return void
     */
    public function forceDeleted(BorrowedBook $borrowedBook)
    {
        //
    }
}
