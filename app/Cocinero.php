<?php

namespace App;

use EstadoDeCocinero;
use TipoDeCocinero;
use User;
use HorarioLaboral;
use Plato;

use Illuminate\Database\Eloquent\Model;

class Cocinero extends Model
{
    protected $table = "cocineros";

    // Un consinero puede tener un estado y un tipo
    public function Tipo(){
        return  $this->belongTo(TipoDeCocinero::class,'tipo');
    }

    public function Estado(){
       return  $this->belongTo(EstadoDeCocinero::class,'estado');
    }

    public function User(){
       return  $this->belongTo(User::class,'id_usuario');
    }
    // 
    // Mis direcciones
    public function Mi_Horario(){
        return  $this->hasMany(HorarioLaboral::class);
    }
    // Mis archivos
    public function MisPlatos(){
        return  $this->hasMany(Plato::class);
    }
    

    
}
