@extends('principal')
@section('titulo', '| PERSONA')
@section('estilos')
    <style>
        /* Estilo mejorado para la paginación */
        .pagination {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 0;
            margin: 0;
        }

        .pagination .page-item {
            margin: 0 2px;
        }

        .pagination .page-link {
            display: block;
            padding: 0.5rem 0.75rem;
            border: 1px solid #007bff;
            border-radius: 0.375rem;
            color: #007bff;
            text-align: center;
            transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out;
        }

        .pagination .page-link:hover {
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
            font-weight: 600;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: transparent;
            border-color: #dee2e6;
        }
    </style>
@endsection

@section('contenido')


    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"> :::::::: PERSONA :::::::: </h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_nueva_persona">
                <i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Nuevo</span>
            </button>
        </div>
        <div class="table-responsive text-nowrap p-3">
            <input type="text" id="search" placeholder="Buscar..." oninput="listarPersonas(1)"
                class="form-control mb-3">
            <table class="table table-sm" id="tabla_persona" style="width: 100%">
                <thead class="table-dark">
                    <tr>
                        <th>ACCION</th>
                        <th>CI</th>
                        <th>NOMBRES</th>
                        <th>FECHA NACIMIENTO</th>
                        <th>ESTADO</th>
                    </tr>
                </thead>

            </table>
            <div class="py-2">
                <div id="pagination" class="mt-3"></div>
            </div>

        </div>
    </div>


    <!-- Modal -->
    <!-- Add tipo de persona Modal -->
    <div class="modal fade" id="modal_nueva_persona" aria-hidden="true" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content p-1 p-md-2">
                <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"
                    onclick="cerrar_modal_registro_persona()"></button>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Registro nueva Persona</h3>
                    </div>
                    <form id="form_nueva_persona" class="row" method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label" for="ci">CI</label>
                                <input type="text" id="ci" name="ci" placeholder="Ingrese el CI"
                                    onkeyup="validar_ci(this.value)" class="form-control uppercase-input"
                                    onkeypress="return soloNumeros(event)" autofocus />
                                <div id="_ci"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label" for="complemento">Complemento</label>
                                <input type="text" id="complemento" name="complemento"
                                    class="form-control uppercase-input" placeholder="Ingrese complemento" autofocus
                                    @disabled(true)  maxlength="4"/>
                                <div id="_complemento"></div>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label" for="fecha_nacimiento">Fecha de nacimiento</label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                                    class="form-control uppercase-input" placeholder="Ingrese la fecha de nacimiento"
                                    autofocus max="{{ date('Y-m-d') }}" onkeypress="return soloNumeros(event)"
                                    @disabled(true) />
                                <div id="_fecha_nacimiento"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label" for="genero">Seleccione Género</label>
                                <select name="genero" id="genero" class="select2" @disabled(true)>
                                    <option disabled selected value="selected">[ SELECCIONE GÉNERO ]</option>
                                    @foreach ($genero as $lis)
                                        <option value="{{ $lis->id }}">[{{ $lis->sigla }}] - [{{ $lis->genero }}]
                                        </option>
                                    @endforeach
                                </select>
                                <div id="_genero"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label" for="nombres">Nombres</label>
                                <input type="text" id="nombres" name="nombres" class="form-control uppercase-input"
                                    placeholder="Ingrese nombres" onkeypress="return soloLetras(event)"
                                    @disabled(true) />
                                <div id="_nombres"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label" for="apellido_paterno">Apellido Paterno</label>
                                <input type="text" id="apellido_paterno" name="apellido_paterno"
                                    class="form-control uppercase-input" placeholder="Ingrese apellido paterno"
                                    onkeypress="return soloLetras(event)" @disabled(true) />
                                <div id="_apellido_paterno"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label" for="apellido_materno">Apellido Materno</label>
                                <input type="text" id="apellido_materno" name="apellido_materno"
                                    class="form-control uppercase-input" placeholder="Ingrese apellido materno"
                                    onkeypress="return soloLetras(event)" @disabled(true) />
                                <div id="_apellido_materno"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label for="direccion">Dirección</label>
                                <textarea class="form-control" name="direccion" id="direccion" placeholder="Ingrese la dirección" cols="30"
                                    rows="2" @disabled(true)></textarea>
                                <div id="_direccion"></div>
                            </div>
                        </div>
                    </form>
                    <div class="col-12 text-center demo-vertical-spacing">
                        <button id="btn_guardar_registro_persona" class="btn btn-primary me-sm-3 me-1">Guardar</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close" onclick="cerrar_modal_registro_persona()">Cerrar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--/ Add tipo de persona Modal -->


    <!-- Modal -->
    <!-- Add tipo de update Modal -->
    <div class="modal fade" id="modal_editar_persona" aria-hidden="true" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content p-1 p-md-2">
                <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"
                    onclick="cerrar_modal_update_persona()"></button>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Registro Editar Persona</h3>
                    </div>
                    <form id="form_editar_persona" class="row" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id_persona" id="id_persona">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label" for="complemento_">Complemento</label>
                                <input type="text" id="complemento_" name="complemento_"
                                    class="form-control uppercase-input" placeholder="Ingrese complemento" autofocus maxlength="5" />
                                <div id="_complemento_"></div>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label" for="fecha_nacimiento">Fecha de nacimiento</label>
                                <input type="date" id="fecha_nacimiento_" name="fecha_nacimiento_"
                                    class="form-control uppercase-input" placeholder="Ingrese la fecha de nacimiento"
                                    autofocus max="{{ date('Y-m-d') }}" onkeypress="return soloNumeros(event)" />
                                <div id="_fecha_nacimiento_"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label" for="genero_">Seleccione Género</label>
                                <select name="genero_" id="genero_" class="select2">
                                    <option disabled selected value="selected">[ SELECCIONE GÉNERO ]</option>
                                    @foreach ($genero as $lis)
                                        <option value="{{ $lis->id }}">[{{ $lis->sigla }}] - [{{ $lis->genero }}]
                                        </option>
                                    @endforeach
                                </select>
                                <div id="_genero"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label" for="nombres_">Nombres</label>
                                <input type="text" id="nombres_" name="nombres_"
                                    class="form-control uppercase-input" placeholder="Ingrese nombres"
                                    onkeypress="return soloLetras(event)" />
                                <div id="_nombres_"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label" for="apellido_paterno_">Apellido Paterno</label>
                                <input type="text" id="apellido_paterno_" name="apellido_paterno_"
                                    class="form-control uppercase-input" placeholder="Ingrese apellido paterno"
                                    onkeypress="return soloLetras(event)" />
                                <div id="_apellido_paterno_"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label" for="apellido_materno_">Apellido Materno</label>
                                <input type="text" id="apellido_materno_" name="apellido_materno_"
                                    class="form-control uppercase-input" placeholder="Ingrese apellido materno"
                                    onkeypress="return soloLetras(event)" />
                                <div id="_apellido_materno_"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label for="direccion">Dirección</label>
                                <textarea class="form-control" name="direccion_" id="direccion_" placeholder="Ingrese la dirección" cols="30"
                                    rows="2"></textarea>
                                <div id="_direccion_"></div>
                            </div>
                        </div>
                    </form>
                    <div class="col-12 text-center demo-vertical-spacing">
                        <button id="btn_guardar_editar_persona" class="btn btn-primary me-sm-3 me-1">Guardar</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close" onclick="cerrar_modal_update_persona()">Cerrar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--/ Add tipo de update Modal -->

