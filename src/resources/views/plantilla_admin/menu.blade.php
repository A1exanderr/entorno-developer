<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <div class="app-brand demo ">
        <a href="javascript:void(0);" class="app-brand-link">
            <img src="{{ asset('rodry/img_logos/logo_oficial_sinfondo.png') }}" width="50" height="40"
                alt="">
            <span class="app-brand-text demo menu-text fw-bold">GAMC</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>


    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <!-- Misc -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="Misc">INICIO</span>
        </li>
        <li class="menu-item @if ($menu == '0') active @endif ">
            <a href="{{ route('inicio') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-lifebuoy"></i>
                <div>INICIO</div>
            </a>
        </li>

        @if (Auth::user()->hasRole('administrador'))
            <li class="menu-item @if ($menu == '3' || $menu == '2' || $menu == '1') open @endif">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                    <div data-i18n="Users">ADMIN USUARIOS</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item @if ($menu == '1') active @endif">
                        <a href="{{ route('usuarios') }}" class="menu-link">
                            Usuarios
                        </a>
                    </li>
                    <li class="menu-item @if ($menu == '2') active @endif">
                        <a href="{{ route('roles') }}" class="menu-link">
                            Roles
                        </a>
                    </li>
                    <li class="menu-item @if ($menu == '3') active @endif">
                        <a href="{{ route('permisos') }}" class="menu-link">
                            Permisos
                        </a>
                    </li>
                </ul>
            </li>
        @endif


        <!--se controlara desde 10 - 19 -->
        <li class="menu-item @if ($menu == '10') active @endif ">
            <a href="{{ route('cper_index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-user"></i>
                <div>REGISTRO</div>
            </a>
        </li>

        <!--se controlara desde 20 - 29 -->
        <li class="menu-item @if ($menu == '20') active @endif ">
            <a href="{{ route('ccon_index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-car"></i>
                <div>CONTROL</div>
            </a>
        </li>

        @if (auth()->user()->hasRole('administrador'))
            <!--se controlara desde 20 - 29 -->
            <li class="menu-item @if ($menu == '30') active @endif ">
                <a href="{{ route('crep_reportes') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-printer"></i>
                    <div>REPORTES</div>
                </a>
            </li>
        @endif
    </ul>
</aside>
<!-- / Menu -->
