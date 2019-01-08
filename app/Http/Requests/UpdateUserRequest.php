<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;
use App\User;

class UpdateUserRequest extends FormRequest
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
        $id = (int)$this->route()->parameters()['user'];
        return [
            'name' => 'required|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id)
            ],
            'username' => [
                'required',
                'max:255',
                Rule::unique('users')->ignore($id)
            ],
            'type' =>  [
                'required',
                Rule::in(['manager', 'cook', 'waiter', 'cashier'])
            ],
            'photo_url'=> 'nullable'
        ];
    }
}
