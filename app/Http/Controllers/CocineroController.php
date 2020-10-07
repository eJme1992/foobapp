<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Cocinero;

class CocineroController extends Controller
{
    
  public function __construct()
    {
       //Le aplica la autentificacion por Login a todo el metodo que no este en except
       $this->middlewere('api.auth', ['except'=>['index','show']]);
    }


     public function index()
    {
        $cocineros = Cocinero::all();
         return response()->json(['Cocineros'=> $Cocineros , 'code'=>200,'status'=> 'succes']);
    }













}
