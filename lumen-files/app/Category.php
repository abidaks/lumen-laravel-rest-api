<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model 
{
    public function todos()
    {
    	return $this->hasMany('App\Todos');
    }
}
