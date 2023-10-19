
<script>
import { Head, Link } from '@inertiajs/vue3';
import OpcionesNavAdmin from '@/Components/admin/OpcionesNavAdmin.vue';
import profesores from './Admin/Profesores.vue';
import inicio from './Admin/Inicio.vue';

export default {
    data() {
        return {
            opcionSeleccionada: 'opcion1'
        }
    },
    methods:
    {
        toggleSidebar() {
            sideNav.classList.toggle('hidden'); // Agrega o quita la clase 'hidden' para mostrar u ocultar la navegación lateral
        }
    },
    components: {
        OpcionesNavAdmin,
        profesores,
        inicio,
    },
    
}
</script>
<template>
    <Head title="Administrador: inicio" />
    <div class="flex flex-col h-screen bg-gray-100">
        <!-- Aqui se colocara el layout, dentro de este se tendra el contenido, que en este caso, seran las otras vue -->
        <!-- Corresponde ahora a topContent -->
        <!-- Barra de navegación superior -->
        <div class="bg-cyan-600 text-white shadow w-full p-2 flex items-center justify-between">
            <div class="flex items-center">
                <div class="md:hidden flex items-center pe-2"> <!-- Se muestra solo en dispositivos pequeños -->
                    <button id="menuBtn" @click="toggleSidebar">
                        <i class="fas fa-bars text-white text-lg"></i> <!-- Ícono de menú -->
                    </button>
                </div>
                <div class="flex items-center"> <!-- Mostrado en todos los dispositivos -->
                    <h2 class="font-bold text-xl">Telesecundaria clave: 20DTV1474D</h2>
                </div>

            </div>

            <!-- Ícono de Notificación y Perfil -->
            <div class="space-x-5">
                <!-- Botón de Perfil -->
                <div>
                    <i class="fas fa-user text-white font-thin font-['DM Sans']"></i>
                    <i class="text-white font-['DM Sans']"> Administrador </i>
                </div>
            </div>
        </div>
        <!-- Hasta aca llegara top content -->
        <!-- Contenido principal -->
        <div class="flex-1 flex flex-wrap">
            <!-- Barra lateral de navegación (oculta en dispositivos pequeños) -->
            <div class="p-2 bg-white w-full md:w-60 flex flex-col md:flex" id="sideNav">
                <nav>
                    <div class="w-60 h-16 justify-start items-center gap-4 inline-flex">
                        <div class="w-12 h-12 relative">
                            <div class="w-12 h-12 left-0 top-0 absolute bg-zinc-300 rounded-full"></div>
                            <img class="w-12 h-12 left-0 top-0 absolute" src="{{ asset('images/perfil.png') }}" />
                        </div>
                        <div class="flex-col justify-start items-center inline-flex ">
                            <div class="text-center text-black text-lg font-normal font-['DM Sans']">Rigoberto López
                            </div>
                            <div class="text-center text-stone-950 text-sm font-normal font-['DM Sans']">SALOR850201
                            </div>
                        </div>
                    </div>
                    <!-- Señalador de ubicación -->
                    <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mt-2"></div>

                    <OpcionesNavAdmin />

                </nav>
                <!-- Señalador de ubicación -->
                <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mt-2"></div>
                <!-- Ítem de Cerrar Sesión -->
                <a class="block text-gray-500 py-2.5 px-4 my-2 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white mt-auto"
                    href="#">
                    <i class="fas fa-sign-out-alt mr-2"></i>Cerrar sesión
                </a>
            </div>

            <!-- Área de contenido principal -->
            <div class="flex-1 p-4 w-full md:w-1/2">
                    <div v-if="opcionSeleccionada === 'opcion1'">
                        <inicio />
                    </div>
                    <div v-else-if="opcionSeleccionada === 'opcion2'">
                        <!-- Contenido para la opción 2 -->
                    </div>
                    <div v-else-if="opcionSeleccionada === 'opcion3'">
                        <profesores />
                    </div>
                    <div v-else-if="opcionSeleccionada === 'opcion4'">
                        <!-- Contenido para la opción 4 -->
                    </div>
                    <div v-else>
                        <!-- Contenido para la opción 5 o por defecto -->
                    
                </div>
            </div>
        </div>
    </div>
</template>
