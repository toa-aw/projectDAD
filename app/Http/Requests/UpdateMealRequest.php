<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Meal;
use App\User;

class UpdateMealRequest extends FormRequest
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
            'state' => 'required',
            'userId' => 'required'
        ];
    }

    public function withValidator($validator)
    {       
        $validator->after(function ($validator) {
            $id = (int)$this->route()->parameters()['meal'];
            $order = Meal::findOrFail($id);
            $user = User::findOrFail($this->userId);

            if($this->state === 'active'){
                    $validator->errors()->add('state', 'You cant alter the state of this meal to terminated');
            }

            if($this->state === 'terminated'){
                if($order->state !== 'active'){
                    $validator->errors()->add('state', 'You cant alter the state of this meal to terminated');
                }
                
                if($user->type !== 'waiter'){
                    $validator->errors()->add('userId', 'You arent allowed to alter the state of this meal');
                }
            }
        });
    }
}
