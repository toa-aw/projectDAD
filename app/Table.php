<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{

    protected $table = 'restaurant_tables';
    protected $primaryKey = 'table_number';

    public function meals()
    {
        return $this->hasMany('App\Meal', 'table_number', 'table_number');
    }
}
