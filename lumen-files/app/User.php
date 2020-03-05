<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model 
{
    public function todos()
    {
        return $this->hasMany('App\Todos');
    }
    
}
