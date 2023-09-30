@extends('layouts.principal')

@section('title', 'Directivos')

@section('opcionesNav')
<a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
    href="{{route('admin.inicio')}}">
    <i class="fas fa-home mr-2"></i>Inicio
</a>
<a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
    href="{{route('admin.profesores')}}">
    <i class="fas fa-file-alt mr-2"></i>Profesores
</a>
<a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
    href="{{route('admin.alumnos')}}">
    <i class="fas fa-users mr-2"></i>Alumnos
</a>
<a class="block text-black bg-cyan-500 py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
    href="#">
    <i class="fas fa-store mr-2"></i>Directivos
</a>
<a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
    href="{{route('admin.tutores')}}">
    <i class="fas fa-exchange-alt mr-2"></i>Tutores
</a>
<a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
    href="{{route('admin.materias')}}">
    <i class="fas fa-exchange-alt mr-2"></i>Materias
</a>
@endsection()