<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->

<li class='nav-item'><a class='nav-link' href="{{ backpack_url('dashboard') }}"><i class="nav-icon fa fa-dashboard"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<h4 class='nav-item nav-link text-white mt-3'>Datos Meteorlógicos</h4>
<li class='nav-item'><a class='nav-link' href="{{ backpack_url('station') }}"><i class="nav-icon fas fa-broadcast-tower"></i> Estaciones</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('weather_data') }}'><i class='nav-icon fas fa-cloud-sun-rain'></i> Datos Meteorlógicos</a></li>

<li class='nav-item nav-dropdown'>
    <a class='nav-link nav-dropdown-toggle' href="#"><i class="nav-icon fa fa-newspaper-o"></i>Resúmenes</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href="{{ backpack_url('daily') }}"><i class="nav-icon fa fa-calendar"></i>Diario</a></li>
        <li class='nav-item'><a class='nav-link' href="{{ backpack_url('tenDays') }}"><i class="nav-icon fa fa-calendar"></i>Diez días</a></li>
        <li class='nav-item'><a class='nav-link' href="{{ backpack_url('monthly') }}"><i class="nav-icon fa fa-calendar"></i>Mensual</a></li>
        <li class='nav-item'><a class='nav-link' href="{{ backpack_url('yearly') }}"><i class="nav-icon fa fa-calendar"></i>Anual</a></li>
    </ul>
</li>

<h4 class='nav-item nav-link text-white mt-3'>Datos Agronómicos</h4>

<li class='nav-item nav-dropdown'>
    <a class='nav-link nav-dropdown-toggle' href="#"><i class="nav-icon fa fa-newspaper-o"></i>Módulos: Parcela</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('parcela') }}'><i class='nav-icon fa fa-question'></i> Parcelas</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('suelo') }}'><i class='nav-icon fa fa-question'></i> Suelos</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('muestrasuelo') }}'><i class='nav-icon fa fa-question'></i> Muestras Suelo</a></li>
    </ul>
</li>

<li class='nav-item nav-dropdown'>
    <a class='nav-link nav-dropdown-toggle' href="#"><i class="nav-icon fa fa-newspaper-o"></i>Módulos: Cultivo</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('cultivo') }}'><i class='nav-icon fa fa-question'></i> Cultivos</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('fenologia') }}'><i class='nav-icon fa fa-question'></i> Fenología</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('manejoparcela') }}'><i class='nav-icon fa fa-question'></i> Manejo Parcela</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('plaga') }}'><i class='nav-icon fa fa-question'></i> Plagas</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('enfermedade') }}'><i class='nav-icon fa fa-question'></i> Enfermedades</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('rendimento') }}'><i class='nav-icon fa fa-question'></i> Rendimentos</a></li>
    </ul>
</li>

<li class='nav-item nav-dropdown'>
    <a class='nav-link nav-dropdown-toggle' href="#"><i class="nav-icon fa fa-list"></i>Listas de cultivos</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lkpcultivo') }}'><i class='nav-icon fa fa-question'></i> Cultivos</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('lkpvariedad') }}'><i class='nav-icon fa fa-question'></i> Variedades</a></li>
    </ul>
</li>

<h4 class='nav-item nav-link text-white mt-3'>Administración de la Plataforma</h4>
<li class='nav-item'><a class='nav-link' href="{{ backpack_url('user') }}"><i class="nav-icon fa fa-users"></i> Usuarios</a></li>

<li class='nav-item nav-dropdown'>
    <a class='nav-link nav-dropdown-toggle' href="#"><i class="fa fa-cog"></i> Sistema Kobo</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('xlsform') }}'><i class='nav-icon fa fa-question'></i> XLS Forms</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('datamap') }}'><i class='nav-icon fa fa-question'></i> Data Maps</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('submission') }}'><i class='nav-icon fa fa-question'></i> Submissions</a></li>
    </ul>
</li>

<li class='nav-item nav-dropdown'>
    <a class='nav-link nav-dropdown-toggle' href="#"><i class="nav-icon fa fa-map"></i>Localidades</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('region') }}'><i class='nav-icon fa fa-question'></i> Regiones</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('departamento') }}'><i class='nav-icon fa fa-question'></i> Departamentos</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('municipio') }}'><i class='nav-icon fa fa-question'></i> Municipios</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('comunidad') }}'><i class='nav-icon fa fa-question'></i> Comunidades</a></li>
    </ul>
</li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('observation') }}'><i class='nav-icon fa fa-files-o'></i> Observaciones</a></li>