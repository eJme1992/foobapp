<?php

namespace App;
use User;
use Illuminate\Database\Eloquent\Model;

class my_payment_methods extends Model
{
    protected $table = "my_payment_methods";
    
    public function Mi_Usuario(){
        return  $this->belongTo(User::class,'id_usuario');
    }

}
