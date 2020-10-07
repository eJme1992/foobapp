<?php

namespace App;
use my_payment_methods;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
     protected $table = "payment_methods";
     
      /* Encargo
      public function Users(){
        return  $this->hasMany(User::class);
      }*/

      public function my_payment_methods(){
        return  $this->hasMany(my_payment_methods::class);
      }
}
