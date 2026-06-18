@extends('principal')
@section('titulo', '| REPORTES')
@section('contenido')
    <div class="card p-0 mb-4">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between p-0 pt-4">
            <div class="app-academy-md-25 card-body py-0">
                <img src="{{ asset('admin_template/img/illustrations/bulb-light.png') }}"
                    class="img-fluid app-academy-img-height scaleX-n1-rtl" height="90" />
            </div>
            <div class=" col-lg-12 card-body ">
                <h3 class="card-title mb-4 lh-sm px-md-5 lh-lg">
                    Reporte por Mes
                </h3>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <strong>¡Ups! Algo salió mal:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                @endif


                <form action="{{ route('crep_generarpdf') }}" method="POST">
                    @csrf
                    <div class="row py-4">
                        <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 py-2">
                            <select id="gestion" name="gestion" class="select2 " style="width: 100%">
                                <option value="selected" selected disabled>[ SELECCION GESTIÓN ]</option>
                                @foreach ($listar_gestion as $lis)
                                    <option value="{{ $lis }}">{{ $lis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 py-2">
                            <select id="mes" name="mes" class="select2 " style="width: 100%">
                                <option selected disabled>[ SELECCION MES ]</option>
                                <option value="1">ENERO</option>
                                <option value="2">FEBRERO</option>
                                <option value="3">MARZO</option>
                                <option value="4">ABRIL</option>
                                <option value="5">MAYO</option>
                                <option value="6">JUNIO</option>
                                <option value="7">JULIO</option>
                                <option value="8">AGOSTO</option>
                                <option value="9">SEPTIEMBRE</option>
                                <option value="10">OCTUBRE</option>
                                <option value="11">NOVIEMBRE</option>
                                <option value="12">DICIEMBRE</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-100">PROCESA</button>
                    </div>
                </form>



            </div>
            <div class="app-academy-md-25 d-flex align-items-end justify-content-end">
                <img src="{{ asset('admin_template/img/illustrations/pencil-rocket.png') }}" alt="pencil rocket"
                    height="188" class="scaleX-n1-rtl" />
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script></script>
@endsection
