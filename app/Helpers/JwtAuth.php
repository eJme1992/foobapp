<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth{

	public $key;

	public function __construct(){
		$this->key = "edwinmogollon";
	}
	
     public function signup($email,$password,$getToken =null){
     $signup = false;
     
	//Buscar si existe el usuario
     $User = User::where([
        'email'    => $email,
        'password' => $password
     ])->first();

	 // Comprobar si son correctas 
     if(is_object($User)){
     	$signup = true;
     }	
     // Generar el token con los datos
     if($signup){
     
     $token = array(	
         'sub'   => $User->id,
         'email' => $User->email,
         'name'  => $User->name,
         'iat'   => time(),
         'exp'   => time() + (7*24*60*60),
      );

     var_dump($this->key);

     $jwt    = JWT::encode($token, $this->key,'HS256');
     $decode = JWT::decode($jwt,   $this->key,['HS256']);
     
     if(is_null($getToken)){
     	$data = array (
         'status'    =>  'succes',
         'data'      =>   $jwt,
         'msj'       =>  'Login con exito'
     	);
     }else{
       $data = array (
         'status'   =>  'succes',
         'data'     =>   $decode,
         'msj'      =>  'Login con exito'
     	);
     }


     }else {
     	$data = array (
         'status'   =>  'error',
         'msj'      =>  'Los datos no han sido encontrados'
     	);
     }	
	//devolver los datos decodificados o el token en funcion de un parametro
      return $data;
    }
    
    //JWT ES EL TOKEN Y getIdentity VALIDA SI PASAMOS O NO LOS DATOS DECODIFICADOS<<
    public function checkToken($jwt, $getIdentity=false){
      try{
       
       $jwt = str_replace('"','',$jwt);
       $auth = false;
       //Deciframos el token
       $decode = JWT::decode($jwt,   $this->key,['HS256']);
      // Normalmente puede dar uno de estos dos errores asi que usamos try catch
      }catch(\UnexpectedValueException $e){
           $auth = false;
      }catch(\DomainException $e){
            $auth = false;
      }
      if(!empty($decode) && is_object($decode) && isset($decode->sub)){
           $auth = true;
      }else{
           $auth = false;
      }
      // Si el parametro de entrada $getIdentity devolvemos la info del usuario si no solo si esta o no logueado
      if($getIdentity){
      	 return $decode;
      }
      return $auth;
}

}