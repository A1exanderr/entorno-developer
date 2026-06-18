@extends('principal')

@section('titulo', 'CONTROL')

@section('estilos')
    <style>
        .search-card {
            position: relative;
        }

        .search-results {
            position: absolute;
            z-index: 1000;
            width: 100%;
            border: 1px solid #ced4da;
            max-height: 200px;
            overflow-y: auto;
            background-color: white;
        }

        .search-results li {
            padding: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-results li:hover {
            background-color: #002a58;
            color: white;
        }

        .card-illustration {
            max-width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: contain;
        }

        .table-responsive {
            padding: 1rem;
        }

        .table thead th {
            white-space: nowrap;
        }

        .table tbody tr td {
            word-wrap: break-word;
            white-space: normal;
        }

        @media (max-width: 576px) {
            .table-responsive {
                padding: 0.5rem;
            }

            .table thead th,
            .table tbody td {
                font-size: 0.875rem;
                /* Reduces font size for better readability */
            }

            h4 {
                font-size: 1.25rem;
                /* Reduces the heading size */
            }
        }
    </style>
@endsection

@section('contenido')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Control</h5>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <img src="{{ asset('rodry/img_logos/logo_oficial_sinfondo.png') }}" alt="Búsqueda"
                        class="card-illustration">
                </div>
                <div class="col-xl-9 col-lg-9  col-md-9 col-sm-12 mb-3 mb-md-0">
                    <input type="hidden" name="id_persona" id="id_persona">
                    <div class="search-card">
                        <div class="form-group">
                            <input type="text" id="buscarCiPersona" class="form-control" placeholder="Ingrese CI">
                            <ul id="resultadosBusqueda" class="list-group mt-2 search-results" style="display: none;"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4 " id="listado_html_control">

    </div>


@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Referencias a los elementos del DOM (Document Object Model)
            let searchInput = document.getElementById('buscarCiPersona'); // Campo de entrada de búsqueda
            let resultsContainer = document.getElementById('resultadosBusqueda'); // Contenedor de resultados
            let persona_id = document.getElementById(
                'id_persona'); // Campo oculto para almacenar el ID de la persona seleccionada

            // Función para hacer una pausa antes de ejecutar la función de búsqueda
            let debounce = (func, delay) => {
                let timeoutId; // Variable para almacenar el temporizador
                return (...args) => {
                    clearTimeout(timeoutId); // Limpia el temporizador anterior
                    timeoutId = setTimeout(() => func.apply(null, args),
                        delay); // Establece un nuevo temporizador
                };
            };

            // Función de búsqueda que se ejecuta después de un retraso
            let performSearch = debounce(async (query) => {
                if (query.length >= 2) { // Realiza la búsqueda solo si hay al menos 2 caracteres
                    try {
                        let response = await fetch(
                            `{{ route('ccon_buscar') }}?q=${encodeURIComponent(query)}`
                        ); // Hace la solicitud al servidor con la consulta
                        if (!response.ok) throw new Error(
                            `La respuesta de la red no fue correcta: ${response.statusText}`
                        ); // Manejo de errores en la respuesta
                        let data = await response.json(); // Convierte la respuesta en formato JSON
                        updateSearchResults(data.items); // Actualiza los resultados de la búsqueda
                    } catch (error) {
                        showErrorMessage(
                            'Se produjo un error durante la búsqueda. Por favor inténtalo de nuevo'
                        ); // Muestra un mensaje de error al usuario
                    }
                } else {
                    hideResults(); // Oculta los resultados si la consulta es demasiado corta
                    persona_id.value = ''; // Limpia el campo de ID de la persona
                    verificar_mes_control(
                        null); // Llama a la función `verificar_mes_control` con `null`
                }
            }, 300); // Establece un retraso de 300 ms antes de realizar la búsqueda

            // Función para actualizar los resultados de la búsqueda en el DOM
            let updateSearchResults = (items) => {
                resultsContainer.innerHTML = ''; // Limpia el contenedor de resultados
                if (items.length > 0) {
                    let fragment = document
                        .createDocumentFragment(); // Crea un fragmento de documento para optimizar la inserción en el DOM
                    items.forEach(persona => {
                        let li = createResultItem(
                            persona); // Crea un elemento de lista para cada persona
                        fragment.appendChild(li); // Agrega el elemento de lista al fragmento
                    });
                    resultsContainer.appendChild(
                        fragment); // Inserta el fragmento en el contenedor de resultados
                    showResults(); // Muestra el contenedor de resultados
                } else {
                    showNoResultsMessage(); // Muestra un mensaje si no hay resultados
                }
            };

            // Función para crear un elemento de lista de resultados
            let createResultItem = (persona) => {
                let li = document.createElement('li'); // Crea un nuevo elemento `li`
                li.classList.add('list-group-item'); // Agrega la clase CSS al elemento `li`
                li.textContent =
                    `${persona.ci} - ${persona.nombres} ${persona.ap_paterno} ${persona.ap_materno}`; // Establece el contenido de texto del elemento
                li.dataset.id = persona.id; // Establece el ID en un atributo de datos
                li.addEventListener('click', () => selectResult(
                    li)); // Agrega un evento de clic para seleccionar el resultado
                return li; // Retorna el elemento `li` creado
            };

            // Función que se ejecuta al seleccionar un resultado
            let selectResult = (li) => {
                searchInput.value = li
                    .textContent; // Establece el texto del resultado seleccionado en el campo de búsqueda
                persona_id.value = li.dataset
                    .id; // Establece el ID de la persona seleccionada en el campo oculto
                verificar_mes_control(li.dataset
                    .id); // Llama a la función `verificar_mes_control` con el ID seleccionado
                hideResults(); // Oculta los resultados
            };

            // Funciones auxiliares para mostrar y ocultar resultados
            let showResults = () => resultsContainer.style.display = 'block'; // Muestra el contenedor de resultados
            let hideResults = () => resultsContainer.style.display = 'none'; // Oculta el contenedor de resultados
            let showNoResultsMessage = () => {
                resultsContainer.innerHTML =
                    '<li class="list-group-item">No se encontraron resultados</li>'; // Muestra un mensaje de "No se encontraron resultados"
                showResults(); // Muestra el contenedor de resultados
            };
            let showErrorMessage = (message) => {
                resultsContainer.innerHTML =
                    `<li class="list-group-item text-danger">${message}</li>`; // Muestra un mensaje de error
                showResults(); // Muestra el contenedor de resultados
            };

            // Evento que se dispara cuando se escribe en el campo de búsqueda
            searchInput.addEventListener('input', (e) => performSearch(e.target
                .value)); // Ejecuta la búsqueda al escribir

            // Evento que oculta los resultados si se hace clic fuera del campo de búsqueda o del contenedor de resultados
            document.addEventListener('click', (event) => {
                if (!searchInput.contains(event.target) && !resultsContainer.contains(event.target)) {
                    hideResults(); // Oculta los resultados si se hace clic fuera
                }
            });
        });



        async function verificar_mes_control(id) {
            let listado_html_control = document.getElementById('listado_html_control');
            if (id !== null && id !== '') {
                try {
                    let formData = new FormData();
                    formData.append('id', id);
                    formData.append('_token', token);

                    let respuesta = await fetch("{{ route('ccon_copers') }}", {
                        method: 'POST',
                        body: formData
                    });

                    if (respuesta.ok) {
                        let data = await respuesta.json();
                        listado_html_control.innerHTML = generarHTML(data);
                    } else {
                        console.log('Error en la solicitud', respuesta.status);
                        listado_html_control.innerHTML = '<p>Error al cargar los datos</p>';
                    }
                } catch (error) {
                    console.log('Ocurrió un error: ' + error);
                    listado_html_control.innerHTML = '<p>Error al procesar la solicitud</p>';
                }
            } else {
                listado_html_control.innerHTML = '';
            }
        }

        // Función para generar el HTML basado en los datos recibidos
        function generarHTML(data) {
            let html = '';

            let totalLitros = 0;
            // Formulario o mensaje de última compra
            if (data.mensaje_ultima_compra === 0) {
                if(data.litros_disponibles !== 0){
                    html += `
                        <form id="form_registra_compra" class="row p-2" method="POST" autocomplete="off">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                            <input type="hidden" name="persona_id" id="persona_id" value="${data.persona.id}">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label class="form-label" for="cantidad">
                                    Ingrese Cantidad menor o igual a ${data.litros_disponibles}
                                </label>
                                <input type="number" id="cantidad" name="cantidad" class="form-control uppercase-input" step="0.01" min="0" max="${data.litros_disponibles}" placeholder="Ej: 20.00"
                                    onkeypress="return filterFloat(event, this)"
                                    onkeyup="validar_cantidad(this.value, ${data.litros_disponibles})" autofocus />
                                <div id="_cantidad"></div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3 align-items-end">
                                <label class="form-label"></label>
                                <button type="button" id="btn_registra_compra" onclick = "validar_btn()" class="btn btn-primary w-100" disabled>
                                    VALIDAR Y GUARDAR
                                </button>
                            </div>
                        </form>
                    `;
                }else{
                    html += `
                        <div class="p-2">
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <span class="alert-icon text-danger me-2">
                                    <i class="ti ti-ban ti-xs"></i>
                                </span>
                                Estimado usuario, lamentamos informarle que ha alcanzado el límite permitido de compras para el mes de ${data.gestion_mes}. Agradecemos su comprensión. ¡Disculpe las molestias!
                            </div>
                        </div>
                    `;
                }
            } else {
                html += `
                    <div class="p-2">
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <span class="alert-icon text-danger me-2">
                                <i class="ti ti-ban ti-xs"></i>
                            </span>
                            ${data.mensaje_ultima_compra}
                        </div>
                    </div>
                `;
            }


            // Tabla de compras
            html += `
                <div class="table-responsive text-nowrap p-3">
                    <h4 class="text-center">Compras Realizadas en ${data.gestion_mes}</h4>
                    <table class="table table-sm" id="tabla_persona">
                        <thead class="table-dark">
                            <tr>
                                <th>Nº</th>
                                <th>Fecha de Compra</th>
                                <th>Cantidad de Litros</th>
                                <th>Estación</th>
                                <th>Usuario Rg</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

            if (data.compras && data.compras.length > 0) {
                data.compras.forEach((compra, index) => {
                    totalLitros += parseFloat(compra.cantidad_litros);
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${compra.fecha_compra}</td>
                            <td>${compra.cantidad_litros}</td>
                            <td>${compra.estacion.nombre}</td>
                            <td>${compra.usuario.nombres} ${compra.usuario.apellidos}</td>
                        </tr>
                    `;
                });
                // Agregar una fila al final para mostrar el total de litros
                html += `
                    <tr>
                        <td colspan="2" class="text-end"><strong>Total:</strong></td>
                        <td>${totalLitros.toFixed(2)} Litros</td>
                        <td colspan="2"></td>
                    </tr>
                `;
            } else {
                html += `
                    <tr>
                        <td colspan="4" class="text-center">No hay compras registradas en este mes</td>
                    </tr>
                `;
            }

            html += `
                    </tbody>
                </table>
                </div>
            `;
            return html;
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', () => {
            // Delegación de eventos para el formulario
            document.body.addEventListener('submit', async (e) => {
                if (e.target && e.target.id === 'form_registra_compra') {
                    e.preventDefault();
                    let form = e.target;
                    let registra_compra_btn = form.querySelector('#btn_registra_compra');

                    let datos = Object.fromEntries(new FormData(form).entries());
                    validar_boton(true, 'Verificando datos . . . ', 'btn_registra_compra');

                    try {
                        let respuesta = await fetch("{{ route('ccon_compra_nuevo') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(datos)
                        });
                        let dato = await respuesta.json();
                        if (dato.tipo === 'success') {
                            alerta_top(dato.tipo, dato.mensaje);
                            validar_boton(false, 'Validar y Guardar', 'btn_registra_compra');
                            verificar_mes_control(dato.persona_id);
                        }
                        if (dato.tipo === 'error') {
                            alerta_top(dato.tipo, dato.mensaje);
                            validar_boton(false, 'Validar y Guardar', 'btn_registra_compra');
                        }
                    } catch (error) {
                        console.log('Ocurrió un error: ' + error);
                        validar_boton(false, 'Validar y Guardar', 'btn_registra_compra');
                    }
                }
            });

            // Delegación de eventos para el clic en el botón
            document.body.addEventListener('click', (e) => {
                if (e.target && e.target.id === 'btn_registra_compra') {
                    e.preventDefault();
                    let form = e.target.closest('form');
                    if (form) {
                        form.dispatchEvent(new Event('submit'));
                    }
                }
            });
        });



        //para asegurar que solo sea de la cantidad de litros sobrantes
        let persona_id = document.getElementById('id_persona');

        // Corrección en la función validar_cantidad
        async function validar_cantidad(cantidad_ingresado, valor_maximo) {
            let btn_registra_compra = document.getElementById('btn_registra_compra');
            let cantidad_mensaje = document.getElementById('_cantidad');
            let cantidad_input = document.getElementById('cantidad');

            let maxLitros = parseFloat(valor_maximo);
            let cantidad = parseFloat(cantidad_ingresado); // Usar 'cantidad_ingresado'

            if (cantidad > maxLitros) {
                cantidad_mensaje.innerHTML = `<span class="text-danger">La cantidad no puede exceder ${maxLitros} litros.</span>`;
                btn_registra_compra.disabled = true; // Desactiva el botón
            } else {
                cantidad_mensaje.innerHTML = ""; // Limpia el mensaje de error
                btn_registra_compra.disabled = false; // Activa el botón

                try {
                    let respuesta = await fetch("{{ route('ccon_validar') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            cantidad_ingresado: cantidad_ingresado,
                            persona_id: persona_id // Se asegura que 'persona_id' sea el ID correcto
                        })
                    });
                    let dato = await respuesta.json();
                    if (dato.tipo === 'success') {
                        btn_registra_compra.disabled = false;
                        cantidad_mensaje.innerHTML = '';
                    }
                    if (dato.tipo === 'error') {
                        btn_registra_compra.disabled = true;
                        cantidad_mensaje.innerHTML = `<p id="error_estilo">` + dato.mensaje + `</p>`;
                    }
                } catch (error) {
                    console.log('error : ' + error);
                    cantidad_mensaje.innerHTML = '';
                }
            }
        }

        //para validar el boton
        async function validar_btn() {
            let datos = Object.fromEntries(new FormData(document.getElementById('form_registra_compra')).entries());
            validar_boton(true, 'Verificando datos . . . ', 'btn_registra_compra');
            try {
                let respuesta = await fetch("{{ route('ccon_compra_nuevo') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(datos)
                });
                let dato = await respuesta.json();
                if (dato.tipo === 'success') {
                    alerta_top(dato.tipo, dato.mensaje);
                    validar_boton(false, 'Validar y Guardar', 'btn_registra_compra');
                    verificar_mes_control(dato.persona_id);
                }
                if (dato.tipo === 'error') {
                    alerta_top(dato.tipo, dato.mensaje);
                    validar_boton(false, 'Validar y Guardar', 'btn_registra_compra');
                }
            } catch (error) {
                console.log('Ocurrió un error: ' + error);
                validar_boton(false, 'Validar y Guardar', 'btn_registra_compra');
            }
        }
    </script>
@endsection
