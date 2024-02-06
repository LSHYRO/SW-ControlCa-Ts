<script setup>
////////////////////////////////////////////////////////////////////////////////////////////////
// Importaciones necesarias para la funcionalidad de la vista en general
import { ref, onMounted } from 'vue';
import DirectorLayout from '@/Layouts/DirectorLayout.vue';
import FormAvisos from '@/Components/director/FormularioAvisos.vue'
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
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Variables e inicializaciones necesarias para el datatable y el uso de generacion de 
// documentos
window.JSZip = jsZip;
pdfmake.vfs = pdfFonts.pdfMake.vfs;
DataTable.use(DataTablesLib);
DataTable.use(ButtonsHtml5);
DataTable.use(pdfmake);
DataTable.use(Select);
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Variables que recibe la vista 
const props = defineProps({
    avisos: { type: Object },
    usuario: { type: Object }
});
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Configuración de las columnas con los valores correspondientes de las materias, ademas de
// la creacion de las checkboxes y los botones de editar y modificar
const columns = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return `<input type="checkbox" class="aviso-checkbox" data-id="${row.idAviso}" ">`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) { return meta.row + 1 }
    },
    { data: 'titulo' },
    { data: 'descripcion' },
    { data: 'fecha_ini' },
    { data: 'fecha_fi' },
    { data: 'nombre' },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="editar-button" data-id="${row.idAviso}"><i class="fa fa-pencil"></i></button>`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="eliminar-button" data-id="${row.idAviso}"><i class="fa fa-trash"></i></button>`;
        }

    }

];
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Creación de los botones para al generación de documentos, ademas de la configuración de los
// titulos de los documentos
const botones = [{
    title: 'Avisos registrados',
    extend: 'excelHtml5',
    text: '<i class="fa-solid fa-file-excel"></i> Excel',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Avisos registrados',
    extend: 'pdfHtml5',
    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Avisos registrados',
    extend: 'print',
    text: '<i class="fa-solid fa-print"></i> Imprimir',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Avisos registrados',
    extend: 'copy',
    text: '<i class="fa-solid fa-copy"></i> Copiar Texto',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
];
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Constantes para los modales
const mostrarModal = ref(false);
const mostrarModalEditar = ref(false);
// Constantes para la configuracion del modal
const maxWidth = 'xl';
const closeable = true;
// Variable y constante para las materias y la seleccion de estas
const avisoE = ref({});
// Creación de la constante para el formulario (guardar los datos)
const form = useForm({});
//
const descripcionAgregar = "Rellene todos los campos para poder registrar un nuevo aviso";
//
const descripcionModificar = "Rellene todos los campos para poder actualizar los datos del aviso";
//
const selectedAvisos = ref([]);
////////////////////////////////////////////////////////////////////////////////////////////////

const actDesModal = () => {
    mostrarModal.value = !mostrarModal.value;
};

const actDesModalEditar = () => {
    mostrarModalEditar.value = !mostrarModalEditar.value;
};

const abrirE = (aviso) => {
    avisoE.value = aviso;
    actDesModalEditar();
}
////////////////////////////////////////////////////////////////////////////////////////////////
// Metodo para la seleccion y llenado del arreglo de materias (selectedAvisos) por medio del 
// checkbox
const toggleAvisoSelection = (aviso) => {
    if (selectedAvisos.value.includes(aviso)) {
        // Si la materia ya está seleccionada, la eliminamos del array
        selectedAvisos.value = selectedAvisos.value.filter((a) => a !== aviso);
    } else {
        // Si la materia no está seleccionada, la agregamos al array
        selectedAvisos.value.push(aviso);
    }
    // Llamado del botón de eliminar para cambiar su estado deshabilitado
    const botonEliminar = document.getElementById("eliminarMBtn");
    // Cambio de estado del botón eliminar dependiendo de las materias seleccionadas
    if (selectedAvisos.value.length > 0) {
        botonEliminar.removeAttribute("disabled");
    } else {
        botonEliminar.setAttribute("disabled", "");
    }
};
////////////////////////////////////////////////////////////////////////////////////////////////

const eliminarAviso = (idAviso, titulo) => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: `¿Estas seguro que deseas eliminar el aviso:  ` + titulo + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('director.eliminarAv', idAviso));
        }

    })
};

