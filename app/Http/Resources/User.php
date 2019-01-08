<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->photo_url = $this->photo_url ? asset('storage/profiles/'.$this->photo_url) : 'http://placehold.it/64x85';
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'type' => $this->type,
            'photo_url' => $this->photo_url, 
            'shift_start' => $this->last_shift_start,
            'shift_end' => $this->last_shift_end,
            'shift_active' => (bool) $this->shift_active,
            'blocked' => (bool) $this->blocked,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
