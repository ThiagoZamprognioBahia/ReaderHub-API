<?php

namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;

class BooksFilterService
{
    public function apply(Builder $query, array $filters)
    {
        foreach ($filters as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($query, $value);
            }
        }

        return $query;
    }

    protected function name(Builder $query, $value)
    {
        $query->where('name', 'like', '%' . $value . '%');
    }

    protected function genre(Builder $query, $value)
    {
        $query->whereHas('genre', function ($query) use ($value) {
            $query->where('name', 'like', '%' . $value . '%');
        });
    }

    protected function author(Builder $query, $value)
    {
        $query->where('author', 'like', '%' . $value . '%');
    }

    protected function publisher_name(Builder $query, $value)
    {
        $query->whereHas('publisher', function ($query) use ($value) {
            $query->where('name', 'like', '%' . $value . '%');
        });
    }

    protected function isbn(Builder $query, $value)
    {
        $query->where('isbn', 'like', '%' . $value . '%');
    }
}