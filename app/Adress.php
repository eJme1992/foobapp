<?php

namespace App;
use User;
use Illuminate\Database\Eloquent\Model;

class Adress extends Model
{
    // Relacion uno a mucho inversa 
    public function Mi_Usuario(){
        return  $this->belongTo(User::class,'id_usuario');
    }
}
