<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Categoria;

class CategoriaController extends Controller
{
    public function __construct()
    {
       //Le aplica la autentificacion por Login a todo el metodo que no este en except
       $this->middlewere('api.auth', ['except'=>['index','show']]);
    }

     public function index()
    {
        $categorias = Categoria::all();
         return response()->json(['categorias'=> $categorias , 'code'=>200,'status'=> 'succes']);
    }

     public function show($id)
    {  
        $categoria = Categoria::find($id);
        
         if(is_object($categoria)){
         
          return response()->json(['categoria'=> $categoria , 'code'=>200,'status'=> 'succes']);
        
         }else{
         
          return response()->json([ 'code'=>400,'status'=> 'error']);
        
         }  
    }

     public function store(Request $request)
    {
      
        // Recojo los parametros por post
        $json         = $request->input('json', null);
        $params_array = json_decode($json, true);
      
        // Validamos datos correctos
        if (empty($params_array))
        {
            $data = array(
                'status' => 'error',
                'code'   =>  404,
                'msj'    => 'Los datos de la peticion estan dañados',
            );
            return response()->json($data, $data['code']);
        }
            
        $params_array = array_map('trim', $params_array);

            $validador = \Validator::make($params_array, 
                [
                 'nombre'     => '|unique:categorias', 
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
                return response()->json($data, $data['code']);
            }
          
            // Inicio Logia Login
           
           $categoria = new Categoria();

           $categoria->nombre = $params_array['nombre'];
           $categoria->slug = str_shuffle($params_array['nombre'].date("Ymd").uniqid());
           $categoria->save();

           $data = array(
                    'status' => 'succes',
                    'code'   => 200,
                    'data'   =>  $user,
                    'msj'    => 'La edicion a sido hecha con exito',
            );
            // Fin Logica Login
    }

    public function update($id, Request $request)
    {

 // Recojo los parametros por post
        $json         = $request->input('json', null);
        $params_array = json_decode($json, true);
      
        // Validamos datos correctos
        if (empty($params_array))
        {
            $data = array(
                'status' => 'error',
                'code'   =>  404,
                'msj'    => 'Los datos de la peticion estan dañados',
            );
            return response()->json($data, $data['code']);
        }
            
            $params_array = array_map('trim', $params_array);

            $categoria    = Categoria::where('slug',$id);

            $validador    = \Validator::make($params_array, 
                [
                 'nombre'     => '|unique:categorias,nombre',.$categoria->id 
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
                return response()->json($data, $data['code']);
            }

            unset($params_array['id']);
            unset($params_array['slug']);
            unset($params_array['created_at']);
            
            $categoria->update($params_array);
          

           $data = array(
                    'status' => 'succes',
                    'code'   => 200,
                    'data'   =>  $params_array,
                    'msj'    => 'La edicion a sido hecha con exito',
            );
            // Fin Logica Login
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
