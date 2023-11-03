<script setup>
import { ref } from 'vue';
import SearchBar from '@/Components/SearchBar.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FormularioProf from '@/Components/admin/FormularioProf.vue';
import MenuOpciones from '@/Components/admin/MenuOpciones.vue';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    $tutores: { type: Object },
});


</script>
<template>
    <div class="mt-8 bg-white p-4 shadow rounded-lg">
        <h2 class="text-black text-lg text-center font-semibold pb-4">Tutores</h2>
        <div class="my-1"></div> <!-- Espacio de separación -->
        <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
        <!-- Línea con gradiente -->
        <div class="overflow-x-auto">
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
                            Direccion
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
                    <tr class="hover:bg-grey-lighter" v-for="tutor in tutores" :key="tutor.idTutor">
                        <td class="py-2 px-4 border-b border-grey-light">{{ tutor.apellidoP }}</td>
                        <td class="py-2 px-4 border-b border-grey-light">{{ tutor.apellidoM }}</td>
                        <td class="py-2 px-4 border-b border-grey-light">{{ tutor.nombre }}</td>
                        <td class="py-2 px-4 border-b border-grey-light">
                            {{ tutor.direcciones.calle+" #"+tutor.direcciones.numero+" "+tutor.direcciones.colonia+", "+tutor.direcciones.municipio+", "+tutor.direcciones.ciudad+", "+tutor.direcciones.estados.estado}}</td>
                        <td class="py-2 px-4 border-b border-grey-light">{{ tutor.numTelefono }}</td>
                        <td class="py-2 px-4 border-b border-grey-light">
                            <a href="tel:{{tutor.numTelefono}}">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Botón "Ver más" para la tabla de Autorizaciones Pendientes -->
        <div class="text-right mt-4">
            <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded">
                Ver más
            </button>
        </div>
</div></template>