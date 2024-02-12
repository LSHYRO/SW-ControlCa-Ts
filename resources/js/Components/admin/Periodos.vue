<script setup>
// Importaciones necesarias para la funcionalidad de la vista en general
import { ref, onMounted } from 'vue';
import FormularioTutores from './FormularioTutores.vue';
import FormularioPeriodos from '@/Components/admin/FormularioPeriodos.vue';
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

const props = defineProps({
    ciclos: { type: Object },
    periodos: { type: Object },
});

const mostrarModalPeriodos = ref(false);
const mostrarModalEPeriodos = ref(false);
const maxWidth = 'xl';
const closeable = true;

var periodoE = ({});

const selectedPeriodos = ref([]);

const form = useForm({});

const abrirPeriodos = ($periodossE) => {
    console.log("Entró en abrirPeriodos")
    periodoE = $periodossE;
    //periodoE = Object.assign({}, $periodossE); // Asegura que no se modifique el objeto original
    mostrarModalEPeriodos.value = true;
    console.log("Despues de mostrarModalPeriodos");
}

const cerrarModalPeriodos = () => {
    mostrarModalPeriodos.value = false;
};
const cerrarModalEPeriodos = () => {
    mostrarModalEPeriodos.value = false;
};

const columnsPeriodo = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return "";
        }
    },
    {
        data: null,
        render: function (data, type, row, meta) {
            return `<input type="checkbox" class="periodo-checkbox" data-id="${row.idPeriodo}" ">`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) { return meta.row + 1 }
    },
    { data: 'periodo' },
    { data: 'fecha_inicio' },
    { data: 'fecha_fin' },
    {
        data: 'idCiclo',
        render: function (data, type, row, meta) {
            // Modificación para mostrar la descripción del ciclo
            const ciclo = props.ciclos.find(ciclo => ciclo.idCiclo === data);
            return ciclo ? ciclo.descripcionCiclo : '';
        }
    },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="editar-button" data-id="${row.idPeriodo}"><i class="fa fa-pencil"></i></button>`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="eliminar-button" data-id="${row.idPeriodo}"><i class="fa fa-trash"></i></button>`;
        }
    }
];

const botonesPeriodo = [{
    title: 'Periodos registrados',
    extend: 'excelHtml5',
    text: '<i class="fa-solid fa-file-excel"></i> Excel',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded',
    exportOptions: {
            columns: [2, 3, 4, 5, 6]
        },
},
{
    title: 'Periodos registrados',
    extend: 'pdfHtml5',
    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded',
    exportOptions: {
            columns: [2, 3, 4, 5, 6]
        },
        orientation: 'landscape',
},
{
    title: 'Periodos registrados',
    extend: 'print',
    text: '<i class="fa-solid fa-print"></i> Imprimir',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded',
    exportOptions: {
            columns: [2, 3, 4, 5, 6]
        },
},
{
    title: 'Periodos registrados',
    extend: 'copy',
    text: '<i class="fa-solid fa-copy"></i> Copiar Texto',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded',
    exportOptions: {
            columns: [2, 3, 4, 5, 6]
        },
},
];

const togglePeriodoSelection = (periodo) => {
    if (selectedPeriodos.value.includes(periodo)) {
        // Si la materia ya está seleccionada, la eliminamos del array
        console.log("Se quito el periodo de la seleccion");
        selectedPeriodos.value = selectedPeriodos.value.filter((p) => p !== periodo);
    } else {
        // Si la materia no está seleccionada, la agregamos al array
        console.log("Se agrego un periodo a la selección");
        selectedPeriodos.value.push(periodo);

    }
    const botonEliminar = document.getElementById("eliminarPBtn");

    if (selectedPeriodos.value.length > 0) {
        botonEliminar.removeAttribute("disabled");
        console.log("Se ha habilitado el botón");
    } else {
        botonEliminar.setAttribute("disabled", "");
        console.log("Se ha deshabilitado el botón");
    }
};

const eliminarPeriodo = (idPeriodo, periodo) => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: `¿Estas seguro que deseas eliminar los datos de ` + periodo + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('admin.eliminarPeriodos', idPeriodo));
        }

    })
};

const eliminarPeriodos = () => {
    console.log('Eliminar periodos se ejecuta');
    console.log(selectedPeriodos);

    const swal = Swal.mixin({
        buttonsStyling: true
    })

    swal.fire({
        title: '¿Estas seguro que deseas eliminar los datos de los periodos seleccionados?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            console.log("confirmado")
            try {
                const periodosS = selectedPeriodos.value.map((periodo) => periodo.idPeriodo);
                const $periodosIds = periodosS.join(',');
                await form.delete(route('admin.elimPeriodos', $periodosIds));

                // Limpia los periodos seleccionados después de la eliminación
                selectedPeriodos.value = [];
            } catch (error) {
                console.log('El error se origina aquí');
                console.log(error);
            }
        }
    });
};

