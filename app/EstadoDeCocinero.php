<?php

namespace App;
use Cocinero;

use Illuminate\Database\Eloquent\Model;

class EstadoDeCocinero extends Model
{
      protected $table = "estado_del_cocinero";
      
      // Cocineros de un estado espesifico
      public function Cocineros(){
        return  $this->hasMany(Cocinero::class);
      }

}
