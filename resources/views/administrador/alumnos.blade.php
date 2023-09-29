@extends('layouts.principal')

@section('title', 'Alumnos')

@section('opcionesNav')
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        href="{{ route('admin.inicio') }}">
        <i class="fas fa-home mr-2"></i>Inicio
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        href="{{ route('admin.profesores') }}">
        <i class="fas fa-file-alt mr-2"></i>Profesores
    </a>
    <a class="block text-white bg-cyan-500 py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
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
    <div class="border-b border-gray-900/10 pb-12">
        <h2 class="text-base font-semibold leading-7 text-gray-900">Datos del alumno</h2>
        <p class="mt-1 text-sm leading-6 text-gray-600">Rellene todos los campos para poder registrar a un nuevo alumno</p>

        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div class="sm:col-span-2">
                <label for="nombres" class="block text-sm font-medium leading-6 text-gray-900">Nombres</label>
                <div class="mt-2">
                    <input type="text" name="nombres" id="nombres" autocomplete="given-name"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>

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

            <div class="sm:col-span-1">
                <label for="CURP" class="block text-sm font-medium leading-6 text-gray-900">CURP</label>
                <div class="mt-2">
                    <input type="text" name="CURP" id="CURP" autocomplete="family-name"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>

            <div class="sm:col-span-1">
                <label for="estatus" class="block text-sm font-medium leading-6 text-gray-900">Estatus</label>
                <div class="mt-2">
                    <select id="estatus" name="estatus" autocomplete="estatus"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                        <option>Activo</option>
                        <option>Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="sm:col-span-1">
                <label for="grado" class="block text-sm font-medium leading-6 text-gray-900">Grado</label>
                <div class="mt-2">
                    <select id="grado" name="grado" autocomplete="number"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                        <option>1°</option>
                        <option>2°</option>
                        <option>3°</option>
                    </select>
                </div>
            </div>

            <div class="sm:col-span-1">
                <label for="materia" class="block text-sm font-medium leading-6 text-gray-900">Materia</label>
                <div class="mt-2">
                    <select id="materia" name="materia" autocomplete="materia"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                        <option>Matemáticas</option>
                        <option>Español</option>
                        <option>Ciencias Naturales</option>
                        <option>Geografía</option>
                        <option>Historia</option>
                        <option>Física</option>
                        <option>Química</option>
                        <option>Computación</option>
                    </select>
                </div>
            </div>

            <div class="sm:col-span-1">
                <label for="grupo" class="block text-sm font-medium leading-6 text-gray-900">Grupo</label>
                <div class="mt-2">
                    <select id="grupo" name="grupo" autocomplete="grupo"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                        <option>A</option>
                        <option>B</option>
                        <option>C</option>
                        <option>D</option>
                    </select>
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="tutor" class="block text-sm font-medium leading-6 text-gray-900">Tutor</label>
                <div class="mt-2">
                    <input type="text" name="tutor" id="tutor" autocomplete="given-name"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancelar</button>
                <button type="submit"
                    class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded">Guardar</button>
            </div>
        </div>



        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <h2 class="text-black text-lg text-center font-semibold pb-4">Alumnos</h2>
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
                            CURP
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            Estatus
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            Grado
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            Grupo
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            Materias
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            Tutor
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <!-- Se llaman los datos a traves de un foreach -->
                    @foreach ($alumnos as $alumno)
                        <tr class="hover:bg-grey-lighter">
                            <td class="py-2 px-4 border-b border-grey-light">{{ $alumno->personas->apellidoP }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ $alumno->personas->apellidoM }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ $alumno->personas->nombre }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ $alumno->CURP }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ $alumno->Estatus }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ $alumno->Grado }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ $alumno->Grupo }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ $alumno->Materias }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ $alumno->Tutor }}</td>
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
