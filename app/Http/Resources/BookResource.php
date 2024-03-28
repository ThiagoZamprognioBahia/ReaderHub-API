<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'genre'     => $this->genre ? [
                'id'        => $this->genre->id,
                'name'      => $this->genre->name,
            ] : null,
            'author'    => $this->author,
            'year'      => $this->year,
            'pages'     => $this->pages,
            'language'  => $this->language,
            'edition'   => $this->edition,
            'publisher' => $this->publisher ? [
                'id'        => $this->publisher->id,
                'name'      => $this->publisher->name,
                'code'      => $this->publisher->code,
                'telephone' => $this->publisher->telephone,
            ] : null,
            'isbn'      => $this->isbn,
        ];
    }
}
