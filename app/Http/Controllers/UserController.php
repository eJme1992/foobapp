<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\EstadoDeUsuario;
use App\TipoDeUsuario;

class UserController extends Controller
{
    // Registro tipo Api Rest 
    public function login(Request $request)
    {
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        if (!empty($params_array))
        {
            $params_array = array_map('trim', $params_array);
            $validador = \Validator::make($params_array, 
                [
                 'email'      => 'required|email', 
                 'password'   => 'required|alpha_num', 
                ]
            );
            //Segun la respuesta continuo o no
            if ($validador->fails())
            {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'msj' => 'El usuario no se a podido logear correctamente',
                    'errors' => $validador->errors() 
                );
            }
            else
            {
            // Inicio Logia Login
            // Decifro la contraseña
             $password = hash('sha256',$params_array['password']);
            // Llamo a mi clase Logeo por JwtAuth la cual hace la logica en db
             $JwtAuth = new \JwtAuth();
            // Paso los datos
             $user = $JwtAuth->signup($params_array['email'],$password);
            // Datos de salida 
             $data = array(
                    'status' =>  $user['status'],
                    'code'   =>  200,
                    'msj'    =>  $user['msj'],
                    'data'   =>  $user
             );
            // Fin Logica Login
            }
        }
        else
        {
            $data = array(
                'status' => 'error',
                'code'   =>  404,
                'msj'    => 'El usuario no se a podido logear correctamente',
                'errors' => 'El Json no a sido escrito correctamente'
            );
        }
        return response()
            ->json($data, $data['code']);
    }
    // Registro tipo Api Rest 
    public function register(Request $request)
    {
        // Recibe Json
        $json = $request->input('json', null);
        // Decodifica el json
        $params_array = json_decode($json, true);
        //Varifico que los parametros esten llenos
        if (!empty($params_array))
        {
            //Limpio los datos de front (Saca espacios)
            $params_array = array_map('trim', $params_array);
            //Valida datos de forma automatica y responde en una variable
            $validador = \Validator::make($params_array, 
                [
                 'nombre'     => 'required|alpha', 
                 'apellido'   => 'required|alpha',
                 'email'      => 'required|email|unique:users', 
                  // unique:users valida automaticamente que usuario no esta repetido
                 'password'   => 'required|alpha_num', 
                 'numero'     => 'required|numeric|unique:users',
                ]
            );
            //Segun la respuesta continuo o no
            if ($validador->fails())
            {
                $data = array(
                    'status' => 'error',
                    'code'   => 404,
                    'msj'    =>  'el usuario no a sido creado',
                    'errors' => $validador->errors() 
                );
            }
            else
            {
                // Cifrado de contraseñas
                $password = hash('sha256',$params_array['password']);
                $user = new User;
                // Datos recibidos por el front
                $user->nombre    =  $params_array['nombre'];
                $user->apellido  =  $params_array['apellido'];
                $user->name      =  $params_array['nombre']." ".$params_array['apellido'];
                $user->email     =  $params_array['email'];
                $user->numero    =  $params_array['numero'];
                $user->password  =  $password;
                $user->slug      =  str_shuffle($user->name.date("Ymd").uniqid());
                $estado          =  EstadoDeUsuario::where('slug','activo')->first();
                $user->estado    =  $estado->id;
                $tipo            =  TipoDeUsuario::where('slug','consumidor')->first();
                $user->tipo      =  $tipo->id;
                $user->save();
                // Datos de salida 
                $data = array(
                    'status' => 'succes',
                    'code' => 200,
                    'msj' => 'el usuario ha sido creado',
                );
            }
        }
        else
        {
            $data = array(
                'status' => 'error',
                'code'   =>  404,
                'msj'    => 'el usuario no a sido creado ',
                'errors' => 'El Json no a sido escrito correctamente'
            );
        }
        return response()
            ->json($data, $data['code']);
    }

    public function index()
    {
        //
        
    }

    public function create()
    {
        //
        
    }

    public function store(Request $request)
    {
        //
        
    }

    public function show($id)
    {
        //
        
    }

    public function edit($id)
    {
        //
        
    }

    public function update(Request $request)
    {
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if($checkToken){
        // Recojo los parametros por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        if (!empty($params_array))
        {
            $params_array = array_map('trim', $params_array);

            // Sacamos el id del token
            $user = $jwtAuth->checkToken($token,true);
        
            $validador = \Validator::make($params_array, 
                [
                 'nombre'     => 'alpha', 
                 'apellido'   => 'alpha',
                 // unique:users valida automaticamente que usuario no esta repetido Si concateno con. hace un excecion con el mismo
                 'email'      => 'email|unique:users,email,'.$user->sub, 
                 'numero'     => 'numeric|unique:users,numero,'.$user->sub, 
                 'documento'  => 'numeric|unique:users,documento,'.$user->sub, 
                ]
            );
            //Segun la respuesta continuo o no
            if ($validador->fails())
            {
                $data = array(
                    'status' => 'error',
                    'code'   =>  404,
                    'msj'    => 'El usuario no se a podido logear correctamente',
                    'errors' => $validador->errors() 
                );
            }
            else
            {
            // Inicio Logia Login
            $user = User::where('id',$user->sub)->first();
          
            //  Descartos datos de la db que no quiero actualizar aunque vengan
            unset($params_array['id']);
            unset($params_array['email_verified_at']);
            unset($params_array['password']);
            unset($params_array['slug']);
            unset($params_array['estado']);
            unset($params_array['tipo']);
            unset($params_array['remember_token']);
            unset($params_array['created_at']);
            unset($params_array['tipo']);


          
           if(!empty($params_array['nombre']))
                   $params_array['name'] = $params_array['nombre']." ".$user->apellido;

           
           if(!empty($params_array['apellido']))
                   $params_array['name'] = $user->nombre." ".$params_array['apellido'];
           

           if(!empty($params_array['nombre']) AND !empty($params_array['apellido']))
                   $params_array['name'] = $params_array['nombre']." ".$params_array['apellido'];

           $user->update($params_array);
             
           $data = array(
                    'status' => 'succes',
                    'code'   => 200,
                    'data'   =>  $user,
                    'msj'    => 'el usuario ha sido creado',
             );
            // Fin Logica Login
            }
        }
        else
        {
            $data = array(
                'status' => 'error',
                'code'   =>  404,
                'msj'    => 'Los datos de la peticion estan dañados',
            );
        }
        }else{
               $data = array(
                'status' => 'error',
                'code'   =>  404,
                'msj'    => 'El Usuario no esta identificado',
            );
        }
             return response()
            ->json($data, $data['code']);
    }

  
    public function avatar(Request $request)
    {
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if($checkToken){
        // Recojo los parametros por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        if (!empty($params_array))
        {
            $params_array = array_map('trim', $params_array);

            // Sacamos el id del token
            $user = $jwtAuth->checkToken($token,true);
        
            $validador = \Validator::make($params_array, 
                [
                 'nombre'     => 'alpha', 
                 'apellido'   => 'alpha',
                 // unique:users valida automaticamente que usuario no esta repetido Si concateno con. hace un excecion con el mismo
                 'email'      => 'email|unique:users,email,'.$user->sub, 
                 'numero'     => 'numeric|unique:users,numero,'.$user->sub, 
                 'documento'  => 'numeric|unique:users,documento,'.$user->sub, 
                ]
            );
            //Segun la respuesta continuo o no
            if ($validador->fails())
            {
                $data = array(
                    'status' => 'error',
                    'code'   =>  404,
                    'msj'    => 'El usuario no se a podido logear correctamente',
                    'errors' => $validador->errors() 
                );
            }
            else
            {
            // Inicio Logia Login
       
             
           $data = array(
                    'status' => 'succes',
                    'code'   => 200,
                    'data'   =>  $user,
                    'msj'    => 'el usuario ha sido creado',
             );
            // Fin Logica Login
            }
        }
        else
        {
            $data = array(
                'status' => 'error',
                'code'   =>  404,
                'msj'    => 'Los datos de la peticion estan dañados',
            );
        }
        }else{
               $data = array(
                'status' => 'error',
                'code'   =>  404,
                'msj'    => 'El Usuario no esta identificado',
            );
        }
             return response($data, $data['code'])->header('Content-Type','text/plain');
        
    }






    public function destroy($id)
    {
        //
        
    }
}

