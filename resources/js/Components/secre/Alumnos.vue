<script setup>
////////////////////////////////////////////////////////////////////////////////////////////////
// Importaciones necesarias para la funcionalidad de la vista en general
import { ref, onMounted } from 'vue';
import Swal from 'sweetalert2';
import FormularioAlumnos from './FormularioAlumnos.vue';
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
// Datos que recibe la vista para su uso
const props = defineProps({
    alumnos: { type: Object },
    generos: { type: Object },
    grados: { type: Object },
    grupos: { type: Object },
    tipoSangre: { type: Object },
    talleres: { type: Object },
    ciclos: { type: Object },
});
////////////////////////////////////////////////////////////////////////////////////////////////
console.log(props.grupos);
////////////////////////////////////////////////////////////////////////////////////////////////
// Constantes para los modales
const mostrarModal = ref(false);
const mostrarModalE = ref(false);
// Constantes para la configuracion del modal
const maxWidth = 'xl';
const closeable = true;
// Variable y constante para las materias y la seleccion de estas
var alumnoE = ({});
const alumnosSeleccionados = ref([]);
// Creación de la constante para el formulario (guardar los datos)
const form = useForm({});
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Metodos para el manejo del modal del formulario
const abrirE = ($alumno) => {
    alumnoE = $alumno;
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
// Configuración de las columnas con los valores correspondientes de las materias, ademas de
// la creacion de las checkboxes y los botones de editar y modificar
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
    { data: 'apellidoP' },
    { data: 'apellidoM' },
    { data: 'nombre' },
    { data: 'fechaNacimiento' },
    { data: 'CURP' },
    { data: 'genero' },
    {
        data: null, render: function (data, type, row, meta) {
            return row.numTelefono + " " + `<a href="tel:${row.numTelefono} "><i class="fa fa-phone" aria-hidden="true"></i></a>`
        }
    },
    { data: 'correoElectronico' },
    { data: 'tipoS' },
    { data: 'alergias' },
    { data: 'discapacidad' },
    { data: 'domicilio' },
    {
        data: 'esForaneo', render: function (data, type, row) {
            return data ? 'Si' : 'No';
        }
    },
    { data: 'grado' },
    { data: 'grupo' },
    { data: 'materia' },
    {
        data: null, render: function (data, type, row, meta) {
            return row.tutor + " " + `<a href="tel:${row.tutorTel} "><i class="fa fa-phone" aria-hidden="true"></i></a>`
        }
    },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="editar-button" data-id="${row.idAlumno}"><i class="fa fa-pencil"></i></button>`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="eliminar-button" data-id="${row.idAlumno}"><i class="fa fa-trash"></i></button>`;
        }

    }
];
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Creación de los botones para al generación de documentos, ademas de la configuración de los
// titulos de los documentos
const botones2 = [
    {
        title: 'Alumnos registrados',
        extend: 'excelHtml5',
        text: '<i class="fa-solid fa-file-excel"></i> Excel',
        className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded mb-2',
        exportOptions: {
            columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19]
        },
    },
    {
        title: 'Alumnos registrados',
        extend: 'pdfHtml5',
        text: '<i class="fa-solid fa-file-pdf"></i> PDF',
        className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded mb-2',
        exportOptions: {
            columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19]
        },
        orientation: 'landscape',
        pageSize: 'TABLOID'
    },
    {
        title: 'Alumnos registrados',
        extend: 'print',
        text: '<i class="fa-solid fa-print"></i> Imprimir',
        className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded mb-2',
        exportOptions: {
            columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19]
        },
        orientation: 'landscape',
    },
    {
        title: 'Alumnos registrados',
        extend: 'copy',
        text: '<i class="fa-solid fa-copy"></i> Copiar Texto',
        className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded mb-2',
        exportOptions: {
            columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19]
        }
    },
];
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Metodo para la seleccion y llenado del arreglo de tutores (selectedTutores) por medio del 
// checkbox
const toggleAlumnoSelection = (alumno) => {
    if (alumnosSeleccionados.value.includes(alumno)) {
        // Si el alumno ya está seleccionado, la eliminamos del array
        alumnosSeleccionados.value = alumnosSeleccionados.value.filter((a) => a !== alumno);
    } else {
        // Si el alumno no está seleccionado, la agregamos al array
        alumnosSeleccionados.value.push(alumno);
    }
    // Llamado del botón de eliminar para cambiar su estado deshabilitado
    const botonEliminar = document.getElementById("eliminarABtn");
    // Cambio de estado del botón eliminar dependiendo de las materias seleccionadas
    if (alumnosSeleccionados.value.length > 0) {
        botonEliminar.removeAttribute("disabled");
    } else {
        botonEliminar.setAttribute("disabled", "");
    }
};
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Funcion para eliminar alumno por alumno
const eliminarAlumno = (idAlumno, alumno) => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: `¿Estas seguro que deseas eliminar los datos de ` + alumno + '?' + '\nTodos lo relacionado al alumno (calificaciones) sera eliminado.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('secre.eliminarAlumno', idAlumno));
        }

    })
};

