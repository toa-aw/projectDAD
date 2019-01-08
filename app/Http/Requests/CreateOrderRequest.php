<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Item;
use App\Meal;

class CreateOrderRequest extends FormRequest
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
            'item_id' => 'required',
            'meal_id' => 'required',
        ];
    }

    public function messages(){
        return[
            'state.required' => 'The order must have a state',
            'item_id.required' => 'The order must have an Item associated to it',
            'meal_id.required' => 'The order must have an Meal associated to it'
        ];
    }

    public function withValidator($validator)
    {   
        $validator->after(function ($validator) {
            $meal = Meal::find($this->meal_id);
            $item = Item::find($this->item_id);

            if($meal == null){
                $validator->errors()->add('meal_id', 'You must associate an order to a valid meal');
            }

            if($meal->state !== 'active'){
                $validator->errors()->add('meal_id', 'This meal is not active');
            }

            if($item == null){
                $validator->errors()->add('item_id', 'This item is not on the menu');
            }

            if($this->state !== 'pending'){
                $validator->errors()->add('state', 'State is not valid');
            }
        });
    }
}
