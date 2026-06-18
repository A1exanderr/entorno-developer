<!DOCTYPE html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-semi-dark" data-assets-path="../../assets/" data-template="vertical-menu-template-semi-dark">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum=1.0" />
        <title>Login</title>

        <link rel="icon" type="image/x-icon" href="{{ asset('rodry/img_logos/logo_oficial_sinfondo.png') }}" />

        <!-- Core CSS -->
        <link rel="stylesheet" href="{{ asset('admin_template/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
        <link rel="stylesheet" href="{{ asset('admin_template/vendor/css/rtl/theme-semi-dark.css') }}" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="{{ asset('admin_template/css/demo.css') }}" />

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="{{ asset('admin_template/vendor/libs/node-waves/node-waves.css') }}" />
        <link rel="stylesheet" href="{{ asset('admin_template/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
        <link rel="stylesheet" href="{{ asset('admin_template/vendor/libs/typeahead-js/typeahead.css') }}" />
        <link rel="stylesheet" href="{{ asset('admin_template/vendor/libs/%40form-validation/form-validation.css') }}" />

        <!-- Page CSS -->
        <link rel="stylesheet" href="{{ asset('admin_template/vendor/css/pages/page-auth.css') }}">
        <!-- Helpers -->
        <script src="{{ asset('admin_template/vendor/js/helpers.js') }}"></script>
        <script src="{{ asset('admin_template/vendor/js/template-customizer.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('rodry/css/loader.css') }}">
        <link rel="stylesheet" href="{{ asset('rodry/css/css.css') }}">



        <!-- Custom CSS -->
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background: linear-gradient(135deg, #96e16b 0%, #3cb78a 100%);
                position: relative;
                overflow: hidden;
            }

            .background-logo {
                position: absolute;
                top: 10%;
                left: 50%;
                transform: translateX(-50%);
                width: 200px;
                opacity: 1;
            }

            .card {
                border: none;
                border-radius: 1rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.369);
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(10px);
                z-index: 1;
            }

            .card-body {
                padding: 2rem;
                color: #333;
            }

            .app-brand {
                display: flex;
                justify-content: center;
                margin-bottom: 1.5rem;
            }

            .app-brand .app-brand-text {
                font-size: 1.75rem;
                font-weight: 700;
                color: #333;
            }

            .form-label {
                color: #333;
            }

            .form-control {
                background: rgba(255, 255, 255, 0.5);
                border: 1px solid rgba(255, 255, 255, 0.7);
                color: #333;
            }

            .form-control::placeholder {
                color: #999;
            }

            .btn-primary {
                background-color: #667eea;
                border: none;
                transition: background-color 0.3s;
            }

            .btn-primary:hover {
                background-color: #000000ab;
            }

            .spinner-border {
                color: #667eea;
            }

            #loading-wrapper {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
            }
        </style>
    </head>

    <body>
        <!-- Loading wrapper start -->
        <div id="loading-wrapper">
            <div class="spinner-border"></div>
            <span style="color: white;">Cargando login . . . .</span>
        </div>
        <!-- Loading wrapper end -->

        <img src="{{ asset('rodry/img_logos/logo_oficial_sinfondo.png') }}" alt="Logo" class="background-logo">

        <div class="container-xxl">
            <div class="authentication-wrapper authentication-basic container-p-y">
                <div class="authentication-inner py-4">
                    <!-- Login -->
                    <div class="card">
                        <div class="card-body">
                            <!-- Logo -->
                            <div class="app-brand justify-content-center mb-4 mt-2">
                                <a href="index.html" class="app-brand-link gap-2">
                                    <span class="app-brand-text demo text-body fw-bold ms-1">LOGIN</span>
                                </a>
                            </div>
                            <!-- /Logo -->

                            <div id="mensaje_error"></div>

                            <form id="formulario_login" class="mb-3" method="POST" autocomplete="off">
                                @csrf
                                <div class="mb-3">
                                    <label for="usuario" class="form-label">USUARIO</label>
                                    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingrese usuario" autofocus>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">CONTRASEÑA</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese su contraseña" autofocus>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary d-grid w-100" id="btn_ingresar_usuario">INGRESAR</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /Login -->
                </div>
            </div>
        </div>

        <script src="{{ asset('admin_template/vendor/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('admin_template/vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('admin_template/vendor/js/bootstrap.js') }}"></script>
        <script src="{{ asset('admin_template/vendor/libs/node-waves/node-waves.js') }}"></script>
        <script src="{{ asset('admin_template/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
        <script src="{{ asset('admin_template/vendor/libs/hammer/hammer.js') }}"></script>
        <script src="{{ asset('admin_template/vendor/libs/i18n/i18n.js') }}"></script>
        <script src="{{ asset('admin_template/vendor/libs/typeahead-js/typeahead.js') }}"></script>
        <script src="{{ asset('admin_template/vendor/js/menu.js') }}"></script>

        <!-- Page JS -->
        <script src="{{ asset('admin_template/js/pages-auth.js') }}"></script>

        <script>
            window.addEventListener('load', function() {
                setTimeout(() => {
                    var loader = document.getElementById('loading-wrapper');
                    loader.style.display = 'none';
                }, 500);
            });

            let login_btn = document.getElementById('btn_ingresar_usuario');
            let nuevo_icono = document.createElement('span');
            let nuevo_mensaje = document.createElement('span');

            login_btn.addEventListener('click', async () => {
                let datos = Object.fromEntries(new FormData(document.getElementById('formulario_login')).entries());

                validar_boton(true, "Verificando datos . . . . ");
                try {
                    let respuesta = await fetch("{{ route('cl_ingresar') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(datos)
                    });
                    let data = await respuesta.json();
                    if (data.tipo === 'success') {
                        document.getElementById('mensaje_error').innerHTML = `<p id="success_estilo" >` + data.mensaje + `</p>`;
                        window.location = '';
                        validar_boton(false, 'Datos correctos . . . . ');
                    }
                    if (data.tipo === 'error') {
                        document.getElementById('mensaje_error').innerHTML = `<p id="error_estilo" >` + data.mensaje + `</p>`;
                        validar_boton(false, 'INGRESAR');
                    }
                } catch (error) {
                    console.log('Existe un error : ' + error);
                    validar_boton(false, 'INGRESAR');
                }
            });

            function validar_boton(valor, msj) {
                if (valor === true) {
                    nuevo_icono.className = "spinner-border";
                    let contenedor = document.createElement("div");
                    contenedor.appendChild(nuevo_icono);
                    contenedor.appendChild(document.createTextNode(" "));
                    contenedor.appendChild(login_btn.firstChild);
                    login_btn.insertBefore(contenedor, login_btn.firstChild);
                    login_btn.disabled = true;
                }
                if (valor === false) {
                    login_btn.innerHTML = msj;
                    login_btn.disabled = false;
                }
            }
        </script>
    </body>
</html>
