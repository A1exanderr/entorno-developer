<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REPORTE - GAMC</title>
    <style>
        @page {
            size: letter;
            margin: 1cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            margin-top: 4cm;
            /* Espacio para el header */
            margin-bottom: 2cm;
        }

        header,
        footer {
            position: fixed;
            left: 0cm;
            right: 0cm;
            height: 1cm;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 1cm;
            text-align: center;
            line-height: 1cm;
            z-index: 1000;
        }

        footer {
            bottom: 0cm;
            text-align: center;
            line-height: 1cm;
        }

        .img_fondo {
            position: absolute;
            /* opacity: 0.5; */
            top: 50%;
            left: 15%;
            transform: translate(-50%, -50%);
            z-index: -1;
            width: 30%;
            pointer-events: none;
        }


        .seccion {
            text-align: center;
            margin-bottom: 5px;
            margin-top: 10%;
        }

        .header-title {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2E86C1;
        }

        .info-section div {
            flex: 1;
            padding-right: 5px;
        }

        .info-section div:last-child {
            padding-right: 0;
        }

        .info-section h6 {
            margin: 0;
            font-size: 9px;
            color: #2E4053;
        }

        .table-container {
            margin-top: 5px;
        }

        .my-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 10px;
        }

        .my-table th,
        .my-table td {
            border: 1px solid black;
            padding: 2px;
            text-align: center;
        }



        /*
        para otra tabla
        */
        .inner-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        #tablecenter {
            border: none;
            border-bottom: 1px solid black;
            text-align: center;
        }

        #tableletft {
            border: none;
            border-bottom: 1px solid black;
            text-align: left;
        }

        #tablerigth {
            border: none;
            border-bottom: 1px solid black;
            text-align: right;
        }
    </style>
</head>

<body>
    @php
        $img_logo = public_path('rodry/img_logos/logo_oficialb.jpg');
        $imagen_logo = 'data:image/png;base64,' . base64_encode(file_get_contents($img_logo));
    @endphp

    <header>
        <img src="{{ $imagen_logo }}" class="img_fondo">

        <div class="seccion">
            <div class="header-title">REPORTE DE CONSUMO DE COMBUSTIBLE - {{ $mes }} / {{ $anio }}
            </div>
        </div>
    </header>




    <div class="table-container">
        <table class="my-table">
            <thead class="table-header">
                <tr>
                    <th>CI</th>
                    <th>NOMBRES Y APELLIDOS</th>
                    <th>TOTAL LITROS COMPRADOS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportes as $reporte)
                    <tr>
                        <td>{{ $reporte->ci_persona }}</td>
                        <td>{{ $reporte->nombre_persona }} {{ $reporte->paterno_persona }} {{ $reporte->materno_persona }}</td>
                        <td>{{ $reporte->total_litros_comprados }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


</body>

</html>
