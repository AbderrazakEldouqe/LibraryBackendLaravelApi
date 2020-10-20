<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->pivot->borrowed_book_id_public,
            'borrowing_date' => $this->pivot->borrowing_date,
            'receiving_date' => $this->pivot->receiving_date,
            'estimated_return_date' => $this->pivot->estimated_return_date,
            'return_date' => $this->pivot->return_date,
            'canceled_borrowed_book' => $this->pivot->canceled_borrowed_book,
            'book' => new BookResource($this)
        ];
    }
}
