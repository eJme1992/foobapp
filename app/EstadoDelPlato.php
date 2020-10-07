<?php

namespace App;
use Plato;

use Illuminate\Database\Eloquent\Model;

class EstadoDelPlato extends Model
{
      protected $table = "estado_del_plato";
      
      // Plastos en un estado en espesifico 
      public function Platos(){
        return  $this->hasMany(Plato::class);
      }
}
