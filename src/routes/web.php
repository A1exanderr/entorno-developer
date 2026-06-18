<?php

use App\Http\Controllers\Configuracion\Controlador_configuracion;
use App\Http\Controllers\Control\Controlador_control;
use App\Http\Controllers\Persona\Controlador_persona;
use App\Http\Controllers\Reporte\Controlador_reporte;
use App\Http\Controllers\Reportes\Controlador_reportes;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Usuario\Controlador_login;
use App\Http\Controllers\Usuario\Controlador_usuario;

//AQUI PARA LOS NO AUTENTICADOS DE LOS USUSARIOS
Route::prefix('/')->middleware(['no_autenticados'])->group(function(){
    Route::get('/', function(){
        return view('login');
    })->name('login');
    Route::get('/login', function(){
        return view('login');
    })->name('login');

    Route::controller(Controlador_login::class)->group(function(){
        Route::post('ingresar', 'ingresar')->name('cl_ingresar');
    });
});

Route::prefix('/admin')->middleware(['autenticados'])->group(function(){
    Route::controller(Controlador_login::class)->group(function (){
        Route::get('inicio', 'inicio')->name('inicio');
        Route::post('mensaje', 'mensaje')->name('in_mensaje');
        Route::post('salir','cerrar_session')->name('salir');
    });

    Route::controller(Controlador_usuario::class)->group(function(){
        /**
         * ADMINISTRACION DEL PERFIL
         */
        Route::get('perfil','perfil')->name('perfil');
        Route::post('guardar_password', 'guardar_password')->name('pe_guardar');

        Route::post('save_estacion', 'guardar_estacion')->name('sav_estacion');
        /**
         * FIN DE ADMINISTRACION DE PERFIL
         */

        /**
             * PARA ADMINISTRAR LOS USUARIOS
             */
            Route::get('usuarios','usuarios')->name('usuarios');
            Route::post('listar_usuario', 'listar_usuario')->name('user_listar');
            Route::post('nuevo_usuario', 'nuevo_usuario')->name('user_crear');
            Route::post('validar_usuario', 'validar_usuario')->name('user_validar');
            Route::post('estado_usuario', 'estado_usuario')->name('user_estado');
            Route::post('edit_usuario', 'edit_usuario')->name('user_edit');
            Route::post('update_usuario', 'update_usuario')->name('user_update');
            /*

            Route::delete('eliminar_usuario', 'eliminar_usuario')->name('user_eliminar');
            Route::post('userbuscar_rol', 'userbuscar_rol')->name('user_buscar_rol'); */
            /**
             * FIN PARA ADMINISTRAR LOS USUARIOS
             */

            /**
             * ADMINISTRAR ROLES
             */
            Route::get('roles', 'roles')->name('roles');
            Route::post('roles_guardar','roles_guardar')->name('rol_guardar');
            Route::post('roles_editar','roles_editar')->name('rol_editar');
            Route::post('roles_editar_guardar','roles_editar_guardar')->name('rol_editar_guardar');
            Route::delete('roles_eliminar','roles_eliminar')->name('rol_eliminar');
            Route::delete('roles_vizualizar','roles_vizualizar')->name('rol_vizualizar');
            /**
             * FIN DE ADMINISTRAR LOS ROLES
             */

        /**
         * ADMINISTRAR PERMISOS
         */
        Route::get('permisos', 'permisos')->name('permisos');
        Route::post('guardar_permiso','guardar_permiso')->name('per_guardar');
        Route::post('permiso_listar','permiso_listar')->name('per_listar');
        Route::post('permiso_editar','permiso_editar')->name('per_editar');
        Route::post('permiso_editar_guardar','permiso_editar_guardar')->name('pergu_editar');
        Route::delete('permiso_eliminar','permiso_eliminar')->name('per_eliminar');
        /**
         * FIN DE ADMINISTRAR LOS PERMISOS
         */

    });

    //PARA LA PARTE DE REGISTRO DE PERSONAS
    Route::controller(Controlador_persona::class)->group(function(){
        Route::get('persona', 'persona')->name('cper_index');
        Route::post('persona_validar', 'persona_validar')->name('cper_validar');
        Route::post('persona_nuevo', 'persona_nuevo')->name('cper_nuevo');
        Route::post('persona_listar', 'persona_listar')->name('cper_listar');
        Route::post('persona_editar', 'persona_editar')->name('cper_editar');
        Route::post('persona_editar_save', 'persona_editar_save')->name('cper_editar_save');
        Route::post('persona_estado', 'persona_estado')->name('cper_estado');
    });
    //FIN DE LA PARTE DE REGISTRO DE PERSONAS


    //PARA LA PARTE DE CONTROL
    Route::controller(Controlador_control::class)->group(function(){
        Route::get('control', 'control')->name('ccon_index');
        Route::get('/buscar_persona', 'buscar_persona')->name('ccon_buscar');
        Route::post('/listacontrol_persona', 'lista_control_persona')->name('ccon_copers');

        //para validar la cantidad
        Route::post('/validar_cantidad', 'valida_cantidad_persona')->name('ccon_validar');
        Route::post('/compra_nuevo', 'compra_nuevo')->name('ccon_compra_nuevo');
    });
    //FIN DE LA PARTE DE CONTROL


    //PARA LA PARTE DE LOS REPORTES
    Route::controller(Controlador_reporte::class)->group(function(){
        Route::get('reportes', 'reportes')->name('crep_reportes');
        Route::post('generarpdf', 'generar_pdf')->name('crep_generarpdf');
    });
    //FIN DE LA PARTE DE REPORTES
});
