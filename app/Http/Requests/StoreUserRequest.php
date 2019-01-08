<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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


    public function rules()
    {
        return [
            'name' => 'required|max:255|regex:/^[a-zA-Z ]+$/',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|max:255|unique:users,username',
            'type' =>  [
                'required',
                 Rule::in(['manager', 'cook', 'waiter', 'cashier'])
            ],
            'photo_url' => 'required'
        ];
    }

    public function messages(){
        return[
            'photo_url.required' => 'You must have a profile photo'
        ];
    }
}
