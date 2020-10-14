<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id' => $this->user_id_public,
            'name' => $this->name,
            'email' => $this->email,
            'cin' => $this->cin,
            'phone_number' => $this->phone_number,
            'address' => $this->address
        ];
    }
}
