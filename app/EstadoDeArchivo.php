<?php

namespace App;
use UserFile;

use Illuminate\Database\Eloquent\Model;

class EstadoDeArchivo extends Model
{
    protected $table = "estado_de_archivos";
    // Todo los archivo con el estado ----
    public function Files(){
        return  $this->hasMany(UserFile::class);
    }
}
