<?php

namespace App;

use Adress;
use UserFile;
use my_payment_methods;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    // Mis direcciones
    public function direcciones(){
        return  $this->hasMany(Adress::class);
    }
    
    // Mis archivos
    public function user_files(){
        return  $this->hasMany(UserFile::class);
    }
    
    //
    public function my_payment_methods(){
        return  $this->hasMany(my_payment_methods::class);
    }





    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
