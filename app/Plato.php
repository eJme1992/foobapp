<?php

namespace App;
use Ingrediente;
use Categoria;

use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
     protected $table = "platos";

     // Relacion de muchos a mucho 
      public function MisIngredientes(){
         return $this->belongsToMany(Ingrediente::class);
      }

       // Relacion de muchos a mucho 
      public function MisCategorias(){
         return $this->belongsToMany(Categoria::class);
      }
}