////////////////////////////////////////////////////////////////////////////////////////////////
// Función para eliminar varias materias a la vez (a tráves del bóton eliminar)
const eliminarAvisos = () => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: '¿Estas seguro que deseas eliminar los datos de los avisos seleccionados?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const avisosS = selectedAvisos.value.map((aviso) => aviso.idAviso);
                const $avisosIds = avisosS.join(',');
                await form.delete(route('director.eliminarAvs', $avisosIds));

                selectedAvisos.value = [];
                const botonEliminar = document.getElementById("eliminarMBtn");
                
                if (selectedAvisos.value.length <= 0) {                    
                    botonEliminar.setAttribute("disabled", "");
                }
            } catch (error) {
                console.log("Error al eliminar varios avisos: " + error);
            }
        }
    });
};
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Función onMounted que se ejecuta al iniciar la vista
onMounted(() => {
    // Agrega un escuchador de eventos fuera de la lógica de Vue
    document.getElementById('avisosTablaId').addEventListener('click', (event) => {
        const checkbox = event.target;
        if (checkbox.classList.contains('aviso-checkbox')) {
            const avisoId = parseInt(checkbox.getAttribute('data-id'));
            // Se asegura que props.materias.data esté definido antes de usar find
            if (props.avisos) {
                const aviso = props.avisos.find(aviso => aviso.idAviso === avisoId);
                if (aviso) {
                    toggleAvisoSelection(aviso);
                } else {
                    console.log("No se tiene aviso");
                }
            }
        }
    });

    // Manejar clic en el botón de editar
    $('#avisosTablaId').on('click', '.editar-button', function () {
        const avisoId = $(this).data('id');
        const aviso = props.avisos.find(a => a.idAviso === avisoId);
        abrirE(aviso);
    });

    // Manejar clic en el botón de eliminar
    $('#avisosTablaId').on('click', '.eliminar-button', function () {
        const avisoId = $(this).data('id');
        const aviso = props.avisos.find(a => a.idAviso === avisoId);
        eliminarAviso(avisoId, aviso.titulo);
    });
});
////////////////////////////////////////////////////////////////////////////////////////////////
</script>
<template>
    <DirectorLayout title="Avisos" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <h2 class="text-black text-2xl text-center font-semibold p-5">Avisos</h2>
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
            <div class="py-3 flex flex-col md:flex-row md:items-start md:space-x-3 space-y-3 md:space-y-0">
                <!--<div class="w-full md:w-2/3 space-y-4 md:space-y-0 md:space-x-4 md:flex md:items-center md:justify-start">-->
                <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                    @click="mostrarModal = true" data-bs-toggle="modal" data-bs-target="#modalCreate">
                    <i class="fa fa-plus mr-2"></i>Agregar Aviso
                </button>
                <button id="eliminarMBtn" disabled="true"
                    class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                    @click="eliminarAvisos()">
                    <i class="fa fa-trash mr-2"></i>Borrar Aviso(s)
                </button>
                <!--</div>-->
            </div>
            <div>
                <DataTable class="w-full table-auto text-sm display stripe compact cell-border order-column"
                    id="avisosTablaId" :columns="columns" :data="props.avisos" :options="{
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
                                Titulo
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Descripción
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Inicio
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Fin
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Publicador
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
        <!--
        <formulario-materia :show="mostrarModal" :max-width="maxWidth" :closeable="closeable" @close="cerrarModal"
            :title="'Añadir materia'" :op="'1'" :modal="'modalCreate'"></formulario-materia>
        <formulario-materia :show="mostrarModalE" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalE"
            :title="'Editar materia'" :op="'2'" :modal="'modalEdit'" :materias="mater"></formulario-materia>
            -->
        <FormAvisos :title="'Añadir Aviso'" :show="mostrarModal" :max-width="maxWidth" :closeable="closeable"
            @close="actDesModal()" :op="'1'" :descripcion="descripcionAgregar" :modal="'modalCreate'" />
        <FormAvisos :title="'Editar Aviso'" :show="mostrarModalEditar" :max-width="maxWidth" :closeable="closeable"
            @close="actDesModalEditar()" :op="'2'" :descripcion="descripcionModificar" :aviso="avisoE"
            :modal="'modalCreate'" />
    </DirectorLayout>
</template>
<style>
.swal2-popup {
    font-size: 15px !important;
}
</style>