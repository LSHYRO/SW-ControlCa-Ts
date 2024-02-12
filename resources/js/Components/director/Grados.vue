<script setup>
// Importaciones necesarias para la funcionalidad de la vista en general
import { ref, onMounted } from 'vue';
import FormularioGrado from '@/Components/director/FormularioGrado.vue';
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

const props = defineProps({
    grados: { type: Object },
    grupos: { type: Object },
    //ciclos: { type: Object },

});

const mostrarModal = ref(false);
const mostrarModalE = ref(false);
const maxWidth = 'xl';
const closeable = true;

var gradoE = ({});

const selectedGrados = ref([]);

const form = useForm({});

const abrirGrados = ($gradoss) => {
    gradoE = $gradoss;
    mostrarModalE.value = true;
    console.log($gradoss);
    console.log(gradoE);
}

const cerrarModal = () => {
    mostrarModal.value = false;
};
const cerrarModalE = () => {
    mostrarModalE.value = false;
};

const columnsGrados = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return "";
        }
    },
    {
        data: null,
        render: function (data, type, row, meta) {
            return `<input type="checkbox" class="grado-checkbox" data-id="${row.idGrado}" ">`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) { return meta.row + 1 }
    },
    { data: 'grado' },
    /* {
        data: 'idCiclo',
        render: function (data, type, row, meta) {
            // Modificación para mostrar la descripción del ciclo
            const ciclo = props.ciclos.find(ciclo => ciclo.idCiclo === data);
            return ciclo ? ciclo.descripcionCiclo : '';
        }
    }, */
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="editar-button" data-id="${row.idGrado}"><i class="fa fa-pencil"></i></button>`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="eliminar-button" data-id="${row.idGrado}"><i class="fa fa-trash"></i></button>`;
        }

    }
];

const botonesGrado = [{
    title: 'Grados registrados',
    extend: 'excelHtml5',
    text: '<i class="fa-solid fa-file-excel"></i> Excel',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded',
    exportOptions: {
        columns: [2, 3]
    }, 
},
{
    title: 'Grados registrados',
    extend: 'pdfHtml5',
    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded',
    exportOptions: {
        columns: [2, 3]
    },    
    orientation: 'landscape',
},
{
    title: 'Grados registrados',
    extend: 'print',
    text: '<i class="fa-solid fa-print"></i> Imprimir',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded',
    exportOptions: {
        columns: [2, 3]
    }, 
},
{
    title: 'Grados registrados',
    extend: 'copy',
    text: '<i class="fa-solid fa-copy"></i> Copiar Texto',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded',
    exportOptions: {
        columns: [2, 3]
    }, 
},
];

const toggleGradoSelection = (grado) => {
    if (selectedGrados.value.includes(grado)) {
        // Si la materia ya está seleccionada, la eliminamos del array
        console.log("Se quito dl grupo de la seleccion");
        selectedGrados.value = selectedGrados.value.filter((g) => g !== grado);
    } else {
        // Si la materia no está seleccionada, la agregamos al array
        console.log("Se agrego un grado a la selección");
        selectedGrados.value.push(grado);

    }
    const botonEliminar = document.getElementById("eliminarGBtn");

    if (selectedGrados.value.length > 0) {
        botonEliminar.removeAttribute("disabled");
        console.log("Se ha habilitado el botón");
    } else {
        botonEliminar.setAttribute("disabled", "");
        console.log("Se ha deshabilitado el botón");
    }
};

const eliminarGrado = (idGrado, grado) => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: `¿Estas seguro que deseas eliminar los datos de ` + grado + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('director.eliminarGrados', idGrado));
        }

    })
};

const eliminarGrados = () => {
    console.log("Entre en eliminar Grados");
    const swal = Swal.mixin({
        buttonsStyling: true
    })

    swal.fire({
        title: '¿Estas seguro que deseas eliminar los datos de los grados seleccionados?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            console.log("Se confirmó eliminar");
            try {
                const gradosS = selectedGrados.value.map((grado) => grado.idGrado);
                const $gradosIds = gradosS.join(',');
                console.log(gradosS);
                console.log($gradosIds);
                await form.delete(route('director.elimGrados', $gradosIds));

                // Limpia las materias seleccionadas después de la eliminación
                selectedGrados.value = [];
            } catch (error) {
                console.log('El error se origina aquí');
                console.log("El error es: "+error);
            }
        }
    });
};

onMounted(() => {
    console.log("Estoy en onMounted");
    // Agrega un escuchador de eventos fuera de la lógica de Vue
    document.getElementById('gradosTablaId').addEventListener('click', (event) => {
        const checkbox = event.target;
        console.log(checkbox);
        if (checkbox.classList.contains('grado-checkbox')) {
            const gradoId = parseInt(checkbox.getAttribute('data-id'));
            // Se asegura que props.materias.data esté definido antes de usar find
            if (props.grados) {
                const grado = props.grados.find(grado => grado.idGrado === gradoId);
                if (grado) {
                    toggleGradoSelection(grado);
                } else {
                    console.log("No se tiene grado");
                }
            }
        }
    });

    // Manejar clic en el botón de editar
    $('#gradosTablaId').on('click', '.editar-button', function () {
        console.log("Entró en editar");
        const gradoId = $(this).data('id');
        const grado = props.grados.find(g => g.idGrado === gradoId);
        abrirGrados(grado);
    });

    // Manejar clic en el botón de eliminar
    $('#gradosTablaId').on('click', '.eliminar-button', function () {
        const gradoId = $(this).data('id');
        const grado = props.grados.find(g => g.idGrado === gradoId);
        eliminarGrado(gradoId, grado.grado);
    });
});

const optionsGrado = {
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
    buttons: botonesGrado,
};

</script>

<template>
    <div class="mt-8 bg-white p-4 shadow rounded-lg">
        <h2 class="text-black text-2xl text-center font-semibold p-5">Grados</h2>
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
                <i class="fa fa-plus mr-2"></i>Agregar Grado
            </button>
            <button id="eliminarGBtn" disabled="true"
                class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded" @click="eliminarGrados">
                <i class="fa fa-trash mr-2"></i>Borrar Grado(s)
            </button>
            <!--</div>-->
        </div>
        <!--<div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>-->
        <!-- Línea con gradiente -->
        <div class="overflow-x-auto">
            <DataTable class="w-full table-auto text-sm display stripe compact cell-border order-column" id="gradosTablaId"
                :columns="columnsGrados" :data="grados" :options="optionsGrado">
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
                            Grado
                        </th>
                        <!--
                        <th
                            class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            Ciclo
                        </th>
                        -->
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
    <formulario-grado :show="mostrarModal" :max-width="maxWidth" :closeable="closeable" @close="cerrarModal"
        :title="'Añadir grado'" :op="'1'" :modal="'modalCreate'" :ciclos="props.ciclos"></formulario-grado>
    <formulario-grado :show="mostrarModalE" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalE"
        :title="'Editar grado'" :op="'2'" :modal="'modalEdit'" :grados="gradoE" :ciclos="props.ciclos"></formulario-grado>
</template>