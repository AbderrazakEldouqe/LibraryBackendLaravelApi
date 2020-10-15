<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'id' => $this->book_id_public,
            'isbn' => $this->isbn,
            'title' => $this->title,
            'edition' => $this->edition,
            'author' => $this->author,
            'sotck_quantity' => $this->sotck_quantity,
            'image' => $this->image,
            'description' => $this->description,
            'language_id'=> $this->language->language_id_public
        ];
    }
}
