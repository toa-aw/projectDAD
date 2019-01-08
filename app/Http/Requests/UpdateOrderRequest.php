<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;
use App\Order;
use App\Meal;
use App\User;

class UpdateOrderRequest extends FormRequest
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
            'userId' => 'required',
            'state' => [
                'required',
                Rule::in(['confirmed', 'in preparation', 'prepared', 'delivered', 'not delivered'])
                
            ]
        ];
    }

    public function withValidator($validator)
    {       
        $validator->after(function ($validator) {
            $id = (int)$this->route()->parameters()['id'];
            $order = Order::findOrFail($id);
            $user = User::findOrFail($this->userId);

            if($this->state === 'pending'){
                $validator->errors()->add('state', 'You cant alter this state of this order to confirmed');
            }

            if($this->state === 'confirmed'){
                if($order->state !== 'pending'){
                    $validator->errors()->add('state', 'You cant alter this state of this order to confirmed');
                }
                
                if($user->type !== 'waiter'){
                    $validator->errors()->add('userId', 'You arent allowed to alter the state of this order');
                }
            }

            if($this->state === 'in preparation' ){
              
                if($order->state !== 'confirmed'){
                    $validator->errors()->add('state', 'You cant alter this state of this order to in preparation');
                }
                
                if($user->type !== 'cook'){
                    $validator->errors()->add('userId', 'You arent allowed to alter the state of this order');
                }
            }

            if($this->state === 'prepared'){

                if($order->state !== 'in preparation'){
                    $validator->errors()->add('state', 'You cant alter this state of this order to prepared');
                }

                if($user->type !== 'cook'){
                    $validator->errors()->add('userId', 'You arent allowed to alter the state of this order');
                }
            }

            if($this->state === 'delivered' || $this->state === 'not delivered'){

                if($order->state !== 'prepared'){
                    $validator->errors()->add('state', 'You cant alter this state of this order to prepared');
                }
    
                if($user->type !== 'waiter'){
                    $validator->errors()->add('userId', 'You arent allowed to alter the state of this order');
                }
            }
        });
    }
}
