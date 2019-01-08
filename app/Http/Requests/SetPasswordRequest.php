<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\User;

class SetPasswordRequest extends FormRequest
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
            'new' => 'required|confirmed|min:3',
        ];
    }

    public function messages(){
        return[
            'new.confirmed' => 'Password must match its confirmation',
            'new.required' => 'The password field is required',
            'new.min' => 'Your new password must have at least 3 characters'
        ];
    }

    public function withValidator($validator)
    {   
        $validator->after(function ($validator) {
            $token = $this->route()->parameters()['token'];
            $user = User::where('activation_token', $token)->first();
            if($user == null){
                $validator->errors()->add('token', 'Your confirmation token has expired');
            }
        });
    }
}
