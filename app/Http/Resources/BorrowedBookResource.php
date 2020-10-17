<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BorrowedBookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->borrowed_book_id_public,
            'borrowing_date' => $this->borrowing_date,
            'receiving_date' => $this->receiving_date,
            'estimated_return_date' => $this->estimated_return_date,
            'return_date' => $this->return_date,
            'canceled_borrowed_book' => $this->canceled_borrowed_book,
            'book' => new BookResource($this->book),
            'user' => new UserResource($this->user)
        ];
    }
}
