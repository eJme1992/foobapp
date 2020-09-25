<?php

namespace App;
use User;
use Illuminate\Database\Eloquent\Model;

class UserFile extends Model
{
    //
    public function Mi_Usuario(){
        return  $this->belongTo(User::class,'id_usuario');
    }
}
