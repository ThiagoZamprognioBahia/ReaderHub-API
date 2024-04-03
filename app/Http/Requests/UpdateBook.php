<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBook extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                => 'nullable|string',
            'genre_id'            => 'nullable|integer',
            'genre_name'          => 'nullable|string',
            'author'              => 'nullable|string',
            'year'                => 'nullable|integer',
            'pages'               => 'nullable|integer',
            'language'            => 'nullable|string',
            'edition'             => 'nullable|string',
            'publisher_id'        => 'nullable|integer',
            'publisher_name'      => 'nullable|string',
            'publisher_code'      => 'nullable|string',
            'publisher_telephone' => 'nullable|string',
            'isbn'                => 'nullable|string',
        ];
    }
}
