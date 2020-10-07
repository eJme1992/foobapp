<?php

namespace App;
use Plato;

use Illuminate\Database\Eloquent\Model;

class Ingrediente extends Model
{
    protected $table = "ingredientes";

     // Relacion de muchos a mucho 
      public function MisPlatos(){
         return $this->belongsToMany(Plato::class);
      }
}
