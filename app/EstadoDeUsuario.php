<?php

namespace App;
use User;

use Illuminate\Database\Eloquent\Model;

class EstadoDeUsuario extends Model
{
    
      // Usuarios en un estado en espesifico 
      public function Users(){
        return  $this->hasMany(User::class);
      }
}
