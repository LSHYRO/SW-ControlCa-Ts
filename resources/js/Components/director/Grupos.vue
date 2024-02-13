<script setup>
// Importaciones necesarias para la funcionalidad de la vista en general
import { ref, onMounted } from 'vue';
import FormularioGrupo from '@/Components/director/FormularioGrupo.vue';
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

const props = defineProps({
    grupos: { type: Object },
    //ciclos: { type: Object },

});

const mostrarModalGrupo = ref(false);
const mostrarModalEGrupo = ref(false);
const maxWidth = 'xl';
const closeable = true;

var grupoE = ({});

const selectedGrupos = ref([]);

const form = useForm({});

const abrirGrupos = ($gruposs) => {
    grupoE = $gruposs;
    mostrarModalEGrupo.value = true;
    console.log($gruposs);
    console.log(grupoE);
}

const cerrarModalGrupo = () => {
    mostrarModalGrupo.value = false;
};
const cerrarModalEGrupo = () => {
    mostrarModalEGrupo.value = false;
};

const columnsGrupos = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return "";
        }
    },
    {
        data: null,
        render: function (data, type, row, meta) {
            return `<input type="checkbox" class="grupo-checkbox" data-id="${row.idGrupo}" ">`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) { return meta.row + 1 }
    },
    { data: 'grupo' },
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
            return `<button class="editar-button" data-id="${row.idGrupo}"><i class="fa fa-pencil"></i></button>`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="eliminar-button" data-id="${row.idGrupo}"><i class="fa fa-trash"></i></button>`;
        }
    }
];

const botonesGrupo = [{
    title: 'Grupos registrados',
    extend: 'excelHtml5',
    text: '<i class="fa-solid fa-file-excel"></i> Excel',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded',
    exportOptions: {
        columns: [2, 3]
    },
},
{
    title: 'Grupos registrados',
    extend: 'pdfHtml5',
    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded',
    exportOptions: {
        columns: [2, 3]
    },    
    orientation: 'landscape',
},
{
    title: 'Grupos registrados',
    extend: 'print',
    text: '<i class="fa-solid fa-print"></i> Imprimir',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded',
    exportOptions: {
        columns: [2, 3]
    },
},
{
    title: 'Grupos registrados',
    extend: 'copy',
    text: '<i class="fa-solid fa-copy"></i> Copiar Texto',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded',
    exportOptions: {
        columns: [2, 3]
    },
},
];

const toggleGrupoSelection = (grupo) => {
    if (selectedGrupos.value.includes(grupo)) {
        // Si la materia ya está seleccionada, la eliminamos del array
        console.log("Se quito el grupo del la seleccion");
        selectedGrupos.value = selectedGrupos.value.filter((gr) => gr !== grupo);
    } else {
        // Si la materia no está seleccionada, la agregamos al array
        console.log("Se agrego un grupo a la selección");
        selectedGrupos.value.push(grupo);

    }
    const botonEliminar = document.getElementById("eliminarGruBtn");

    if (selectedGrupos.value.length > 0) {
        botonEliminar.removeAttribute("disabled");
        console.log("Se ha habilitado el botón");
    } else {
        botonEliminar.setAttribute("disabled", "");
        console.log("Se ha deshabilitado el botón");
    }
};

const eliminarGrupo = (idGrupo, grupo) => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: `¿Estas seguro que deseas eliminar los datos de ` + grupo + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('director.eliminarGrupos', idGrupo));
        }

    })
};

const eliminarGrupos = () => {
    console.log("Estoy en eliminar grupos");
    console.log(selectedGrupos);

    const swal = Swal.mixin({
        buttonsStyling: true
    })
    
    swal.fire({
        title: '¿Estas seguro que deseas eliminar los datos de los grupos seleccionados?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            console.log("Confirmado");
            try {
                const gruposS = selectedGrupos.value.map((grupo) => grupo.idGrupo);
                const $gruposIds = gruposS.join(',');
                await form.delete(route('director.elimGrupos', $gruposIds));

                // Limpia los periodos seleccionados después de la eliminación
                selectedGrupos.value = [];
            } catch (error) {
                console.log('El error se origina aquí');
                console.log(error);
            }
        }
    });
};

onMounted(() => {
    // Agrega un escuchador de eventos fuera de la lógica de Vue
    document.getElementById('gruposTablaId').addEventListener('click', (event) => {
        const checkbox = event.target;
        console.log(checkbox)
        if (checkbox.classList.contains('grupo-checkbox')) {
            const grupoId = parseInt(checkbox.getAttribute('data-id'));
            // Se asegura que props.materias.data esté definido antes de usar find
            console.log(grupoId);
            if (props.grupos) {
                const grupo = props.grupos.find(grupo => grupo.idGrupo === grupoId);
                if (grupo) {
                    toggleGrupoSelection(grupo);
                } else {
                    console.log("No se tiene grupo");
                }
            }
        }
    });

    // Manejar clic en el botón de editar
    $('#gruposTablaId').on('click', '.editar-button', function () {
        const grupoId = $(this).data('id');
        const grupo = props.grupos.find(g => g.idGrupo === grupoId);
        abrirGrupos(grupo);
    });

    // Manejar clic en el botón de eliminar
    $('#gruposTablaId').on('click', '.eliminar-button', function () {
        const grupoId = $(this).data('id');
        const grupo = props.grupos.find(g => g.idGrupo === grupoId);
        eliminarGrupo(grupoId, grupo.grupo);
    });
});

const optionsGrupo = {
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
    buttons: botonesGrupo,
};

</script>

<template>
    <div class="mt-8 bg-white p-4 shadow rounded-lg">
        <h2 class="text-black text-2xl text-center font-semibold p-5">Grupos</h2>
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
                @click="mostrarModalGrupo = true" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="fa fa-plus mr-2"></i>Agregar Grupo
            </button>
            <button id="eliminarGruBtn" disabled="true"
                class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded" @click="eliminarGrupos">
                <i class="fa fa-trash mr-2"></i>Borrar Grupo(s)
            </button>
            <!--</div>-->
        </div>
        <!--<div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>-->
        <!-- Línea con gradiente -->
        <div class="overflow-x-auto">
            <DataTable class="w-full table-auto text-sm display stripe compact cell-border order-column" id="gruposTablaId"
                :columns="columnsGrupos" :data="grupos" :options="optionsGrupo">
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
                            Grupo
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
    <formulario-grupo :show="mostrarModalGrupo" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalGrupo"
        :title="'Añadir grupo'" :op="'1'" :modal="'modalCreate'" :ciclos="props.ciclos"></formulario-grupo>
    <formulario-grupo :show="mostrarModalEGrupo" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalEGrupo"
        :title="'Editar grupo'" :op="'2'" :modal="'modalEdit'" :grupos="grupoE" :ciclos="props.ciclos"></formulario-grupo>
</template>