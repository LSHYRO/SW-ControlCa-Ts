<script setup>
////////////////////////////////////////////////////////////////////////////////////////////////
// Importaciones necesarias para la vista 
import { ref, onMounted } from 'vue';
import SecreLayout from '@/Layouts/SecreLayout.vue';
import FormularioProf from '@/Components/secre/FormularioProf.vue';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3';
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
// Variables e inicializaciones para el datatable
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

////////////////////////////////////////////////////////////////////////////////////////////////
// Variables que recibe la vista para la funcionalidad
const props = defineProps({
    personal: { type: Object },
    tipoSangre: { type: Object },
    generos: { type: Object },
    usuario: { type: Object }
});
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Constantes para el funcionamientos del modal y su configuración
const mostrarModal = ref(false);
const mostrarModalE = ref(false);
const maxWidth = 'xl';
const closeable = true;
// Constantes para el formulario
var person = ({});
const form = useForm({});
const selectedPersonal = ref([]);
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Creación de la constante para el llenado de datos del datatable, donde tambien se crea los 
// checkboxes y los botones editar y eliminar
const columns = [
    {
        data: null, render: function () {
            return '';
        }
    },
    {
        data: null,
        render: function (data, type, row, meta) {
            return `<input type="checkbox" class="profesor-checkbox" data-id="${row.idPersonal}" ">`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) { return meta.row + 1 }
    },
    { data: 'nombre' },
    { data: 'apellidoP' },
    { data: 'apellidoM' },
    { data: 'fechaNacimiento' },
    { data: 'genero' },
    { data: 'CURP' },
    { data: 'RFC' },
    { data: 'correoElectronico' },
    {
        data: null, render: function (data, type, row, meta) {
            return row.numTelefono + " " + `<a href="tel:${row.numTelefono} "><i class="fa fa-phone" aria-hidden="true"></i></a>`
        }
    },
    { data: 'tipoSangre' },
    { data: 'alergias' },
    { data: 'discapacidad' },
    { data: 'direccion' },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="editar-button" data-id="${row.idPersonal}"><i class="fa fa-pencil"></i></button>`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="eliminar-button" data-id="${row.idPersonal}"><i class="fa fa-trash"></i></button>`;
        }

    }
];
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Constante de botones para la generación de documentos además de la configuración del titulo
// de los documentos
const botones = [{
    title: 'Profesores registradas',
    extend: 'excelHtml5',
    text: '<i class="fa-solid fa-file-excel"></i> Excel',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Profesores registradas',
    extend: 'pdfHtml5',
    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Profesores registradas',
    extend: 'print',
    text: '<i class="fa-solid fa-print"></i> Imprimir',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Profesores registradas',
    extend: 'copy',
    text: '<i class="fa-solid fa-copy"></i> Copiar Texto',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
];
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Funciones para el funcionamiento del modal
const abrirE = ($profe) => {
    person = $profe;
    mostrarModalE.value = true;
}
const cerrarModal = () => {
    mostrarModal.value = false;
};
const cerrarModalE = () => {
    mostrarModalE.value = false;
};
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Función para elimnar profesores a través del botón del datatable
const eliminarProfesor = (idPersonal, nombre) => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: `¿Estas seguro que deseas eliminar los datos de ` + nombre + '?' + '\nTodo lo relacionado al profesor (clases) será eliminado.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('secre.eliminarProfesores', idPersonal));
        }

    })
};
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Función para el llenado de selectedPersonal a través de los checkboxes
const togglePersonalSelection = (personal) => {
    if (selectedPersonal.value.includes(personal)) {
        // Si la personal ya está seleccionada, la eliminamos del array
        selectedPersonal.value = selectedPersonal.value.filter((m) => m !== personal);
    } else {
        // Si la personal no está seleccionada, la agregamos al array
        selectedPersonal.value.push(personal);
    }
    const botonEliminar = document.getElementById("eliminarPBtn");

    if (selectedPersonal.value.length > 0) {
        botonEliminar.removeAttribute("disabled");
    } else {
        botonEliminar.setAttribute("disabled", "");
    }
};
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Función para eliminar varios profesores con el botón eliminar
const eliminarProfesores = () => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })

    swal.fire({
        title: '¿Estas seguro que deseas eliminar los datos de los profesores seleccionados?' + '\nTodo lo relacionado a los profesores (clases) serán eliminados.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const personalS = selectedPersonal.value.map((personal) => personal.idPersonal);
                const $personalIds = personalS.join(',');
                await form.delete(route('secre.elimProfesores', $personalIds));
                const botonEliminar = document.getElementById("eliminarPBtn");
                // Limpia las materias seleccionadas después de la eliminación
                selectedPersonal.value = [];
                botonEliminar.setAttribute("disabled", "");
            } catch (error) {
                console.log("Error al eliminar varias materias: " + error);
            }
        }
    });
};
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Función onMounted que se ejecuta al iniciar la página 
onMounted(() => {
    // Agrega un escuchador de eventos fuera de la lógica de Vue
    document.getElementById('profesoresTablaId').addEventListener('click', (event) => {
        const checkbox = event.target;
        if (checkbox.classList.contains('profesor-checkbox')) {
            const personalId = parseInt(checkbox.getAttribute('data-id'));
            // Asegúrate de que props.materias.data esté definido antes de usar find
            console.log(props.personal);
            if (props.personal) {
                const personal = props.personal.find(personal => personal.idPersonal === personalId);
                if (personal) {
                    togglePersonalSelection(personal);
                } else {
                    console.log("No se tiene materia");
                }
            }
        }
    });

    // Manejar clic en el botón de editar
    $('#profesoresTablaId').on('click', '.editar-button', function () {
        const personalId = $(this).data('id');
        const personal = props.personal.find(m => m.idPersonal === personalId);
        abrirE(personal);
    });

    // Manejar clic en el botón de eliminar
    $('#profesoresTablaId').on('click', '.eliminar-button', function () {
        const personalId = $(this).data('id');
        const personal = props.personal.find(m => m.idPersonal === personalId);
        eliminarProfesor(personalId, personal.nombre + " " + personal.apellidoP + " " + personal.apellidoM);
    });
});
////////////////////////////////////////////////////////////////////////////////////////////////
</script>

