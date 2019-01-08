<?php

namespace App\Http\Requests;

use App\Http\Request\RequestsForm;
use Illuminate\Validation\Rule;
use App\User;
use App\Meal;


class CreateMealRequest extends FormRequest
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
        $occupiedTables = Meal::select('table_number')->where('state', 'active')->get();

        foreach ($occupiedTables->toArray() as $key => $value) {
            $tableNumbers[] = $value['table_number'];
        }
        
        return [
            'state' =>  [
                'required',
                 Rule::in(['active'])
            ],
            'table_number' => [
                'required', 
                Rule::notIn($tableNumbers)
            ],
            'responsible_waiter_id' => 'required', 
        ];
    }

    public function messages(){
        return[
        ];
    }

    public function withValidator($validator)
    {   
        $validator->after(function ($validator) {
            $user = User::find($this->responsible_waiter_id);
            if($user === null || $user->type !== 'waiter'){
                $validator->errors()->add('userType', "You don't have permission to perform this action");
            }
        });
    } 
}
