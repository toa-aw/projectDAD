<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;

class Invoice extends JsonResource
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
            "id" => $this->id,
            "name" => $this->name,
            "state" => $this->state,
            "meal_id" => $this->meal_id,
            "nif" => $this->nif,
            "responsible_waiter" => $this->meal->waiter->name,
            "date" => $this->date,
            "total_price" => $this->total_price,
            "table_number" => $this->meal->table_number,
            "items" => $this->items->toArray()
        ];
    }
}
