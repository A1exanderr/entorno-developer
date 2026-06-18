<?php

function prueva(){
    echo 'hola como estas?';
}

//para la parte de los mensajes
function mensaje_mostrar($tipo, $mensaje){
    return array(
        'tipo'=>$tipo,
        'mensaje'=>$mensaje
    );
}

//Para encriptar y desencriptar
function encriptar($string) {
    $method = 'AES-256-CBC';
    $secret_key = '@Rodry';
    $secret_iv = '03111997';

    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    $output = openssl_encrypt($string, $method, $key, 0, $iv);

    if ($output === false) {
        throw new Exception("Error al encriptar el mensaje");
    }

    $output = base64_encode($iv . $output);
    return $output;
}

function desencriptar($string) {
    $method = 'AES-256-CBC';
    $secret_key = '@Rodry';
    $secret_iv = '03111997';

    $key = hash('sha256', $secret_key);
    $iv_length = openssl_cipher_iv_length($method);

    $decoded = base64_decode($string);
    $iv = substr($decoded, 0, $iv_length);
    $encrypted_text = substr($decoded, $iv_length);

    $output = openssl_decrypt($encrypted_text, $method, $key, 0, $iv);

    if ($output === false) {
        throw new Exception("Error al desencriptar el mensaje");
    }

    return $output;
}


//para 1000000.00
function sin_separador_comas($monto){
    $saldo_respuesta = str_replace(",", "", $monto);
    return $saldo_respuesta;
}
//para 100,000.00
function con_separador_comas($monto){
    $saldo_respuesta = number_format($monto, 2, '.', ',');
    return $saldo_respuesta;
}

//para las fechas
function fecha_literal($Fecha, $Formato) {
    $dias = array(
        0 => 'Domingo',
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Mièrcoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sàbado'
    );
    $meses = array(
        1 => 'enero',
        2 => 'febrero',
        3 => 'marzo',
        4 => 'abril',
        5 => 'mayo',
        6 => 'junio',
        7 => 'julio',
        8 => 'agosto',
        9 => 'septiembre',
        10 => 'octubre',
        11 => 'noviembre',
        12 => 'diciembre');
    $aux = date_parse($Fecha);
    switch ($Formato) {
        case 1:  // 04/10/23
            return date('d/m/y', strtotime($Fecha));
        case 2:  //04/oct/23
            return sprintf('%02d/%s/%02d', $aux['day'], substr($meses[$aux['month']], 0, 3), $aux['year'] % 100);
        case 3:   //octubre 4, 2023
            return $meses[$aux['month']] . ' ' . sprintf('%.2d', $aux['day']) . ', ' . $aux['year'];
        case 4:   // 4 de octubre de 2023
            return $aux['day'] . ' de ' . $meses[$aux['month']] . ' de ' . $aux['year'];
        case 5:   //lunes 4 de octubre de 2023
            $numeroDia= date('w', strtotime($Fecha));
            return $dias[$numeroDia].' '.$aux['day'] . ' de ' . $meses[$aux['month']] . ' de ' . $aux['year'];
        case 6:
            return date('d/m/Y', strtotime($Fecha));
        default:
            return date('d/m/Y', strtotime($Fecha));
    }
}


//para ver si es masculino femenino o prefiere no decir
function verificar_persona_generto($genero){
    if($genero=='M'){
        return 'MASCULINO';
    }
    if($genero=='F'){
        return 'FEMENINO';
    }
    if($genero=='ND'){
        return 'PREFIERE NO DECIR';
    }
}

function obtenerNombreMes($numeroMes) {
    $nombresMeses = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
    ];

    // Verificar que el número de mes esté dentro del rango válido
    if ($numeroMes >= 1 && $numeroMes <= 12) {
        return $nombresMeses[$numeroMes];
    } else {
        return 'Número de mes no válido';
    }
}

function cadena_sin_guion_punto($valor){
    $cadena_sin_guiones_y_puntos = str_replace(['-', ':'], ' ', $valor);
    // Eliminar espacios en blanco
    $cadena_sin_espacios = str_replace(' ', '', $cadena_sin_guiones_y_puntos);
    return $cadena_sin_espacios;
}


