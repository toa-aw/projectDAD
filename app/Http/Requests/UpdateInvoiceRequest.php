<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class UpdateInvoiceRequest extends FormRequest
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
            'nif' => 'required|numeric|digits:9',
            'name' => 'required|max:255|regex:/^[a-zA-Z ]+$/'
        ];
    }
}
