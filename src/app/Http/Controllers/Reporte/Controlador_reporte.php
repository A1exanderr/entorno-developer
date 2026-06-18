<?php

namespace App\Http\Controllers\Reporte;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registro\Compra;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use Dompdf\Options;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class Controlador_reporte extends Controller
{
    //para la parte de los reportes
    public function reportes()
    {
        $data['menu'] = 30;
        $gestiones = Compra::selectRaw('YEAR(fecha_compra) as gestion')
            ->groupBy('gestion')
            ->orderBy('gestion')
            ->pluck('gestion');
        $data['listar_gestion'] = $gestiones;
        return view('administrador.reportes.vista_reporte', $data);
    }

    //para generar pdf
    public function generar_pdf(Request $request)
    {
        try {
            $request->validate([
                'mes' => 'required|integer|between:1,12',
                'gestion' => 'required|integer|min:2000'
            ]);

            $mes = $request->mes;
            $anio = $request->gestion;

            $reportes = Compra::with('persona')
                ->select(
                    'persona.ci as ci_persona',
                    'persona.nombres as nombre_persona',
                    'persona.ap_paterno as paterno_persona',
                    'persona.ap_materno as materno_persona',
                    DB::raw('SUM(compra.cantidad_litros) as total_litros_comprados'),
                    DB::raw('MONTH(compra.fecha_compra) as mes'),
                    DB::raw('YEAR(compra.fecha_compra) as anio')
                )
                ->join('persona', 'compra.id_persona', '=', 'persona.id')
                ->whereMonth('compra.fecha_compra', $mes)
                ->whereYear('compra.fecha_compra', $anio)
                ->groupBy(
                    'persona.ci',
                    'persona.nombres',
                    'persona.ap_paterno',
                    'persona.ap_materno',
                    DB::raw('MONTH(compra.fecha_compra)'),
                    DB::raw('YEAR(compra.fecha_compra)')
                )
                ->orderByDesc('total_litros_comprados')
                ->get();

            if ($reportes->isEmpty()) {
                return redirect()->back()->withErrors(['message' => 'No se encontraron datos para el mes y año especificados.']);
            }

            $data = [
                'reportes' => $reportes,
                'mes' => obtenerNombreMes($mes),
                'anio' => $anio
            ];

            $pdf = PDF::loadView('administrador.reportes.reporte_pdf', $data);
            $pdf->setPaper('letter', 'portrait');

            return $pdf->stream('reporte_mes' . $mes . $anio . '.pdf');
        } catch (\Exception $e) {
            Log::error('Error al generar PDF: ' . $e->getMessage());
            // Redirige con un mensaje de error
            return redirect()->back()->withErrors(['message' => 'Ha ocurrido un error al generar el PDF.']);
        }
    }
}
