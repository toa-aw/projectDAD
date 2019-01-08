<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Meal as MealResource;
use App\Http\Resources\Item as ItemResource;
use App\Order as Orderr;

class Order extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            
            'id' => $this->id,
            'state' => $this->state,
            'start' => $this->start,
            'end' => $this->end,
            'responsible_cook_id' => $this->responsible_cook_id,
            'item' => $this->item->name,
            'table' => $this->meal->table_number
        ];
    }
}
