@extends('layouts.principal')

@section('title', 'Inicio')

@section('opcionesNav')
    <a class="block text-white bg-cyan-500 py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        href="#">
        <i class="fas fa-home mr-2"></i>Inicio
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        href="{{ route('admin.profesores') }}">
        <i class="fas fa-file-alt mr-2"></i>Profesores
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        href="{{ route('admin.alumnos') }}">
        <i class="fas fa-users mr-2"></i>Alumnos
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        href="{{ route('admin.directivos') }}">
        <i class="fas fa-store mr-2"></i>Directivos
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        href="{{ route('admin.tutores') }}">
        <i class="fas fa-exchange-alt mr-2"></i>Tutores
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        href="{{ route('admin.materias') }}">
        <i class="fas fa-exchange-alt mr-2"></i>Materias
    </a>
@endsection()

@section('contenido')
    <div class="mt-8 bg-white p-4 shadow rounded-lg h-5/6">
        <h1 class="font-bold text-center text-2xl pt-5">Bienvenido al sistema de la telesecundaria clave: 20DTV1474D</h1>
        <h1 class="text-justify p-9">Ha iniciado sesion como administrador, donde podra realizar la gestión de usuarios, materias y clases. Para realizar dichas acciones seleccione la categoria a administrar</h1>
    </div>



@endsection()
