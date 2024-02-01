<script setup>
// Importaciones necesarias para la funcionalidad de la vista en general
import { ref, onMounted } from 'vue';
import FormularioTutores from './FormularioTutores.vue';
import FormularioCiclos from '@/Components/admin/FormularioCiclos.vue';
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

// Variables e inicializaciones necesarias para el datatable y el uso de generacion de 
// documentos
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

const mostrarModal = ref(false);
const mostrarModalE = ref(false);
const maxWidth = 'xl';
const closeable = true;

var cicloE = ({});

const selectedCiclos = ref([]);

const form = useForm({});

const abrirCiclos = ($cicloss) => {
    cicloE = $cicloss;
    mostrarModalE.value = true;
}

const cerrarModal = () => {
    mostrarModal.value = false;
};
const cerrarModalE = () => {
    mostrarModalE.value = false;
};

const columnsCiclo = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return `<input type="checkbox" class="ciclo-checkbox" data-id="${row.idCiclo}" ">`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) { return meta.row + 1 }
    },
    { data: 'fecha_inicio' },
    { data: 'fecha_fin' },
    { data: 'descripcionCiclo' },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="editar-button" data-id="${row.idCiclo}"><i class="fa fa-pencil"></i></button>`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="eliminar-button" data-id="${row.idCiclo}"><i class="fa fa-trash"></i></button>`;
        }

    }
];

const botonesCiclo = [{
    title: 'Ciclos registrados',
    extend: 'excelHtml5',
    text: '<i class="fa-solid fa-file-excel"></i> Excel',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Ciclos registrados',
    extend: 'pdfHtml5',
    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Ciclos registrados',
    extend: 'print',
    text: '<i class="fa-solid fa-print"></i> Imprimir',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Ciclos registrados',
    extend: 'copy',
    text: '<i class="fa-solid fa-copy"></i> Copiar Texto',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
];

const toggleCicloSelection = (ciclo) => {
    if (selectedCiclos.value.includes(ciclo)) {
        // Si la materia ya está seleccionada, la eliminamos del array
        console.log("Se quito la materia del la seleccion");
        selectedCiclos.value = selectedCiclos.value.filter((c) => c !== ciclo);
    } else {
        selectedCiclos.value.push(ciclo);

    }
    const botonEliminar = document.getElementById("eliminarCBtn");

    if (selectedCiclos.value.length > 0) {
        botonEliminar.removeAttribute("disabled");
        console.log("Se ha habilitado el botón");
    } else {
        botonEliminar.setAttribute("disabled", "");
        console.log("Se ha deshabilitado el botón");
    }
};

const eliminarCiclo = (idCiclo, ciclo) => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: `¿Estas seguro que deseas eliminar los datos de ` + ciclo + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('admin.eliminarCiclos', idCiclo));
        }

    })
};

const eliminarCiclos = () => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })

    swal.fire({
        title: '¿Estas seguro que deseas eliminar los datos de los ciclos seleccionados?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const ciclosS = selectedCiclos.value.map((ciclo) => ciclo.idCiclo);
                const $ciclosIds = ciclosS.join(',');
                console.log(ciclosS);
                await form.delete(route('admin.elimCiclos', $ciclosIds));

                // Limpia las materias seleccionadas después de la eliminación
                selectedCiclos.value = [];
            } catch (error) {
                console.log('El error se origina aquí');
                console.log(error);
            }
        }
    });
};

onMounted(() => {
    // Agrega un escuchador de eventos fuera de la lógica de Vue
    document.getElementById('ciclosTablaId').addEventListener('click', (event) => {
        const checkbox = event.target;
        console.log(checkbox);
        if (checkbox.classList.contains('ciclo-checkbox')) {
            const cicloId = parseInt(checkbox.getAttribute('data-id'));
            // Se asegura que props.materias.data esté definido antes de usar find
            if (props.ciclos) {
                const ciclo = props.ciclos.find(ciclo => ciclo.idCiclo === cicloId);
                if (ciclo) {
                    toggleCicloSelection(ciclo);
                } else {
                    console.log("No se tiene tutor");
                }
            }
        }
    });

    // Manejar clic en el botón de editar
    $('#ciclosTablaId').on('click', '.editar-button', function () {
        const cicloId = $(this).data('id');
        const ciclo = props.ciclos.find(c => c.idCiclo === cicloId);
        abrirCiclos(ciclo);
    });

    // Manejar clic en el botón de eliminar
    $('#ciclosTablaId').on('click', '.eliminar-button', function () {
        const cicloId = $(this).data('id');
        const ciclo = props.ciclos.find(c => c.idCiclo === cicloId);
        eliminarCiclo(cicloId, ciclo.descripcionCiclo);
    });
});

const optionsCiclo = {
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
    buttons: botonesCiclo,
};

</script>

<template>
    <div class="mt-8 bg-white p-4 shadow rounded-lg">
        <h2 class="text-black text-2xl text-center font-semibold p-5">Ciclos</h2>
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
                @click="mostrarModal = true" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="fa fa-plus mr-2"></i>Agregar Ciclo
            </button>
            <button id="eliminarCBtn" disabled="true"
                class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded" @click="eliminarCiclos">
                <i class="fa fa-trash mr-2"></i>Borrar Ciclo(s)
            </button>
            <!--</div>-->
        </div>
        <!--<div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>-->
        <!-- Línea con gradiente -->
        <div class="overflow-x-auto">
            <DataTable class="w-full table-auto text-sm display stripe compact cell-border order-column" id="ciclosTablaId"
                :columns="columnsCiclo" :data="ciclos" :options="optionsCiclo">
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
                            Fecha de inicio
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            Fecha de terminación
                        </th>
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            Descripción
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
    <formulario-ciclos :show="mostrarModal" :max-width="maxWidth" :closeable="closeable" @close="cerrarModal"
        :title="'Añadir ciclo'" :op="'1'" :modal="'modalCreate'"></formulario-ciclos>
    <formulario-ciclos :show="mostrarModalE" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalE"
        :title="'Editar ciclo'" :op="'2'" :modal="'modalEdit'" :ciclos="cicloE"></formulario-ciclos>
</template>