<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReader extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'         => 'required|string',
            'last_name'    => 'required|string',
            'email'        => 'required|string|email|unique:readers,email',
            'telephone'    => 'required|string',
            'birthday'     => 'required|date_format:Y-m-d',
            'neighborhood' => 'required|string',
            'city'         => 'required|string',
            'zipcode'      => 'required|string',
            'street'       => 'required|string',
            'number'       => 'required|string',
            'complement'   => 'nullable|string',
            'password'     => 'required|string|min:8',
        ];

    }

    public function messages()
    {
        return [
            'email.unique' => 'The email entered already belongs to a reader',     
        ];
    }
}