function unidad($numuero){
    switch ($numuero){
        case 9: $numu = "Nueve"; break;
        case 8: $numu = "Ocho"; break;
        case 7: $numu = "Siete"; break;
        case 6: $numu = "Seis"; break;
        case 5: $numu = "Cinco"; break;
        case 4: $numu = "Cuatro"; break;
        case 3: $numu = "Tres"; break;
        case 2: $numu = "Dos"; break;
        case 1: $numu = "Uno"; break;
        case 0: $numu = ""; break;
    }
    return $numu;
}


function decena($numdero){
    if ($numdero >= 90 && $numdero <= 99){
        $numd = "Noventa ";
        if ($numdero > 90)
            $numd = $numd."Y ".(unidad($numdero - 90));
    }else if ($numdero >= 80 && $numdero <= 89){
        $numd = "Ochenta ";
        if ($numdero > 80)
            $numd = $numd."Y ".(unidad($numdero - 80));
    }else if ($numdero >= 70 && $numdero <= 79){
        $numd = "Setenta ";
        if ($numdero > 70)
            $numd = $numd."Y ".(unidad($numdero - 70));
    }else if ($numdero >= 60 && $numdero <= 69){
        $numd = "Sesenta ";
        if ($numdero > 60)
            $numd = $numd."Y ".(unidad($numdero - 60));
    }else if ($numdero >= 50 && $numdero <= 59){
        $numd = "Cincuenta ";
        if ($numdero > 50)
            $numd = $numd."Y ".(unidad($numdero - 50));
    }else if ($numdero >= 40 && $numdero <= 49){
        $numd = "Cuarenta ";
        if ($numdero > 40)
            $numd = $numd."Y ".(unidad($numdero - 40));
    }else if ($numdero >= 30 && $numdero <= 39){
        $numd = "Treinta ";
        if ($numdero > 30)
            $numd = $numd."Y ".(unidad($numdero - 30));
    }else if ($numdero >= 20 && $numdero <= 29){
        if ($numdero == 20)
            $numd = "Veinte ";
        else
            $numd = " Veinte".(unidad($numdero - 20));
    }else if ($numdero >= 10 && $numdero <= 19){
        switch ($numdero){
            case 10: $numd = "Diez "; break;
            case 11: $numd = "Once "; break;
            case 12: $numd = "Doce "; break;
            case 13: $numd = "Trece "; break;
            case 14: $numd = "Catorce "; break;
            case 15: $numd = "Quince "; break;
            case 16: $numd = " Dieciseis "; break;
            case 17: $numd = " Diecisiete"; break;
            case 18: $numd = " Dieciocho"; break;
            case 19: $numd = " Diecinueve";break;
        }
    }
    else
        $numd = unidad($numdero);
    return $numd;
}


function centena($numc){
    if ($numc >= 100){
        if ($numc >= 900 && $numc <= 999){
            $numce = " Novecientos ";
            if ($numc > 900)
                $numce = $numce.(decena($numc - 900));
        }
        else if ($numc >= 800 && $numc <= 899){
            $numce = " Ochocientos ";
            if ($numc > 800)
                $numce = $numce.(decena($numc - 800));
        }else if ($numc >= 700 && $numc <= 799){
            $numce = " Setecientos ";
            if ($numc > 700)
                $numce = $numce.(decena($numc - 700));
        }else if ($numc >= 600 && $numc <= 699){
            $numce = "Seiscientos ";
            if ($numc > 600)
                $numce = $numce.(decena($numc - 600));
        }else if ($numc >= 500 && $numc <= 599){
            $numce = "Quiñientos ";
            if ($numc > 500)
                $numce = $numce.(decena($numc - 500));
        }else if ($numc >= 400 && $numc <= 499){
            $numce = "Cuatrocientos ";
            if ($numc > 400)
                $numce = $numce.(decena($numc - 400));
        }else if ($numc >= 300 && $numc <= 399){
            $numce = "Trecientos ";
            if ($numc > 300)
                $numce = $numce.(decena($numc - 300));
        }else if ($numc >= 200 && $numc <= 299){
            $numce = "Docientos ";
            if ($numc > 200)
                $numce = $numce.(decena($numc - 200));
        }else if ($numc >= 100 && $numc <= 199){
            if ($numc == 100)
                $numce = "Cien ";
            else
                $numce = "Ciento ".(decena($numc - 100));
        }
    }
    else
        $numce = decena($numc);

    return $numce;
}


