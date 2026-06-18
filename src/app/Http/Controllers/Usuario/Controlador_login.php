<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\Registro\Estacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class Controlador_login extends Controller
{
    /**
     * @version 1.0
     * @author  Rodrigo Lecoña Quispe <rodrigolecona03@gmail.com>
     * @param Controlador Administrar la parte de usuario resgistrados LOGIN
     * ¡Muchas gracias por preferirnos! Esperamos poder servirte nuevamente
     */

    /**
     * PARA EL INGRESO DEL USUARIO POR USUARIO Y CONTRASEÑA
     */
    public function ingresar(Request $request){
        $mensaje = "Usuario y contraseña invalidos";
        $validar = Validator::make($request->all(),[
            'usuario'=>'required',
            'password'=>'required'
        ]);
        if($validar->fails()){
            $data = mensaje_mostrar('error', 'Todos los campos son requeridos');
        }else{
            $usuario = User::where('usuario', $request->usuario)->get();
            if(!$usuario->isEmpty()){
                $compara = Auth::attempt([
                    'usuario'       => $request->usuario,
                    'password'      => $request->password,
                    'estado'        => 'activo',
                    'deleted_at'    => NULL
                ]);
                if($compara){
                    $usuario_id = Auth::user()->id;
                    $usuario_update = User::find($usuario_id);
                    $usuario_update->id_estacion = null;
                    $usuario_update->save();
                    $data = mensaje_mostrar('success', 'Inicion de session con éxito');
                    $request->session()->regenerate();
                }else{
                    $data = mensaje_mostrar('error', $mensaje);
                }
            }else{
                $data = mensaje_mostrar('error', $mensaje);
            }
        }
        return response()->json($data);
    }
    /**
     * FIN PARA EL INGRESO DEL USUARIO Y CONTRASEÑA
     */

    /**
     * PARA INGRESAR AL INICIO
    */
    public function inicio(){
        $data['menu']   = 0;
        $data['usuario_estacion'] = User::with(['estacion'])->find(Auth::user()->id);
        $data['estacion'] = Estacion::get();
        return view('inicio', $data);
    }
    /**
     * FIN PARA INGRESAR AL INICIO
     */



    /**
     * PARA MOSTRAR EL MENSAJE AL INICIO
     */
    public function mensaje(){

        if (!Session::has('bienvenida_mostrada')) {
            $usuario = Auth::user()->nombres.' '.Auth::user()->apellidos;
            $mensaje = "¡Bienvenido al sistema RLQ v1: Usuario $usuario!";

            // Almacena en sesión que se mostró el mensaje
            Session::put('bienvenida_mostrada', true);

            $data = mensaje_mostrar('success', $mensaje);

            return response()->json($data);
        }

        return response()->json(['tipo' => 'error', 'mensaje' => 'Bienvenido de nuevo.']);
    }
    /**
     * FIN PARA MOSTRAR EL MENSAJE AL INICIO
     */

    /**
     * CERRAR LA SESSIÓN
     */
    public function cerrar_session(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $data = mensaje_mostrar('success', 'Finalizó la session con éxito!');
        return response()->json($data);
    }
    /**
     * FIN DE CERRAR LA SESSIÓN
     */
}
