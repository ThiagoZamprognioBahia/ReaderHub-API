<?php

namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;

class ReadersFilterService
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

    protected function last_name(Builder $query, $value)
    {
        $query->where('last_name', 'like', '%' . $value . '%');
    }

    protected function email(Builder $query, $value)
    {
        $query->where('email', 'like', '%' . $value . '%');
    }

    protected function telephone(Builder $query, $value)
    {
        $query->where('telephone', 'like', '%' . $value . '%');
    }

    protected function birthday(Builder $query, $value)
    {
        $query->whereDate('birthday', $value);
    }
}