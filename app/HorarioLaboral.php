<?php

namespace App;
use Cocinero;
use DiasDeSemana;

use Illuminate\Database\Eloquent\Model;

class HorarioLaboral extends Model
{
   protected $table = "dias_de_semana";

    // Un consinero puede tener un estado y un tipo
    public function miCocinero(){
        return  $this->belongTo(Cocinero::class,'id_cocinero');
    }

    public function miDia(){
        return  $this->belongTo(DiasDeSemana::class,'dia');
    }
    // 
   

}
