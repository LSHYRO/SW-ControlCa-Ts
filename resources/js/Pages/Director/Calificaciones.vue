<script setup>
import { ref, computed, getCurrentInstance, onMounted, watch } from 'vue';
import DirectorLayout from '@/Layouts/DirectorLayout.vue';
import FormCalifCiclo from '@/Components/director/FormularioCalificacionCiclo.vue';
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
import axios from 'axios'


pdfmake.vfs = pdfFonts.pdfMake.vfs;

DataTable.use(DataTablesLib);
DataTable.use(ButtonsHtml5);
DataTable.use(pdfmake);
DataTable.use(Select);

// Datos que recibe la vista para su uso
const props = defineProps({
    alumnos: { type: Object },
    generos: { type: Object },
    grados: { type: Object },
    tipoSangre: { type: Object },
    talleres: { type: Object },
    usuario: { type: Object },
    ciclos: { type: Object },
    ciclosE0: { type: Object },
});

const mostrarModal = ref(false);

const actDesModal = () => {
    mostrarModal.value = !mostrarModal.value;
};

const verCalificaciones = async (alumno) => {
    console.log("Esta dentro del const verCalificaciones");
    console.log(alumno);
    try {
        const idAlumno = alumno.idAlumno;
        console.log(idAlumno);
        await axios.get(route('director.verCalificaciones', { idAlumno }))
    } catch (error) {
        console.error("Error al visitar la página de calificaciones:", error);
    }
};


const columns2 = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return "";
        }
    },
    {
        data: null,
        render: function (data, type, row, meta) {
            return `<input type="checkbox" class="alumnos-checkboxes" data-id="${row.idAlumno}" ">`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) { return meta.row + 1 }
    },
    { data: 'nombre_completo' },
    { data: 'grado' },
    { data: 'grupo' },
    { data: 'materia' },
    {
        data: null, render: function (data, type, row, meta) {
            return row.tutor + " " + `<a href="tel:${row.tutorTel} "><i class="fa fa-phone" aria-hidden="true"></i></a>`
        }
    },
    {
        data: null,
        render: function (data, type, row, meta) {
            return `<div class="flex justify-center items-center">
                <a class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded ver-calificaciones-btn" data-id="${row.idAlumno}" href="/director/clasesAlumno/${ row.idAlumno }"">
                    Ver calificaciones
                </a>
            </div>`;
        }
    },
];

onMounted(() => {/*
    console.log("Entro en onMounted");
    const buttons = document.querySelectorAll('.ver-calificaciones-btn');
    console.log("está antes del forEach");
    console.log("Botones seleccionados:", buttons);
    */
    $('#alumnosTablaId').on('click', '.ver-calificaciones-btn', function () {
        const alumnoId = $(this).data('id');
        const alumno = props.alumnos.find(a => a.idAlumno === alumnoId);
        verCalificaciones(alumno);
    });
    /*
    buttons.forEach(button => {
        console.log(button);
        console.log("Está antes del addEventListener");
        button.addEventListener('click', (event) => {
            console.log("Evento de clic activado");
            const alumnoId = event.target.dataset.id;
            verCalificaciones(alumnoId);
        });
    });
    */
});


const botones = [{
    title: 'Alumnos registrados',
    extend: 'excelHtml5',
    text: '<i class="fa-solid fa-file-excel"></i> Excel',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Alumnos registrados',
    extend: 'pdfHtml5',
    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Alumnos registrados',
    extend: 'print',
    text: '<i class="fa-solid fa-print"></i> Imprimir',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Alumnos registrados',
    extend: 'copy',
    text: '<i class="fa-solid fa-copy"></i> Copiar Texto',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
];

const maxWidth = 'xl';
const closeable = true;

</script>


<template>
    <DirectorLayout title="Calificaciones" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <h2 class="text-black text-2xl text-center font-semibold p-5">Calificaciones</h2>
            <div class="my-1"></div> <!-- Espacio de separación -->
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
            <!--  //Mensaje para mostrar el mensaje de que se ha borrado o agregado correctamente una materia            -->
            <div v-if="$page.props.flash.message" class="p-4 mb-4 text-sm rounded-lg" role="alert"
                :class="`text-${$page.props.flash.color}-700 bg-${$page.props.flash.color}-100 dark:bg-${$page.props.flash.color}-200 dark:text-${$page.props.flash.color}-800`">
                <span class="font-medium">
                    {{ $page.props.flash.message }}
                </span>
            </div>
            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
            <div class="m-1 py-3 flex flex-col md:flex-row md:items-start md:space-x-3 space-y-3 md:space-y-0">
                <!--<div class="w-full md:w-2/3 space-y-4 md:space-y-0 md:space-x-4 md:flex md:items-center md:justify-start">-->
                <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                    @click="actDesModal()" data-bs-toggle="modal" data-bs-target="#modalCreate">
                    <i class="fa fa-check-to-slot mr-2"></i>Calificar ciclo
                </button>
                <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                    @click="mostrarModal = true" data-bs-toggle="modal" data-bs-target="#modalCreate">
                    <i class="fa fa-user-graduate mr-2"></i>Pasar de ciclo
                </button>
            </div>
            <div>
                <DataTable class="w-full table-auto text-sm nowrap display stripe compact cell-border order-column"
                    id="alumnosTablaId" :columns="columns2" :data="alumnos" :options="{
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
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            #
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            Nombre
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
                            Taller
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            Tutor
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                        </th>
                    </tr>
                </thead>
                </DataTable>
            </div>
        </div>
        <FormCalifCiclo :title="'Calificar ciclo'" :show="mostrarModal" :max-width="maxWidth" :closeable="closeable"
        @close="actDesModal()" :op="'1'" :modal="'modalCreate'" :ciclos="props.ciclos"/>
    </DirectorLayout>
</template>
<style>
.paginacion-sm {
    border: 1px
}
</style>