@extends('principal')
@section('titulo', '| INICIO')
@section('contenido')
    <!-- /Line Chart -->
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-subtitle text-muted mb-1">ESTACION : @if ($usuario_estacion->estacion)
                            {{ $usuario_estacion->estacion->nombre }}
                        @endif
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <!-- Add ESTACION Modal -->
    <div class="modal fade" id="modal_estacion_agregar" aria-hidden="true" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3 p-md-1">
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Seleccione Estación</h3>
                    </div>
                    <form id="formulario_estacion" class="row" method="POST">
                        @csrf
                        <div class="col-12 mb-3">
                            <label class="form-label" for="estacion">Seleccione la Estación</label>
                            <select name="estacion" id="estacion" class="select2">
                                <option value="null" selected disabled>[ SELECCIONE ESTACIÓN ]</option>
                                @foreach ($estacion as $lis)
                                    <option value="{{ $lis->id }}" > {{ $lis->nombre }} </option>
                                @endforeach
                            </select>
                            <div id="_estacion"></div>
                        </div>
                    </form>
                    <div class="col-12 text-center demo-vertical-spacing">
                        <button id="btn_guardar_nuevo_estacion" class="btn btn-primary me-sm-3 me-1">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Add ESTACION Modal -->


@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Usa una forma segura para pasar el valor de PHP a JavaScript
            let id_estacion = @json(Auth::user()->id_estacion);

            // Verifica si id_estacion es null o undefined
            if (id_estacion === null || id_estacion === undefined) {
                elegir_estacion();
            }

            function elegir_estacion() {
                $('#modal_estacion_agregar').modal('show');
            }
        });

        let btn_new_estacion = document.getElementById('btn_guardar_nuevo_estacion');
        let form_new_estacion = document.getElementById('formulario_estacion');
        btn_new_estacion.addEventListener('click', async()=>{
            let datos = Object.fromEntries(new FormData(form_new_estacion).entries());
            let estacion = document.getElementById('estacion');
            estacion.innerHTML = '';
            validar_boton(true, 'Verificando datos . . . ', 'btn_guardar_nuevo_estacion');
            try {
                let respuesta = await fetch("{{ route('sav_estacion') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(datos)
                });
                let dato = await respuesta.json();
                estacion.innerHTML = '';
                if (dato.tipo === 'errores') {
                    let obj = dato.mensaje;
                    for (let key in obj) {
                        document.getElementById('_' + key).innerHTML = `<p class="text-sm text-danger" >` + obj[
                            key] + `</p>`;
                    }
                    validar_boton(false, 'Guardar', 'btn_guardar_nuevo_estacion');
                }
                if (dato.tipo === 'success') {
                    alerta_top(dato.tipo, dato.mensaje);
                    validar_boton(false, 'Guardar', 'btn_guardar_nuevo_estacion');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
                if (dato.tipo === 'error') {
                    alerta_top(dato.tipo, dato.mensaje);
                    validar_boton(false, 'Guardar', 'btn_guardar_nuevo_estacion');
                }
            } catch (error) {
                console.log('Ocurrio un error :' + error);
                validar_boton(false, 'Guardar', 'btn_guardar_nuevo_estacion');
            }
        });
    </script>
@endsection
