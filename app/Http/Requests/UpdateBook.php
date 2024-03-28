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
            'name'                => 'required|string',
            'genre_id'            => 'nullable|integer|required_without:genre_name',
            'genre_name'          => 'nullable|string|required_without:genre_id',
            'author'              => 'required|string',
            'year'                => 'required|integer',
            'pages'               => 'required|integer',
            'language'            => 'required|string',
            'edition'             => 'required|string',
            'publisher_id'        => 'nullable|integer',
            'publisher_name'      => 'nullable|string',
            'publisher_code'      => 'nullable|string',
            'publisher_telephone' => 'nullable|string',
            'isbn'                => 'nullable|string',
        ];
    }
}