<template>
    <SecreLayout title="profesores" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <h2 class="text-black text-2xl text-center font-semibold p-5">Docentes</h2>
            <div class="my-1"></div> <!-- Espacio de separación -->
            <!-- Línea con gradiente -->
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
            <!-- //////////////////////////////////////////////////////////////////////////////////////////////// -->
            <!--  // Mensaje para mostrar si se guardo o borro un profesor                                        -->
            <div v-if="$page.props.flash.message" class="p-4 mb-4 text-sm rounded-lg" role="alert"
                :class="`text-${$page.props.flash.color}-700 bg-${$page.props.flash.color}-100 dark:bg-${$page.props.flash.color}-200 dark:text-${$page.props.flash.color}-800`">
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
                    <i class="fa fa-plus mr-2"></i>Agregar docente
                </button>
                <button id="eliminarPBtn" disabled="true"
                    class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                    @click="eliminarProfesores">
                    <i class="fa fa-trash mr-2"></i>Borrar Docente(s)
                </button>
                <!-- </div> -->
            </div>

            <div>
                <DataTable class="w-full table-auto nowrap text-sm display stripe compact cell-border order-column"
                    id="profesoresTablaId" :columns="columns" :data="personal" :options="{
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
                                Apellido Paterno
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Apellido Materno
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                F. de nacimiento
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Genero
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                CURP
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                RFC
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Correo electronico
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Telefono
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Tipo de sangre
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Alergias
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Discapacidad(es)
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Direccion
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
        <formulario-prof :show="mostrarModal" :max-width="maxWidth" :closeable="closeable" @close="cerrarModal"
            :title="'Añadir profesor'" :op="'1'" :modal="'modalCreate'" :tipoSangre="props.tipoSangre"
            :generos="props.generos"></formulario-prof>
        <formulario-prof :show="mostrarModalE" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalE"
            :title="'Editar profesor'" :op="'2'" :modal="'modalEdit'" :personal="person" :tipoSangre="props.tipoSangre"
            :generos="props.generos"></formulario-prof>
    </SecreLayout>
</template>