<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [

        'name',
        'type', 
        'description',
        'photo_url',
        'price'
    ];

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function invoices()
    {
        return $this->belongsToMany('App\Invoice', 'invoice_items')->withPivot('quantity', 'unit_price', 'sub_total_price');
    }

    
}
