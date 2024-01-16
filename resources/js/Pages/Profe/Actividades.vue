<script setup>
// Importaciones necesarias para la funcionalidad de la vista en general
import { ref, onMounted } from 'vue';
import ProfeLayout from '@/Layouts/ProfeLayout.vue';
import FormularioActividades from '@/Components/profe/FormularioActividades.vue';
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
    actividad: { type: Object },
});

const columns = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return `<input type="checkbox" class="actividad-checkbox" data-id="${row.idActividad}" ">`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) { return meta.row + 1 }
    },
    { data: 'descripcion' },
    { data: 'idClase' },
    { data: 'idPeriodo' },
    { data: 'idTipoActividad' },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="editar-button" data-id="${row.idActividad}"><i class="fa fa-pencil"></i></button>`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="eliminar-button" data-id="${row.idActividad}"><i class="fa fa-trash"></i></button>`;
        }

    }

];

const botones = [{
    title: 'Actividades registrados',
    extend: 'excelHtml5',
    text: '<i class="fa-solid fa-file-excel"></i> Excel',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Actividades registrados',
    extend: 'pdfHtml5',
    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Actividades registrados',
    extend: 'print',
    text: '<i class="fa-solid fa-print"></i> Imprimir',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Actividades registrados',
    extend: 'copy',
    text: '<i class="fa-solid fa-copy"></i> Copiar Texto',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
];

// Constantes para el funcionamientos del modal y su configuración
const mostrarModal = ref(false);
const mostrarModalE = ref(false);
const maxWidth = 'xl';
const closeable = true;
// Constantes para el formulario
var activi = ({});
const form = useForm({});
const selectedActividad = ref([]);

// Funciones para el funcionamiento del modal
const abrirE = ($actividad) => {
    activi = $actividad;
    mostrarModalE.value = true;
}
const cerrarModal = () => {
    mostrarModal.value = false;
};
const cerrarModalE = () => {
    mostrarModalE.value = false;
};

// Función para elimnar profesores a través del botón del datatable
const eliminarActividad = (idActividad, descripcion) => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: `¿Estas seguro que deseas eliminar los datos de ` + descripcion + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('profe.eliminarActividades', idActividad));
        }

    })
};

// Función para el llenado de selectedPersonal a través de los checkboxes
const toggleActividadSelection = (actividad) => {
    if (selectedActividad.value.includes(actividad)) {
        // Si la personal ya está seleccionada, la eliminamos del array
        selectedActividad.value = selectedActividad.value.filter((a) => a !== actividad);
    } else {
        // Si la personal no está seleccionada, la agregamos al array
        selectedActividad.value.push(actividad);
    }
    const botonEliminar = document.getElementById("eliminarABtn");

    if (selectedActividad.value.length > 0) {
        botonEliminar.removeAttribute("disabled");
    } else {
        botonEliminar.setAttribute("disabled", "");
    }
};

// Función para eliminar varios profesores con el botón eliminar
const eliminarActividades = () => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })

    swal.fire({
        title: '¿Estas seguro que deseas eliminar los datos de las actividades seleccionadas?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const actividadS = selectedActividad.value.map((actividad) => actividad.idActividad);
                const $actividadIds = actividadS.join(',');
                await form.delete(route('profe.elimActividades', $actividadIds));
                const botonEliminar = document.getElementById("eliminarABtn");
                // Limpia las materias seleccionadas después de la eliminación
                selectedActividad.value = [];
                botonEliminar.setAttribute("disabled", "");
            } catch (error) {
                console.log("Error al eliminar varias actividades: " + error);
            }
        }
    });
};

// Función onMounted que se ejecuta al iniciar la página 
onMounted(() => {
    // Agrega un escuchador de eventos fuera de la lógica de Vue
    document.getElementById('actividadesTablaId').addEventListener('click', (event) => {
        const checkbox = event.target;
        if (checkbox.classList.contains('actividad-checkbox')) {
            const actividadId = parseInt(checkbox.getAttribute('data-id'));
            // Asegúrate de que props.materias.data esté definido antes de usar find
            console.log(props.actividad);
            if (props.actividad) {
                const actividad = props.actividad.find(actividad => actividad.idActividad === actividadId);
                if (actividad) {
                    toggleActividadSelection(actividad);
                } else {
                    console.log("No se tiene actividad");
                }
            }
        }
    });

    // Manejar clic en el botón de editar
    $('#actividadesTablaId').on('click', '.editar-button', function () {
        const actividadId = $(this).data('id');
        const actividad = props.actividad.find(a => a.idActividad === actividadId);
        abrirE(actividad);
    });

    // Manejar clic en el botón de eliminar
    $('#actividadesTablaId').on('click', '.eliminar-button', function () {
        const actividadId = $(this).data('id');
        const actividad = props.actividad.find(a => a.idActividad === actividadId);
        eliminarActividad(actividadId, actividad.descripcion);
    });
});

</script>

<template>
    <ProfeLayout title="Actividades">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <h2 class="text-black text-2xl text-center font-semibold p-5">Actividades</h2>
            <div class="my-1"></div> <!-- Espacio de separación -->
            <!-- Línea con gradiente -->
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
            <!-- //////////////////////////////////////////////////////////////////////////////////////////////// -->
            <!--  // Mensaje para mostrar si se guardo o borro un profesor                                        -->
            <div v-if="$page.props.flash.message"
                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert">
                <span class="font-medium">
                    {{ $page.props.flash.message }}
                </span>
            </div>
            <!-- //////////////////////////////////////////////////////////////////////////////////////////////// -->
            <div class="py-3 flex flex-col md:flex-row md:items-start md:space-x-3 space-y-3 md:space-y-0">
                <!-- <div class="w-full md:w-1/3 mb-4 md:mb-0 "></div> -->
                <!-- <div class="w-full md:w-2/3 space-y-4 md:space-y-0 md:space-x-4 md:flex md:items-center md:justify-end"> -->
                <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                    @click="mostrarModal = true" data-bs-toggle="modal" data-bs-target="#modalCreate">
                    <i class="fa fa-plus mr-2"></i>Agregar Actividad
                </button>
                <button id="eliminarABtn" disabled="true"
                    class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                    @click="eliminarActividades">
                    <i class="fa fa-trash mr-2"></i>Borrar Actividad(s)
                </button>
                <!-- </div> -->
            </div>

            <div class="overflow-x-auto ">
                <DataTable class="w-full table-auto text-sm display stripe compact cell-border order-column"
                    id="actividadesTablaId" :columns="columns" :data="actividad" :options="{
                        autoWidth: false, dom: 'Bfrtip', language: {
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
                                Descripción
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Clase
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Periodo
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Tipo de actividad
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
    </ProfeLayout>
</template>