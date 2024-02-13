<script setup>
////////////////////////////////////////////////////////////////////////////////////////////////
// Importaciones necesarias para la funcionalidad de la vista en general
import ProfeLayout from '@/Layouts/ProfeLayout.vue';
import DataTable from 'datatables.net-vue3';
import DataTablesLib from 'datatables.net';
import Buttons from 'datatables.net-buttons-dt';
import pdfmake from 'pdfmake';
import print from 'datatables.net-buttons/js/buttons.print'
//import * as pdfFonts from 'pdfmake/build/vfs_fonts.js';
import ButtonsHtml5 from 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-responsive-dt';
import Select from 'datatables.net-select-dt';
import jsZip from 'jszip';
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Variables e inicializaciones necesarias para el datatable y el uso de generacion de 
// documentos
window.JSZip = jsZip;
//pdfmake.vfs = pdfFonts.pdfMake.vfs;
pdfmake.fonts = {
  Roboto: {
    normal:
      "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Regular.ttf",
    bold: "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Medium.ttf",
    italics:
      "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Italic.ttf",
    bolditalics:
      "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-MediumItalic.ttf",
  },
};
DataTable.use(DataTablesLib);
DataTable.use(ButtonsHtml5);
DataTable.use(pdfmake);
DataTable.use(Select);
////////////////////////////////////////////////////////////////////////////////////////////////


const props = defineProps({
    clase: { type: Object },
    usuario: { type: Object },
    actividad: { type: Object },
    alumnos: { type: Object },
    calificaciones: {
        type: Object,
        default: () => ({})
    },
});

////////////////////////////////////////////////////////////////////////////////////////////////
// Configuración de las columnas con los valores correspondientes de las materias, ademas de
// la creacion de las checkboxes y los botones de editar y modificar
const columns = [
    {
        data: null, render: function (data, type, row, meta) { return meta.row + 1 }
    },
    { data: 'nombre_completo' },
    { data: 'calificacion' }
];
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Creación de los botones para al generación de documentos, ademas de la configuración de los
// titulos de los documentos
const botones = [{
    title: props.actividad.titulo,
    extend: 'excelHtml5',
    text: '<i class="fa-solid fa-file-excel"></i> Excel',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: props.actividad.titulo,
    extend: 'pdfHtml5',
    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: props.actividad.titulo,
    extend: 'print',
    text: '<i class="fa-solid fa-print"></i> Imprimir',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: props.actividad.titulo,
    extend: 'copy',
    text: '<i class="fa-solid fa-copy"></i> Copiar Texto',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
];
////////////////////////////////////////////////////////////////////////////////////////////////
</script>

<template>
    <ProfeLayout :title="props.actividad.titulo" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg alturaM">
            <h2 class="text-black text-2xl text-center font-semibold p-5">{{ actividad.titulo }}</h2>
            <h3 class="text-black text-base text-center m-2 px-12">{{ actividad.descripcion }} </h3>
            <div class="py-3 flex flex-col md:flex-row md:items-start md:space-x-3 space-y-3 md:space-y-0">
                <div class="m-1">
                    <a :href="route('profe.mostrarClase', clase.idClase)"><i class="fa-solid fa-arrow-left"> </i> Volver</a>
                </div>
            </div>
            <!-- Linea de separación -->
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
            <div class="my-2">
                <p class="my-1">
                    <strong>Tipo de actividad: </strong> {{ props.actividad.tipAct }}
                </p>
                <p class="my-1">
                    <strong>Fecha de inicio: </strong> {{ props.actividad.fecha_i }}
                </p>
                <p class="my-1">
                    <strong>Fecha de entrega: </strong> {{ props.actividad.fecha_e }}
                </p>
                <p class="my-1">
                    <strong>Periodo: </strong> {{ props.actividad.periodoD }}
                </p>
            </div>
            <div class="w-full">
                <DataTable class="w-full table-auto text-sm display nowrap stripe compact cell-border order-column"
                    id="tutoresTablaId" name="tutoresTablaId" :columns="columns" :data="props.calificaciones" :options="{
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
                                #
                            </th>
                            <th class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">Alumno</th>
                            <th class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">Calificación</th>
                        </tr>
                    </thead>
                </DataTable>
            </div>
        </div>
    </ProfeLayout>
</template>
<style>
.alturaM {
    min-height: 80vh;
}
</style>