<?php

namespace App\Http\Controllers\Persona;

use App\Http\Controllers\Controller;
use App\Models\Registro\Genero;
use App\Models\Registro\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Controlador_persona extends Controller
{
    //PARA LA ADMINISTRACION DE PERSONA
    public function persona()
    {
        $data['menu'] = 10;
        $data['genero'] = Genero::OrderBy('id', 'asc')->get();
        return view('administrador.persona.persona', $data);
    }

    //para validar la persona si existe o no
    public function persona_validar(Request $request)
    {
        try {
            // Busca la persona con su CI
            $persona = Persona::where('ci', $request->ci)->first();
            // Verifica si la persona existe
            if ($persona) {
                $data = [
                    'tipo'    => 'error',
                    'mensaje' => 'Ya existe el CI registrado!',
                    'persona' => $persona,
                ];
            } else {
                $data = [
                    'tipo'          => 'success',
                    'mensaje'       => 'Puede seguir con el registro!',
                ];
            }
        } catch (\Exception $e) {
            $data = [
                'tipo'    => 'error',
                'mensaje' => 'Ocurrió un error. Intente nuevamente.',
                'error'   => $e->getMessage(),
            ];
        }
        return response()->json($data);
    }


    //para guardar nuevo registro de persona
    public function persona_nuevo(Request $request)
    {
        $validar = Validator::make(
            $request->all(),
            [
                'ci'                => 'required|unique:persona,ci',
                'fecha_nacimiento'  => 'required|date|before_or_equal:' . now()->format('Y-m-d'),
                'genero'            => 'required',
                'nombres'           => 'required',
                'apellido_paterno'  => 'required',
                'direccion'         => 'required'
            ],
            [
                'fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser en el futuro.',
            ]
        );
        if ($validar->fails()) {
            $data = mensaje_mostrar('errores', $validar->errors());
        } else {
            $persona                    = new Persona();
            $persona->ci                = $request->ci;
            $persona->complemento       = $request->complemento;
            $persona->nombres           = $request->nombres;
            $persona->ap_paterno        = $request->apellido_paterno;
            $persona->ap_materno        = $request->apellido_materno;
            $persona->fecha_nacimiento  = $request->fecha_nacimiento;
            $persona->direccion         = $request->direccion;
            $persona->id_usuario        = Auth::user()->id;
            $persona->id_genero         = $request->genero;
            $persona->estado            = 'activo';
            $persona->save();

            if ($persona->id) {
                $data = mensaje_mostrar('success', 'Se realizo el registro con éxito ! ');
            } else {
                $data = mensaje_mostrar('error', 'Ocurrio un error al registrar ! ');
            }
        }
        return response()->json($data);
    }

    //Para listar la persona
    public function persona_listar(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $search = $request->input('search', '');

        $query = Persona::orderBy('id', 'desc');

        // Agrega búsqueda por nombre o CI
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ci', 'LIKE', "%{$search}%")
                    ->orWhereRaw("CONCAT(nombres, ' ', ap_paterno, ' ', ap_materno) LIKE ?", ["%{$search}%"]);
            });
        }

        $persona = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($persona);
    }

    //para editar person
    public function persona_editar(Request $request)
    {
        try {
            $persona = Persona::find($request->id);
            if ($persona) {
                $data = mensaje_mostrar('success', $persona);
            } else {
                $data = mensaje_mostrar('error', 'Ocurrio un error al editar');
            }
        } catch (\Throwable $th) {
            $data = mensaje_mostrar('error', 'Ocurrio un error al editar');
        }
        return response()->json($data);
    }

    //para guardar lo editado
    public function persona_editar_save(Request $request)
    {
        $validar = Validator::make(
            $request->all(),
            [
                'fecha_nacimiento_'  => 'required|date|before_or_equal:' . now()->format('Y-m-d'),
                'genero_'            => 'required',
                'nombres_'           => 'required',
                'apellido_paterno_'  => 'required',
                'direccion_'         => 'required'
            ],
            [
                'fecha_nacimiento_.before_or_equal' => 'La fecha de nacimiento no puede ser en el futuro.',
            ]
        );
        if ($validar->fails()) {
            $data = mensaje_mostrar('errores', $validar->errors());
        } else {
            $persona                    = Persona::find($request->id_persona);
            $persona->complemento       = $request->complemento_;
            $persona->nombres           = $request->nombres_;
            $persona->ap_paterno        = $request->apellido_paterno_;
            $persona->ap_materno        = $request->apellido_materno_;
            $persona->fecha_nacimiento  = $request->fecha_nacimiento_;
            $persona->direccion         = $request->direccion_;
            $persona->id_genero         = $request->genero_;
            $persona->save();
            if ($persona->id) {
                $data = array(
                    'tipo'      => 'success',
                    'mensaje'   => 'Se editó con éxito ! ',
                    'ci_rec1'   => $persona->ci
                );
            } else {
                $data = mensaje_mostrar('error', 'Ocurrio un error al registrar ! ');
            }
        }
        return response()->json($data);
    }

    //PARA CAMBIAR EL ESTADO
    public function persona_estado(Request $request)
    {
        try {
            $persona            = Persona::find($request->id);
            $persona->estado    = ($persona->estado == 'activo') ? 'inactivo' : 'activo';
            $persona->save();
            if ($persona->id) {
                $data = mensaje_mostrar('success', 'Se cambio el estado con éxito');
            } else {
                $data = mensaje_mostrar('error', 'Ocurrio un error al cambiar el estado');
            }
        } catch (\Throwable $th) {
            $data = mensaje_mostrar('error', 'Ocurrio un problema de BD');
        }
        return response()->json($data);
    }
}
