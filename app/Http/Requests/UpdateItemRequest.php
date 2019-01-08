<?php

namespace App\Http\Requests;


use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class UpdateItemRequest extends FormRequest
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
            'name' => 'required|string',
            'type' => [
                'required',
                 Rule::in(['drink', 'dish'])
            ],
            'description' => 'required|string',
            'photo_url' => 'nullable',
            'price' => 'required|numeric',      
        ];
    }
}