@endsection

@section('scripts')
    <script>

    document.getElementById("complemento").addEventListener("input", (e) => {
        let value = e.target.value;
        // Reemplaza cualquier carácter que no sea letra mayúscula, dígito o guion
        value = value.replace(/[^A-Z\d-]/g, "");
        // Limita la longitud a 4 caracteres
        e.target.value = value.substring(0, 6);
    });

    document.getElementById("complemento_").addEventListener("input", (e) => {
        let value = e.target.value;
        // Reemplaza cualquier carácter que no sea letra mayúscula, dígito o guion
        value = value.replace(/[^A-Z\d-]/g, "");
        // Limita la longitud a 4 caracteres
        e.target.value = value.substring(0, 6);
    });


        //para validar ci
        async function validar_ci(ci) {
            let hos_habilitar = document.getElementById('switch-input');
            if (ci.length >= 5) {
                try {
                    let respuesta = await fetch("{{ route('cper_validar') }}", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            ci: ci
                        })
                    });
                    let dato = await respuesta.json();
                    if (dato.tipo === 'success') {
                        habilitar_deshabiltar(false);
                        vaciar_input();
                        vaciar_errores_registro_persona();
                    }
                    if (dato.tipo === 'error') {
                        alerta_top(dato.tipo, dato.mensaje);
                        habilitar_deshabiltar(true);
                        vaciar_input();
                        vaciar_errores_registro_persona();
                    }
                    if (dato.persona_hab === 'no_registrado') {
                        hos_habilitar.disabled = false;
                        hos_habilitar.checked = false;
                    }

                    if (dato.persona_hab === 'no_esta_vacia') {
                        hos_habilitar.disabled = true;
                        hos_habilitar.checked = true;
                    }

                    if (dato.persona_hab === 'no_muestra') {
                        hos_habilitar.disabled = true;
                        hos_habilitar.checked = false;
                    }
                } catch (error) {
                    console.log('Error :>> ', error);
                }
            } else {
                habilitar_deshabiltar(true);
                vaciar_input();
                vaciar_errores_registro_persona();
            }
        }

        //para bloquear los inputs o no
        function habilitar_deshabiltar(valor) {
            let valores = ['complemento', 'fecha_nacimiento', 'nombres', 'apellido_paterno', 'apellido_materno',
            'direccion', 'genero'
            ];
            valores.forEach(elem => {
                if (valor === true) {
                    document.getElementById(elem).disabled = valor;
                } else {
                    document.getElementById(elem).disabled = valor;
                }
            });
        }

        //para cerar el modal de la persona
        function cerrar_modal_registro_persona() {
            $('#modal_nueva_persona').modal('hide');
            //para vaciar el ci
            document.getElementById('ci').value = '';

            vaciar_input();
            vaciar_errores_registro_persona();
            habilitar_deshabiltar(true);
        }
        //para vaciar los inputs
        function vaciar_input() {
            let persona1 = ['complemento', 'fecha_nacimiento', 'nombres', 'apellido_paterno', 'apellido_materno', 'direccion'
            ];
            persona1.forEach(elem => {
                document.getElementById(elem).value = '';
            });
            let persona2 = ['genero'];
            persona2.forEach(elem => {
                $('#' + elem).val('selected').trigger('change');
            });
        }

        //para validar fecha de nacimiento
        let fechaNacimientoInput = document.getElementById('fecha_nacimiento');
        fechaNacimientoInput.addEventListener('change', function() {
            let fechaNacimiento = new Date(this.value);
            let fechaActual = new Date();
            if (fechaNacimiento > fechaActual) {
                document.getElementById('_fecha_nacimiento').innerHTML =
                    '<p style="color:red; font-size: 12px" >La fecha de nacimiento no puede ser en el futuro.</p>';
            } else {
                document.getElementById('_fecha_nacimiento').innerHTML = '';
            }
        });

        //para la administracion limpiar los errores
        function vaciar_errores_registro_persona() {
            let persona = ['_ci', '_fecha_nacimiento', '_nombres', '_apellido_paterno', '_direccion'];
            persona.forEach(elem => {
                document.getElementById(elem).innerHTML = '';
            });
        }



        //PARA GUARDAR LA PERSONA
        let btn_persona_nuevo = document.getElementById('btn_guardar_registro_persona');
        let form_nueva_persona = document.getElementById('form_nueva_persona');

        //para guardar el registro de la nueva persona
        btn_persona_nuevo.addEventListener('click', async () => {
            let datos = Object.fromEntries(new FormData(form_nueva_persona).entries());
            vaciar_errores_registro_persona();
            validar_boton(true, 'Verificando datos . . . ', 'btn_guardar_registro_persona');
            try {
                let respuesta = await fetch("{{ route('cper_nuevo') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(datos)
                });
                let dato = await respuesta.json();
                if (dato.tipo === 'errores') {
                    let obj = dato.mensaje;
                    for (let key in obj) {
                        document.getElementById('_' + key).innerHTML = `<p class="text-sm text-danger" >` + obj[
                            key] + `</p>`;
                    }
                    validar_boton(false, 'Guardar', 'btn_guardar_registro_persona');
                }
                if (dato.tipo === 'success') {
                    alerta_top(dato.tipo, dato.mensaje);
                    cerrar_modal_registro_persona();
                    validar_boton(false, 'Guardar', 'btn_guardar_registro_persona');
                    habilitar_deshabiltar(true);
                    vaciar_input();
                    vaciar_errores_registro_persona();
                    document.getElementById('ci').value = '';
                    actualizarTablaPersona();
                }
                if (dato.tipo === 'error') {
                    alerta_top(dato.tipo, dato.mensaje);
                    validar_boton(false, 'Guardar', 'btn_guardar_registro_persona');
                }
            } catch (error) {
                console.log('Ocurrio un error :' + error);
                validar_boton(false, 'Guardar', 'btn_guardar_registro_persona');
            }
        });


        // Función para listar personas con búsqueda y paginación
        async function listarPersonas(page = 1) {
            let search = document.getElementById('search').value;

            try {
                let response = await fetch(
                    `{{ route('cper_listar') }}?page=${page}&per_page=10&search=${encodeURIComponent(search)}`, {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                    });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                let data = await response.json();
                initializeDataTable(data.data);
                setupPagination(data);
            } catch (error) {
                console.error("Error al obtener los datos:", error);
            }
        }

        // Inicializa el DataTable con los datos recibidos
        function initializeDataTable(data) {
            $('#tabla_persona').DataTable({
                responsive: true,
                data: data,
                columns: [{
                        data: null,
                        className: 'table-td',
                        render: (data, type, row) => `
                            <div class="d-inline-block text-nowrap">
                                <button type="button" onclick="editar_persona('${row.id}')" class="btn btn-icon rounded-pill btn-warning" data-toggle="tooltip" title="EDITAR">
                                    <i class="ti ti-edit"></i>
                                </button>
                            </div>
                        `
                    },
                    {
                        data: null,
                        className: 'table-td',
                        render: ({
                            ci,
                            complemento
                        }) => complemento ? `${ci} - ${complemento}` : ci
                    },
                    {
                        data: null,
                        className: 'table-td',
                        render: ({
                            nombres,
                            ap_paterno,
                            ap_materno
                        }) => `${nombres} ${ap_paterno} ${ap_materno}`
                    },
                    {
                        data: 'fecha_nacimiento',
                        className: 'table-td',
                        render: data => fecha_literal(data, 3)
                    },
                    {
                        data: null,
                        className: 'table-td',
                        render: data => `
                    <label class="switch switch-primary">
                        <input onclick="estado_persona('${data.id}')" type="checkbox" class="switch-input" ${data.estado == 'activo' ? 'checked' : ''} />
                        <span class="switch-toggle-slider">
                            <span class="switch-on"><i class="ti ti-check"></i></span>
                            <span class="switch-off"><i class="ti ti-x"></i></span>
                        </span>
                    </label>
                `
                    },
                ],
                destroy: true,
                paging: false, // Oculta la paginación de DataTables
                searching: false, // Oculta el buscador de DataTables
                drawCallback: function() {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }

        // Configura los controles de paginación con estilo Bootstrap
        function setupPagination(data) {
            $('#pagination').empty();

            if (data.last_page > 1) {
                let paginationHtml = `<nav aria-label="Page navigation"><ul class="pagination">`;

                // Agrega el botón de "Anterior" si no estamos en la primera página
                if (data.current_page > 1) {
                    paginationHtml +=
                        `<li class="page-item"><a class="page-link" href="#" onclick="listarPersonas(${data.current_page - 1})">Anterior</a></li>`;
                } else {
                    paginationHtml += `<li class="page-item disabled"><span class="page-link">Anterior</span></li>`;
                }

                // Agrega los botones para las páginas cercanas a la actual
                const startPage = Math.max(1, data.current_page - 2);
                const endPage = Math.min(data.last_page, data.current_page + 2);

                for (let i = startPage; i <= endPage; i++) {
                    paginationHtml += `<li class="page-item ${data.current_page === i ? 'active' : ''}">
                <a class="page-link" href="#" onclick="listarPersonas(${i})">${i}</a>
            </li>`;
                }

                // Agrega el botón de "Siguiente" si no estamos en la última página
                if (data.current_page < data.last_page) {
                    paginationHtml +=
                        `<li class="page-item"><a class="page-link" href="#" onclick="listarPersonas(${data.current_page + 1})">Siguiente</a></li>`;
                } else {
                    paginationHtml += `<li class="page-item disabled"><span class="page-link">Siguiente</span></li>`;
                }

                paginationHtml += `</ul></nav>`;
                $('#pagination').html(paginationHtml);
            }
        }





        // Actualizar la tabla de personas
        function actualizarTablaPersona() {
            listarPersonas();
        }

        // Inicializar la tabla en la carga de la página
        listarPersonas();



        /**@argument
         * INICIO PARA ACTUALIZAR REGISTRO
         * */
        async function editar_persona(id) {
            try {
                let respuesta = await fetch("{{ route('cper_editar') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        id: id
                    })
                });
                let dato = await respuesta.json();
                if (dato.tipo === 'success') {
                    $('#modal_editar_persona').modal('show');
                    document.getElementById('id_persona').value = dato.mensaje.id;
                    document.getElementById('complemento_').value = dato.mensaje.complemento;
                    document.getElementById('fecha_nacimiento_').value = dato.mensaje.fecha_nacimiento;
                    document.getElementById('nombres_').value = dato.mensaje.nombres;
                    document.getElementById('apellido_paterno_').value = dato.mensaje.ap_paterno;
                    document.getElementById('apellido_materno_').value = dato.mensaje.ap_materno;
                    document.getElementById('direccion_').value = dato.mensaje.direccion;

                    // Seleccionar la opción deseada para el campo con id 'genero_'
                    let generoSelect = document.getElementById('genero_');
                    generoSelect.value = dato.mensaje.id_genero;
                    generoSelect.dispatchEvent(new Event('change'));
                }
                if (dato.tipo === 'error') {
                    alerta_top(dato.tipo, dato.mensaje);
                }
            } catch (error) {
                console.log('Error de datos : ' + error);
            }
        }

        //para validar fecha de nacimiento
        let fechaNacimientoInputEditar = document.getElementById('fecha_nacimiento_');
        fechaNacimientoInputEditar.addEventListener('change', function() {
            let fechaNacimiento = new Date(this.value);
            let fechaActual = new Date();
            if (fechaNacimiento > fechaActual) {
                document.getElementById('_fecha_nacimiento_').innerHTML =
                    '<p style="color:red; font-size: 12px" >La fecha de nacimiento no puede ser en el futuro.</p>';
            } else {
                document.getElementById('_fecha_nacimiento_').innerHTML = '';
            }
        });

        //PARA EDITAR LA PERSONA
        let form_persona_editar = document.getElementById('form_editar_persona');
        let btn_persona_editar = document.getElementById('btn_guardar_editar_persona');

        //para cerar el modal de la persona
        function cerrar_modal_update_persona() {
            $('#modal_editar_persona').modal('hide');
            vaciar_errores_persona_editar();
        }

        function vaciar_errores_persona_editar() {
            let persona = ['_fecha_nacimiento_', '_nombres_', '_apellido_paterno_', '_direccion_'];
            persona.forEach(elem => {
                document.getElementById(elem).innerHTML = '';
            });
        }
        //para guardar lo edtiado
        btn_persona_editar.addEventListener('click', async () => {
            let datos = Object.fromEntries(new FormData(form_persona_editar).entries());
            vaciar_errores_persona_editar();
            validar_boton(true, 'Verificando datos . . . ', 'btn_guardar_editar_persona');
            try {
                let respuesta = await fetch("{{ route('cper_editar_save') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(datos)
                });
                let dato = await respuesta.json();
                if (dato.tipo === 'errores') {
                    let obj = dato.mensaje;
                    for (let key in obj) {
                        document.getElementById('_' + key).innerHTML = `<p class="text-sm text-danger" >` + obj[
                            key] + `</p>`;
                    }
                    validar_boton(false, 'Guardar', 'btn_guardar_editar_persona');
                }
                if (dato.tipo === 'success') {
                    alerta_top(dato.tipo, dato.mensaje);
                    cerrar_modal_update_persona();
                    validar_boton(false, 'Guardar', 'btn_guardar_editar_persona');
                    actualizarTablaPersona();
                }
                if (dato.tipo === 'error') {
                    alerta_top(dato.tipo, dato.mensaje);
                    validar_boton(false, 'Guardar', 'btn_guardar_editar_persona');
                }
            } catch (error) {
                console.log('Ocurrio un error :' + error);
                validar_boton(false, 'Guardar', 'btn_guardar_editar_persona');
            }
        });

        /**@argument
         * FIN DE LA PARTE DE ACTUALIZAR REGISTRO
         * */

        /**@argument
         *  PARA CAMBIAR EL ESTADOSS
         * */
        //para cambiar el estado
        function estado_persona(id) {
            Swal.fire({
                title: "¿Estás seguro de cambiar el estado?",
                text: "¡NOTA!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, cambiar",
                cancelButtonText: "Cancelar",
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect waves-light"
                },
                buttonsStyling: false
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        let respuesta = await fetch("{{ route('cper_estado') }}", {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({
                                id: id
                            })
                        });
                        let dato = await respuesta.json();
                        if (dato.tipo === 'success') {
                            alerta_top(dato.tipo, dato.mensaje);
                            actualizarTablaPersona();
                        }
                        if (dato.tipo === 'error') {
                            alerta_top(dato.tipo, dato.mensaje);
                        }
                    } catch (error) {
                        console.log('Error de datos : ' + error);
                    }
                } else {
                    actualizarTablaPersona();
                    alerta_top('error', 'Se cancelo');
                }
            });
        }

        /***@argument
         * FIN DE LA PARTE DE CAMBIAR ESTADO
         * */
    </script>
@endsection
