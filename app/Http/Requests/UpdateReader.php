<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReader extends FormRequest
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
        
        $readerId = $this->route('reader');
        
        return [
            'name'         => 'nullable|string',
            'last_name'    => 'nullable|string',
            'email'        => [
                'nullable',
                'string',
                'email',
                Rule::unique('readers')->ignore($readerId),
            ],
            'telephone'    => 'nullable|string',
            'birthday'     => 'nullable|date_format:Y-m-d',
            'neighborhood' => 'nullable|string',
            'city'         => 'nullable|string',
            'zipcode'      => 'nullable|string',
            'street'       => 'nullable|string',
            'number'       => 'nullable|string',
            'complement'   => 'nullable|string',
            'password'     => 'nullable|string|min:8',
        ];

    }

    public function messages()
    {
        return [
            'email.unique' => 'The email entered already belongs to a reader',     
        ];
    }
}
