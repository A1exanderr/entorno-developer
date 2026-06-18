<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Registro\Compra;
use App\Models\Registro\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Controlador_control extends Controller
{

    private $cantidadad_maxima;
    public function __construct()
    {
        // Inicializar la propiedad en el constructor
        $this->cantidadad_maxima = 120;
    }


    /**
     * PARA LA ADMINISTRACION DE CONTROL
     */
    public function control()
    {
        $data['menu'] = 20;
        return view('administrador.control.control', $data);
    }

    //para buscar la persona
    public function buscar_persona(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json(['items' => []], 400);
        }

        $personas = Persona::where(function ($q) use ($query) {
            $q->where('ci', 'LIKE', "%{$query}%")
                ->orWhere('nombres', 'LIKE', "%{$query}%")
                ->orWhere('ap_paterno', 'LIKE', "%{$query}%")
                ->orWhere('ap_materno', 'LIKE', "%{$query}%");
        })
            ->select('id', 'ci', 'nombres', 'ap_paterno', 'ap_materno')
            ->limit(10)
            ->get();

        return response()->json(['items' => $personas]);
    }

    //para el listado y control de persona
    public function lista_control_persona(Request $request)
    {
        $persona_id = $request->id;
        $persona = Persona::findOrFail($persona_id);

        $litrosConsumidosMes = Compra::where('id_persona', $persona_id)
            ->whereMonth('fecha_compra', now()->month)
            ->whereYear('fecha_compra', now()->year)
            ->sum('cantidad_litros');

        $gestion_mes = obtenerNombreMes(now()->month) . ' ' . now()->year;

        $litros_disponibles = $this->cantidadad_maxima - $litrosConsumidosMes;

        $compras = Compra::with(['estacion', 'persona', 'usuario'])
            ->where('id_persona', $persona_id)
            ->whereMonth('fecha_compra', now()->month)
            ->whereYear('fecha_compra', now()->year)
            ->orderBy('fecha_compra', 'desc')
            ->get();

        $ultimaCompra = Compra::where('id_persona', $persona_id)
            ->latest('fecha_compra')
            ->first();

        $mensaje_ultima_compra = $this->verificarTiempoUltimaCompra($ultimaCompra);

        return response()->json([
            'persona'               => $persona,
            'litros_disponibles'    => $litros_disponibles,
            'compras'               => $compras,
            'gestion_mes'           => $gestion_mes,
            'mensaje_ultima_compra' => $mensaje_ultima_compra,
        ]);
    }

    private function verificarTiempoUltimaCompra($ultimaCompra)
    {
        if (is_null($ultimaCompra)) {
            return 0;
        }

        $diferenciaHoras = now()->diffInHours($ultimaCompra->fecha_compra);
        $horasRestantes = 24 - $diferenciaHoras;

        if ($horasRestantes > 0) {
            return "Por favor, espere $horasRestantes " . ($horasRestantes == 1 ? "hora" : "horas") . " antes de realizar una nueva compra. Agradecemos su paciencia";
        }

        return 0;
    }


    //para validar la cantidad de litros
    public function valida_cantidad_persona(Request $request)
    {
        try {
            $persona_id = $request->persona_id;
            // Calcular la cantidad de litros comprados en el mes actual
            $litrosConsumidosMes = Compra::where('id_persona', $persona_id)
                ->whereMonth('fecha_compra', now()->month)
                ->whereYear('fecha_compra', now()->year)
                ->sum('cantidad_litros');
            //verificar la cantidad de litros disponibles
            $litrosDisponibles = $this->cantidadad_maxima - $litrosConsumidosMes;

            // Verificar si la persona tiene suficientes litros disponibles
            if ($litrosDisponibles < $request->cantidad_ingresado) {
                return response()->json(
                    mensaje_mostrar('error', 'No puede ingresar mas de ' . $litrosDisponibles),
                    400
                );
            }

            return response()->json(
                mensaje_mostrar('success', 'Puede seguir con la compra'),
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                mensaje_mostrar('error', 'Ocurrio un error de BD'),
                400
            );
        }
    }

    //PARA GUARDAR LA COMPRA
    public function compra_nuevo(Request $request)
    {
        try {
            $validar = Validator::make($request->all(), [
                'cantidad'    => 'required|numeric|min:1',
                'persona_id'  => 'required',
            ]);

            if ($validar->fails()) {
                return response()->json(mensaje_mostrar('errores', $validar->errors()), 400);
            } else {
                // Obtener la cantidad solicitada por el usuario
                $cantidadSolicitada = $request->cantidad;

                // Validar que la persona no exceda los 120 litros en el mes
                $persona_id = $request->persona_id;
                $litrosConsumidosMes = Compra::where('id_persona', $persona_id)
                    ->whereMonth('fecha_compra', now()->month)
                    ->whereYear('fecha_compra', now()->year)
                    ->sum('cantidad_litros');

                $litrosDisponibles = $this->cantidadad_maxima - $litrosConsumidosMes;

                if ($cantidadSolicitada > $litrosDisponibles) {
                    return response()->json(
                        mensaje_mostrar('error', 'No puede comprar más de ' . $litrosDisponibles . ' litros en este mes'),
                        400
                    );
                }

                // Si todo es válido, proceder a guardar la compra
                if (Auth::user()->id_estacion != null) {
                    $compra = new Compra();
                    $compra->fecha_compra = now();
                    $compra->cantidad_litros = $cantidadSolicitada;
                    $compra->id_estacion = Auth::user()->id_estacion;
                    $compra->id_persona = $persona_id;
                    $compra->id_usuario = Auth::user()->id;
                    $compra->save();

                    return response()->json([
                        'tipo'      => 'success',
                        'mensaje'   => 'Se guardó con éxito',
                        'persona_id' => $persona_id
                    ]);
                } else {
                    return response()->json(
                        mensaje_mostrar('error', 'No seleccionó ninguna estación, vuelva a iniciar la sesión'),
                        400
                    );
                }
            }
        } catch (\Throwable $th) {
            return response()->json(
                mensaje_mostrar('error', 'Ocurrio un error de BD'),
                400
            );
        }

    }


    /**
     * FIN DE LA ADMINISTRACION DE CONTROL
     */
}
