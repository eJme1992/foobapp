<?php

namespace App;
use User;
use Country;
use City;
use Illuminate\Database\Eloquent\Model;

class Adress extends Model
{
    // Relacion uno a mucho inversa 
    public function Mi_Usuario(){
        return  $this->belongTo(User::class,'id_usuario');
    }

    public function Mi_Pais(){
        return  $this->belongTo(Country::class,'pais');
    }

    public function Mi_Provincia(){
        return  $this->belongTo(Province::class,'provincia');
    }

    public function Mi_ciudad(){
        return  $this->belongTo(City::class,'ciudad');
    }





 
}