////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Función para eliminar varios alumnos a la vez (a tráves del bóton eliminar)
const eliminarAlumnos = () => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: '¿Estas seguro que deseas eliminar los datos de los alumnos seleccionadas?' + '\nTodo lo relacionado a los alumnos seleccionados (calificaciones) sera eliminado.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const alumnosE = alumnosSeleccionados.value.map((alumno) => alumno.idAlumno);
                const $alumnosIds = alumnosE.join(',');
                await form.delete(route('secre.elimAlumnos', $alumnosIds));
                // Limpia las materias seleccionadas después de la eliminación
                alumnosSeleccionados.value = [];
                const botonEliminar = document.getElementById("eliminarABtn");
                if (alumnosSeleccionados.value.length > 0) {
                    botonEliminar.removeAttribute("disabled");
                } else {
                    botonEliminar.setAttribute("disabled", "");
                }
            } catch (error) {
                console.log("Error al eliminar varios alumnos: " + error);
            }
        }
    });
};
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Función onMounted que se ejecuta al iniciar la vista
onMounted(() => {
    // Agrega un escuchador de eventos fuera de la lógica de Vue
    document.getElementById('alumnosTablaId').addEventListener('click', (event) => {
        const checkbox = event.target;
        if (checkbox.classList.contains('alumnos-checkboxes')) {
            const alumnoId = parseInt(checkbox.getAttribute('data-id'));
            // Se asegura que props.materias.data esté definido antes de usar find
            if (props.alumnos) {
                const alumno = props.alumnos.find(alumno => alumno.idAlumno === alumnoId);
                if (alumno) {
                    toggleAlumnoSelection(alumno);
                } else {
                    console.log("No se tiene alumno");
                }
            }
        }
    });

    // Manejar clic en el botón de editar
    $('#alumnosTablaId').on('click', '.editar-button', function () {
        const alumnoId = $(this).data('id');
        const alumno = props.alumnos.find(a => a.idAlumno === alumnoId);
        console.log(alumno);
        abrirE(alumno);
    });

    // Manejar clic en el botón de eliminar
    $('#alumnosTablaId').on('click', '.eliminar-button', function () {
        const alumnoId = $(this).data('id');
        const alumno = props.alumnos.find(a => a.idAlumno === alumnoId);
        eliminarAlumno(alumnoId, alumno.apellidoP + " " + alumno.apellidoM + " " + alumno.nombre);
    });
});
////////////////////////////////////////////////////////////////////////////////////////////////
</script>
<template>
    <div class="mt-8 bg-white p-4 shadow rounded-lg">
        <h2 class="text-black text-lg text-center font-semibold pb-4">Alumnos</h2>
        <div class="my-1"></div> <!-- Espacio de separación -->
        <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
        <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
        <!--  //Mensaje para mostrar el mensaje de que se ha borrado o agregado correctamente un alumno              -->
        <div v-if="$page.props.flash.message" class="p-4 mb-4 text-sm rounded-lg" role="alert"
            :class="`text-${$page.props.flash.color}-700 bg-${$page.props.flash.color}-100 dark:bg-${$page.props.flash.color}-200 dark:text-${$page.props.flash.color}-800`">
            <span class="font-medium">
                {{ $page.props.flash.message }}
            </span>
        </div>
        <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
        <div class="py-3 flex flex-col md:flex-row md:items-start md:space-x-3 space-y-3 md:space-y-0">
            <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                @click="mostrarModal = true" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="fa fa-plus mr-2"></i>Agregar Alumno
            </button>
            <button id="eliminarABtn" disabled
                class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded" @click="eliminarAlumnos">
                <i class="fa fa-trash mr-2"></i>Borrar Alumno(s)
            </button>
        </div>
        <div>
            <DataTable class="w-full table-auto text-sm display nowrap stripe compact cell-border order-column"
                id="alumnosTablaId" name="alumnosTablaId" :columns="columns2" :data="alumnos" :options="{
                    responsive: true, autoWidth: false, dom: 'Bfrtip', language: {
                        search: 'Buscar', zeroRecords: 'No hay registros para mostrar',
                        info: 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
                        infoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
                        infoFiltered: '(filtrado de un total de _MAX_ registros)',
                        lengthMenu: 'Mostrar _MENU_ registros',
                        paginate: { first: 'Primero', previous: 'Anterior', next: 'Siguiente', last: 'Ultimo' },
                    }, buttons: [botones2],
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
                            CURP
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            Genero
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            Telefono
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            Correo Elec.
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
                            Discapacidad
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            Domicilio
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            Foraneo
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
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                        </th>
                    </tr>
                </thead>
            </DataTable>
        </div>
    </div>
    <formulario-alumnos :show="mostrarModal" :max-width="maxWidth" :closeable="closeable" @close="cerrarModal"
        :title="'Añadir alumno'" :op="'1'" :modal="'modalCreate'" :generos="props.generos" :talleres="props.talleres"
        :tipoSangre="props.tipoSangre" :grados="props.grados" :grupos="props.grupos"
        :ciclos="props.ciclos"></formulario-alumnos>
    <formulario-alumnos :show="mostrarModalE" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalE"
        :title="'Editar alumno'" :op="'2'" :modal="'modalEdit'" :alumno="alumnoE" :generos="props.generos"
        :talleres="props.talleres" :tipoSangre="props.tipoSangre" :grados="props.grados" :grupos="props.grupos"
        :ciclos="props.ciclos"></formulario-alumnos>
</template>
<style>
.swal2-popup {
    font-size: 14px !important;
}
</style>