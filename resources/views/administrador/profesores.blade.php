@extends('layouts.principal')

@section('title', 'Profesores')

@section('opcionesNav')
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        href="{{ route('admin.inicio') }}">
        <i class="fas fa-home mr-2"></i>Inicio
    </a>
    <a class="block text-white bg-cyan-500 py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        href="#">
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
    <div class="mt-8 bg-white p-4 shadow rounded-lg">
        <form action="{{ route('admin.addProfesores') }}" method="POST">

            @csrf

            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Datos del profesor</h2>
                <p class="mt-1 text-sm leading-6 text-gray-600">Rellene todos los campos para poder registrar a un nuevo
                    profesor
                </p>

                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                    <div class="sm:col-span-1"> <!-- Definir el tamaño del cuadro de texto -->
                        <label for="apellidoPaterno" class="block text-sm font-medium leading-6 text-gray-900">Apellido
                            Paterno</label>
                        <div class="mt-2">
                            <input type="text" name="apellidoPaterno" id="apellidoPaterno" autocomplete="family-name"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="apellidoMaterno" class="block text-sm font-medium leading-6 text-gray-900">Apellido
                            Materno</label>
                        <div class="mt-2">
                            <input type="text" name="apellidoMaterno" id="apellidoMaterno" autocomplete="family-name"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="nombres" class="block text-sm font-medium leading-6 text-gray-900">Nombres</label>
                        <div class="mt-2">
                            <input type="text" name="nombres" id="nombres" autocomplete="given-name"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Correo
                            electrónico</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="telefono" class="block text-sm font-medium leading-6 text-gray-900">Número de
                            teléfono</label>
                        <div class="mt-2">
                            <input type="tel" id="telefono" name="telefono" type="number" autocomplete="telefono"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="fechaNacimiento" class="block text-sm font-medium leading-6 text-gray-900">Fecha de
                            nacimiento</label>
                        <div class="mt-2">

                            <input type="date" name="fechaNacimiento" id="fechaNacimiento"
                                autocomplete="fechaNacimiento"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancelar</button>
                <button type="submit"
                    class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded">Guardar</button>
            </div>
        </form>
    </div>


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
                        activo
                    </th>
                    <th
                        class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                        Numero de telefono
                    </th>
                    <th
                        class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">

                    </th>
                </tr>
            </thead>
            <tbody>
                <!-- Se llaman los datos a traves de un foreach -->
                @foreach ($personal as $profesor)
                    <tr class="hover:bg-grey-lighter">
                        <td class="py-2 px-4 border-b border-grey-light">{{ $profesor->apellidoP }}</td>
                        <td class="py-2 px-4 border-b border-grey-light">{{ $profesor->apellidoM }}</td>
                        <td class="py-2 px-4 border-b border-grey-light">{{ $profesor->nombre }}</td>
                        <td class="py-2 px-4 border-b border-grey-light">{{ $profesor->fechaNacimiento }}</td>
                        <td class="py-2 px-4 border-b border-grey-light">{{ $profesor->correoElectronico }}</td>
                        <td class="py-2 px-4 border-b border-grey-light">{{ $profesor->activo }}</td>
                        <td class="py-2 px-4 border-b border-grey-light">{{ $profesor->numTelefono }}</td>
                        <td class="py-2 px-4 border-b border-grey-light">
                            <a href="tel:{{ $profesor->numTelefono }}">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                            </a>
                        </td>
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
