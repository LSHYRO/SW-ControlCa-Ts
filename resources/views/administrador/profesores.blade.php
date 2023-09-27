@extends('layouts.principal')

@section('title', 'Inicio')

@section('opcionesNav')
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        href="{{route('admin.inicio')}}">
        <i class="fas fa-home mr-2"></i>Inicio
    </a>
    <a class="block text-white bg-cyan-500 py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        href="#">
        <i class="fas fa-file-alt mr-2"></i>Profesores
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        href="#">
        <i class="fas fa-users mr-2"></i>Alumnos
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        href="#">
        <i class="fas fa-store mr-2"></i>Directivos
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        href="#">
        <i class="fas fa-exchange-alt mr-2"></i>Tutores
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        href="#">
        <i class="fas fa-exchange-alt mr-2"></i>Materias
    </a>
@endsection()

@section('contenido')
<div class="mt-8 bg-white p-4 shadow rounded-lg">
    <h2 class="text-black text-lg text-center font-semibold pb-4">Profesores</h2>
    <div class="my-1"></div> <!-- Espacio de separación -->
    <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
    <!-- Línea con gradiente -->
    <table class="w-full table-auto text-sm">
        <thead>
            <tr class="text-sm leading-normal">
                <th
                    class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                    Apellido P
                </th>
                <th
                    class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                    Apellido M
                </th>
                <th
                    class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                    Nombre
                </th>
                <th
                    class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                    Fecha de nacimiento
                </th>
                <th
                    class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                    Correo Electronico
                </th>
                <th
                    class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                    Numero de telefono
                </th>
            </tr>
        </thead>
        <tbody>
            <!-- Se llaman los datos a traves de un foreach -->
            @foreach ($profesores as $profesor)
            <tr class="hover:bg-grey-lighter">
                <td class="py-2 px-4 border-b border-grey-light">{{$profesor->personas->apellidoP}}</td>
                <td class="py-2 px-4 border-b border-grey-light">{{$profesor->personas->apellidoM}}</td>
                <td class="py-2 px-4 border-b border-grey-light">{{$profesor->personas->nombre}}</td>
                <td class="py-2 px-4 border-b border-grey-light">{{$profesor->personas->fechaNacimiento}}</td>
                <td class="py-2 px-4 border-b border-grey-light">{{$profesor->correoElectronico}}</td>
                <td class="py-2 px-4 border-b border-grey-light"><a href="tel:{{$profesor->numTelefono}}">{{$profesor->numTelefono}}</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Botón "Ver más" para la tabla de Autorizaciones Pendientes -->
    <div class="text-right mt-4">
        <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded">
            Ver más 
        </button>
    </div>
</div>
@endsection()
