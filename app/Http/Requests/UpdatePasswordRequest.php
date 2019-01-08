<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Hash;
use App\User;

class UpdatePasswordRequest extends FormRequest
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
            'current' => 'required',
            'new' => 'required|confirmed||min:3',
        ];
    }

    public function messages(){
        return[
            'new.confirmed' => 'Password must match its confirmation',
            'new.required' => 'The password field is required',
            'current.required' => 'Your current password is required',
            'new.min' => 'Your new password must have at least 3 characters'
        ];
    }

    public function withValidator($validator)
    {   
        $validator->after(function ($validator) {
            $id = $this->route()->parameters()['id'];
            $user = User::findOrFail($id);
            if(!Hash::check($this->current, $user->password)){
                $validator->errors()->add('current', 'Your current password is incorrect');
            }
        });
    } 
}
