<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\User;
use App\UserFile;
use App\EstadoDeUsuario;
use App\TipoDeUsuario;
use App\TipoDeArchivo;
use App\EstadoDeArchivo;
use App\my_payment_methods;
use App\PaymentMethod;


class UserController extends Controller
{
    
    /* ###################### LOGIN ####################### 
       ###################################################
       ##################################################
       ################################################
       ###############################################
       ######################### */

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
    /* ###################### REGISTRER ################### 
       ###################################################
       ##################################################
       ################################################
       ###############################################
       ######################### */

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
                $user     = new User;
                // Datos recibidos por el front
                $user->nombre    =  $params_array['nombre'];
                $user->apellido  =  $params_array['apellido'];
                //$user->name      =  $params_array['nombre']." ".$params_array['apellido'];
                $user->email     =  $params_array['email'];
                $user->numero    =  $params_array['numero'];
                $user->password  =  $password;
                $user->slug      =  str_shuffle($user->name.date("Ymd").uniqid());
                $estado          =  EstadoDeUsuario::where('slug','activo')->first();
                $user->estado    =  $estado->id;
                $tipo            =  TipoDeUsuario::where('slug','consumidor')->first();
                $user->tipo      =  $tipo->id;
                $user->save();
                $pagos                      = new my_payment_methods();
                $tipoPagos                  = PaymentMethod::where('slug','efectivo')->first();
                $pagos->status              = 1;
                $pagos->user_id             = $user->id;
                $pagos->payment_methods_id  = $tipoPagos->id;
                $pagos->save();
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
    
    /* ###################### EDICION DEL PERFIL ########## 
       ###################################################
       ##################################################
       ################################################
       ###############################################
       ######################### */

public function update(Request $request)
    {
        $token      = $request->header('Authorization');
        $jwtAuth    = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if($checkToken){
        // Recojo los parametros por post
        $json         = $request->input('json', null);
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
                    'msj'    => 'El Formulario no a sido llenado correctamente',
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
          
        /*   if(!empty($params_array['nombre']))
                   $params_array['name'] = $params_array['nombre']." ".$user->apellido;

          
           if(!empty($params_array['apellido']))
                   $params_array['name'] = $user->nombre." ".$params_array['apellido'];
          

           if(!empty($params_array['nombre']) AND !empty($params_array['apellido']))
                   $params_array['name'] = $params_array['nombre']." ".$params_array['apellido']; */

           $user->update($params_array);
            
           $data = array(
                    'status' => 'succes',
                    'code'   => 200,
                    'data'   =>  $user,
                    'msj'    => 'La edicion a sido hecha con exito',
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
    
    /* ########## SUBIDA DE ARCHIVOS DE USUARIOS ############# 
       ###################################################
       ##################################################
       ################################################
       ###############################################
       ######################### */
public function fileUser(Request $request, $tipo)
    {
     // Valido token con el middelwe
        //var_dump($validador);
       // exit();
     $validador = \Validator::make($request->all(), 
                [
                 'file0'     => 'required|image|mimes:jpg,png,jpeg,gif'
                ]
      );

     // Optiene datos del usuario que uso el metodo
     $jwtAuth = new \JwtAuth();
     $token   = $request->header('Authorization');
     $user    = $jwtAuth->checkToken($token,true);
     // Contiene la imagen
     $imagen = $request->file('file0');


     // Verifico que la imagen sea treu osea exista y !$validador->fails() valida a la vez
     if($imagen AND !$validador->fails()){
         // Crea el nombre de la imagen
         $image_name = time().$imagen->getClientOriginalName();
         // Guarda en el disco user dentro de la carpeta storage/ user la imagen 
         \Storage::disk('users')->put($image_name,\File::get($imagen));
      
         $File             = new UserFile();
         $File->id_usuario = $user->sub;
         $File->slug       = str_shuffle($image_name.$user->sub.date("Ymd").uniqid());;
         $File->nombre     = $image_name;
         $File->url        = 'users';
         $estado           = EstadoDeArchivo::where('slug','activo')->first();
         $Tipo             = TipoDeArchivo::where('slug',$tipo)->first();
         $File->estado     = $estado->id; 
         $File->tipo       = $Tipo->id;
         $File->save();
         // PARA MEJORAR EL SISTEMA PUEDE GUARDAR MULTIPLES AVATAR HAY QUE IDEAR COMO MARCAR CON ESTE METODO CUAL ES LA QUE SE CORRESPONDE CON EL USO ACTUAL

         $data = array(
                    'status' => 'succes',
                    'code'   => 200,
                    'data'   => $File,
                    'msj'    => 'El archivo ha sido subido correctamente',
         );

         return response()->json($data, $data['code']);
     }
    
    $data = array(
                'status' => 'error',
                'code'   =>  404,
                'msj'    => 'El archivo no a sido subido',
     );
     return response()->json($data, $data['code']);
        
    }

       /* ##### PETICIOS GENERAL DE ARCHIVOS DE USUARIOS ############# 
       ###################################################
       ##################################################
       ################################################
       ###############################################
       ######################### */

    public function getFile($filename)
    {
        
           $isset = \Storage::disk('users')->exists($filename);  
           if($isset){
              $file = \Storage::disk('users')->get($filename);  
              return new Response($file,200);
           }

          $data = array(
                'status' => 'error',
                'code'   =>  404,
                'msj'    => 'El archivo no a sido subido',
          );
          return response()->json($data, $data['code']);
    }
       /* ##### Peticio de archivo DE ARCHIVOS DE USUARIOS ############# 
       ###################################################
       ##################################################
       ################################################
       ###############################################
       ######################### */
     public function getFileUser($slug_user,$tipo)
    {
         $filename = UserFile::select('user_files.nombre')
         ->join('users','users.id','user_files.id_usuario')
         ->join('estado_de_archivos','estado_de_archivos.id','user_files.estado')
         ->join('tipo_de_archivos','tipo_de_archivos.id','user_files.tipo')
         ->where('users.slug',$slug_user)
         ->where('estado_de_archivos.slug','activo')
         ->where('tipo_de_archivos.slug',$tipo)
         ->orderBy('user_files.created_at')->first();
         if(is_object($filename)){
           $isset = \Storage::disk('users')->exists($filename->nombre);  
           if($isset){
              $file = \Storage::disk('users')->get($filename->nombre);  
              return new Response($file,200);
           }
         }
          $data = array(
                'status' => 'error',
                'code'   =>  404,
                'msj'    => 'El archivo no a sido subido',
          );
          return response()->json($data, $data['code']);   
    }
      /* ##### GET USUARIO ################################ 
       ###################################################
       ##################################################
       ################################################
       ###############################################
       ######################### */
    public function getUsuario($slug_user,$tipo=null)
    {
         $user = User::where('users.slug',$slug_user)->first();
     if (is_object($user)) {
           $data = array(
                'status' => 'success',
                'code'   =>  202,
                'data'    => $user,
          );
     }else{
           $data = array(
                'status' => 'error',
                'code'   =>  404,
                'msj'    => 'El Usuario no ha ido encontrado',
          );
     } 
          return response()->json($data, $data['code']); 
    }
    /* ##### GET USUARIO ################################ 
       ###################################################
       ##################################################
       ################################################
       ###############################################
       ######################### */


















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






    public function destroy($id)
    {
        //
        
    }
}

