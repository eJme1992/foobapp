<?php

namespace App;
use Plato;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = "categorias";

     public function MisCategorias(){
         return $this->belongsToMany(Plato::class);
     }
}
