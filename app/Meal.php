<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{

    protected $fillable = [
        'state', 'table_number', 'start', 'end', 'responsible_waiter_id', 'total_price_preview'
    ];

    public function table()
    {
        return $this->belongsTo('App\Table', 'table_number', 'table_number');
    }

    public function waiter(){
        return $this->belongsTo('App\User', 'responsible_waiter_id');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }
}