onMounted(() => {
    console.log("Entré en onMounted");
    // Agrega un escuchador de eventos fuera de la lógica de Vue
    document.getElementById('periodosTablaId').addEventListener('click', (event) => {
        const checkbox = event.target;
        console.log(checkbox)
        if (checkbox.classList.contains('periodo-checkbox')) {
            const periodoId = parseInt(checkbox.getAttribute('data-id'));
            // Se asegura que props.materias.data esté definido antes de usar find
            console.log(periodoId)
            if (props.periodos) {
                const periodo = props.periodos.find(periodo => periodo.idPeriodo === periodoId);
                if (periodo) {
                    togglePeriodoSelection(periodo);
                } else {
                    console.log("No se tiene periodo");
                }
            }
        }
    });

    // Manejar clic en el botón de editar
    $('#periodosTablaId').on('click', '.editar-button', function () {
        console.log("Estoy en editar");
        const periodoId = $(this).data('id');
        console.log("pasé de periodoId");
        const periodo = props.periodos.find(p => p.idPeriodo === periodoId);
        abrirPeriodos(periodo);
    });

    // Manejar clic en el botón de eliminar
    $('#periodosTablaId').on('click', '.eliminar-button', function () {
        const periodoId = $(this).data('id');
        const periodo = props.periodos.find(p => p.idPeriodo === periodoId);
        eliminarPeriodo(periodoId, periodo.periodo);
    });
});

const optionsPeriodo = {
    responsive: true,
    autoWidth: false,
    dom: 'Bfrtip',
    language: {
        search: 'Buscar',
        zeroRecords: 'No hay registros para mostrar',
        info: 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
        infoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
        infoFiltered: '(filtrado de un total de _MAX_ registros)',
        lengthMenu: 'Mostrar _MENU_ registros',
        paginate: { first: 'Primero', previous: 'Anterior', next: 'Siguiente', last: 'Ultimo' },
    },
    buttons: botonesPeriodo,
};

</script>

<template>
    <div class="mt-8 bg-white p-4 shadow rounded-lg">
        <h2 class="text-black text-2xl text-center font-semibold p-5">Periodos</h2>
        <div class="my-1"></div> <!-- Espacio de separación -->
        <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
        <!-- flash message start -->
        <div v-if="$page.props.flash.message" class="p-4 mb-4 text-sm rounded-lg" role="alert"
            :class="`text-${$page.props.flash.color}-700 bg-${$page.props.flash.color}-100 dark:bg-${$page.props.flash.color}-200 dark:text-${$page.props.flash.color}-800`">
            <span class="font-medium">
                {{ $page.props.flash.message }}
            </span>
        </div>
        <div class="py-3 flex flex-col md:flex-row md:items-start md:space-x-3 space-y-3 md:space-y-0">
            <!--<div class="w-full md:w-2/3 space-y-4 md:space-y-0 md:space-x-4 md:flex md:items-center md:justify-start">-->
            <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                @click="mostrarModalPeriodos = true" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="fa fa-plus mr-2"></i>Agregar Periodo
            </button>
            <button id="eliminarPBtn" disabled="true"
                class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded" @click="eliminarPeriodos">
                <i class="fa fa-trash mr-2"></i>Borrar Periodo(s)
            </button>
            <!--</div>-->
        </div>
        <!--<div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>-->
        <!-- Línea con gradiente -->
        <div class="">
            <DataTable class="w-full table-auto text-sm display stripe compact cell-border order-column"
                id="periodosTablaId" :columns="columnsPeriodo" :data="periodos" :options="optionsPeriodo">
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
                            Periodo
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            Fecha de inicio
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            Fecha de terminación
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            Ciclo
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
    <formulario-periodos :show="mostrarModalPeriodos" :max-width="maxWidth" :closeable="closeable"
        @close="cerrarModalPeriodos" :title="'Añadir periodo'" :op="'1'" :modal="'modalCreate'"
        :ciclos="props.ciclos"></formulario-periodos>
    <formulario-periodos :show="mostrarModalEPeriodos" :max-width="maxWidth" :closeable="closeable"
        @close="cerrarModalEPeriodos" :title="'Editar periodo'" :op="'2'" :modal="'modalEdit'" :period="periodoE"
        :ciclos="props.ciclos"></formulario-periodos>
</template>