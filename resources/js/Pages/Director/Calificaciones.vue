<script setup>
import { ref, computed, getCurrentInstance, onMounted, watch } from 'vue';
import SearchBar from '@/Components/SearchBar.vue';
import DirectorLayout from '@/Layouts/DirectorLayout.vue';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3';
import DataTable from 'datatables.net-vue3';
import DataTablesLib from 'datatables.net';
import Buttons from 'datatables.net-buttons-dt';
import pdfmake from 'pdfmake';
import print from 'datatables.net-buttons/js/buttons.print'
import pdfFonts from 'pdfmake/build/vfs_fonts.js';
import ButtonsHtml5 from 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-responsive-dt';
import Select from 'datatables.net-select-dt';
import jsZip from 'jszip';
window.JSZip = jsZip;

pdfmake.vfs = pdfFonts.pdfMake.vfs;

DataTable.use(DataTablesLib);
DataTable.use(ButtonsHtml5);
DataTable.use(pdfmake);
DataTable.use(Select);

// Variables que recibe la vista 
const props = defineProps({
    grados: {
        type: Object,
        default: () => ({}),
    },
    grupos: {
        type: Object,
        default: () => ({}),
    },
    materias: {
        type: Object,
        default: () => ({}),
    },
    clases: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    idClase: props.clases.idClase,
    grados: props.clases.idGrado,
    grupos: props.clases.idGrupo,
    personal: props.clases.idPersonal,
    materias: props.clases.idMateria,
    ciclos: props.clases.idCiclo,//Le agregué la s

    idMateria: props.materias.idMateria,
    materia: props.materias.materia,


});

const botones = [{
    title: 'Materias registradas',
    extend: 'excelHtml5',
    text: '<i class="fa-solid fa-file-excel"></i> Excel',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Materias registradas',
    extend: 'pdfHtml5',
    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Materias registradas',
    extend: 'print',
    text: '<i class="fa-solid fa-print"></i> Imprimir',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Materias registradas',
    extend: 'copy',
    text: '<i class="fa-solid fa-copy"></i> Copiar Texto',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
];

const getMateria = (idMateria) => {
    const materia = props.materias.find(m => m.idMateria === idMateria);
    return materia ? materia.materia : 'N/A';
};

const getGrado = (idGrado) => {
    const grado = props.grados.find(g => g.idGrado === idGrado);
    return grado ? grado.grado : 'N/A';
};

const getGrupo = (idGrupo) => {
    const grupo = props.grupos.find(g => g.idGrupo === idGrupo);
    return grupo ? grupo.grupo : 'N/A';
};

</script>

<template>
    <DirectorLayout title="materias">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <h2 class="text-black text-2xl text-center font-semibold p-5">Calificaciones</h2>
            <div class="my-1"></div> <!-- Espacio de separación -->
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
            <!--  //Mensaje para mostrar el mensaje de que se ha borrado o agregado correctamente una materia            -->
            <div v-if="$page.props.flash.message"
                class="p-4 mb-4 text-sm rounded-lg text-green-700 bg-green-100 dark:bg-green-200 dark:text-green-800"
                role="alert">
                <span class="font-medium">
                    {{ $page.props.flash.message }}
                </span>
            </div>
            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
            <div class="py-3 flex flex-col md:flex-row md:items-start md:space-x-3 space-y-3 md:space-y-0">
                <!--<div class="w-full md:w-2/3 space-y-4 md:space-y-0 md:space-x-4 md:flex md:items-center md:justify-start">-->
                <div class="sm:col-span-3">
                    <label for="clase" class="block text-sm font-medium leading-6 text-gray-900">Clase</label>
                    <div class="mt-2">
                        <select name="clase" :id="'clase' + op" v-model="form.idClase"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="" disabled selected>Selecciona una materia</option>
                            <option v-for="clase in clases" :key="clase.idClase" :value="clase.idClase">
                                {{ console.log(clase) }}
                                {{ `${getMateria(clase.idMateria)} - Grado: ${getGrado(clase.idGrado)} - Grupo: ${getGrupo(clase.idGrupo)}` }}
                            </option>
                        </select>
                    </div>
                    <div v-if="materiaError != ''" class="text-red-500 text-xs">{{ materiaError }}</div>
                </div>
            </div>
            <div>
                <DataTable class="w-full table-auto text-sm nowrap display stripe compact cell-border order-column"
                    id="materiasTablaId" :columns="columns" :data="materias" :options="{
                        responsive: true, autoWidth: false, dom: 'Bfrtip', language: {
                            search: 'Buscar', zeroRecords: 'No hay registros para mostrar',
                            info: 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
                            infoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
                            infoFiltered: '(filtrado de un total de _MAX_ registros)',
                            lengthMenu: 'Mostrar _MENU_ registros',
                            paginate: { first: 'Primero', previous: 'Anterior', next: 'Siguiente', last: 'Ultimo' },
                        }, buttons: botones
                    }">
                    <thead>
                        <tr class="text-sm leading-normal">
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                #
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Alumno
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
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            </th>
                        </tr>
                    </thead>
                </DataTable>
            </div>

        </div>

    </DirectorLayout>
</template>
<style>
.paginacion-sm {
    border: 1px
}
</style>