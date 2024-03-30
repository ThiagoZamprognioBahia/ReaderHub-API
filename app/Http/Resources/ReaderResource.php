<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReaderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'last_name'        => $this->last_name,
            'email'            => $this->email,
            'telephone'        => $this->telephone,
            'birthday'         => $this->birthday,
            'total_books_read' => $this->total_books_read,
            'total_pages_read' => $this->total_pages_read,
            'neighborhood'     => $this->neighborhood,
            'city'             => $this->city,
            'zipcode'          => $this->zipcode,
            'street'           => $this->street,
            'number'           => $this->number,
            'complement'       => $this->complement,
        ];
    }
}