function miles($nummero){
    if ($nummero >= 1000 && $nummero < 2000){
        $numm = "Mil ".(centena($nummero%1000));
    }
    if ($nummero >= 2000 && $nummero <10000){
        $numm = unidad(Floor($nummero/1000))." mil ".(centena($nummero%1000));
    }
    if ($nummero < 1000)
        $numm = centena($nummero);

    return $numm;
}


function decmiles($numdmero){
    if ($numdmero == 10000)
        $numde = "Diez mil";
    if ($numdmero > 10000 && $numdmero <20000){
        $numde = decena(Floor($numdmero/1000))."Mil ".(centena($numdmero%1000));
    }
    if ($numdmero >= 20000 && $numdmero <100000){
        $numde = decena(Floor($numdmero/1000))." Mil ".(miles($numdmero%1000));
    }
    if ($numdmero < 10000)
        $numde = miles($numdmero);

    return $numde;
}


function cienmiles($numcmero){
    if ($numcmero == 100000)
        $num_letracm = "Cien mil";
    if ($numcmero >= 100000 && $numcmero <1000000){
        $num_letracm = centena(Floor($numcmero/1000))." Mil ".(centena($numcmero%1000));
    }
    if ($numcmero < 100000)
        $num_letracm = decmiles($numcmero);
    return $num_letracm;
}


function millon($nummiero){
    if ($nummiero >= 1000000 && $nummiero <2000000){
        $num_letramm = "Un millon ".(cienmiles($nummiero%1000000));
    }
    if ($nummiero >= 2000000 && $nummiero <10000000){
        $num_letramm = unidad(Floor($nummiero/1000000))." Millones ".(cienmiles($nummiero%1000000));
    }
    if ($nummiero < 1000000)
        $num_letramm = cienmiles($nummiero);

    return $num_letramm;
}


function decmillon($numerodm){
    if ($numerodm == 10000000)
        $num_letradmm = "Diez millones";
    if ($numerodm > 10000000 && $numerodm <20000000){
        $num_letradmm = decena(Floor($numerodm/1000000))."Millones ".(cienmiles($numerodm%1000000));
    }
    if ($numerodm >= 20000000 && $numerodm <100000000){
        $num_letradmm = decena(Floor($numerodm/1000000))." Millones ".(millon($numerodm%1000000));
    }
    if ($numerodm < 10000000)
        $num_letradmm = millon($numerodm);

    return $num_letradmm;
}

function cienmillon($numcmeros){
    if ($numcmeros == 100000000)
        $num_letracms = "Cien millones";
    if ($numcmeros >= 100000000 && $numcmeros <1000000000){
        $num_letracms = centena(Floor($numcmeros/1000000))." Millones ".(millon($numcmeros%1000000));
    }
    if ($numcmeros < 100000000)
        $num_letracms = decmillon($numcmeros);
    return $num_letracms;
}

function milmillon($nummierod){
    if ($nummierod >= 1000000000 && $nummierod <2000000000){
        $num_letrammd = "Mil ".(cienmillon($nummierod%1000000000));
    }
    if ($nummierod >= 2000000000 && $nummierod <10000000000){
        $num_letrammd = unidad(Floor($nummierod/1000000000))." Mil ".(cienmillon($nummierod%1000000000));
    }
    if ($nummierod < 1000000000)
        $num_letrammd = cienmillon($nummierod);

    return $num_letrammd;
}




function convertir($numero){
    $num = str_replace(",","",$numero);
    $num = number_format($num,2,'.','');
    $cents = substr($num,strlen($num)-2,strlen($num)-1);
    $num = (int)$num;


    if ($num === 0) {
        return 'CERO';
    }

    $numf = milmillon($num);

    if($cents>0){
        return $numf." con ".centena($cents).' centavos';
    }else{
        return $numf. ' Bolivianos';
    }
}
