<script setup>
import { ref, computed, getCurrentInstance, onMounted } from 'vue';
import SearchBar from '@/Components/SearchBar.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FormularioMateria from '@/Components/admin/FormularioMateria.vue';
import MenuOpciones from '@/Components/admin/MenuOpciones.vue';
import Swal from 'sweetalert2';
import { useForm, usePage, Link } from '@inertiajs/vue3';
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
    materias: { type: Object },

});
console.log(props.materias);
const columns = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return `<input type="checkbox" class="materia-checkbox" data-id="${row.idMateria}" ">`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) { return meta.row + 1 }
    },
    { data: 'materia' },
    { data: 'descripcion' },
    { data: 'esTaller', render: function(data, type, row) {
        return data ? 'Si' : 'No';
    }},
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="editar-button" data-id="${row.idMateria}"><i class="fa fa-pencil"></i></button>`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="eliminar-button" data-id="${row.idMateria}"><i class="fa fa-trash"></i></button>`;
        }

    }
    
];

const botones = [{
    title: 'Materias registradas',
    extend: 'excelHtml5',
    text: '<i class="fa-solid fa-file-excel"></i> Excel',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Materias registradas',
    extend: 'pdfHtml5',
    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Materias registradas',
    extend: 'print',
    text: '<i class="fa-solid fa-print"></i> Imprimir',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Materias registradas',
    extend: 'copy',
    text: '<i class="fa-solid fa-copy"></i> Copiar Texto',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},

];
const mostrarModal = ref(false);
const mostrarModalE = ref(false);
const maxWidth = 'xl';
const closeable = true;

var mater = ({});

const selectedMaterias = ref([]);

const toggleMateriaSelection = (materia) => {
    if (selectedMaterias.value.includes(materia)) {
        // Si la materia ya está seleccionada, la eliminamos del array
        console.log("Se quito la materia del la seleccion");
        selectedMaterias.value = selectedMaterias.value.filter((m) => m !== materia);
    } else {
        // Si la materia no está seleccionada, la agregamos al array
        console.log("Se agrego una materia a la selección");
        selectedMaterias.value.push(materia);

    }
    const botonEliminar = document.getElementById("eliminarMBtn");

    if (selectedMaterias.value.length > 0) {
        botonEliminar.removeAttribute("disabled");
        console.log("Se ha habilitado el botón");
    } else {
        botonEliminar.setAttribute("disabled", "");
        console.log("Se ha deshabilitado el botón");
    }
};

const form = useForm({});

const abrirE = ($materiaa) => {
    mater = $materiaa;
    mostrarModalE.value = true;
    console.log($materiaa);
    console.log(mater);
}
const cerrarModal = () => {
    mostrarModal.value = false;
};
const cerrarModalE = () => {
    mostrarModalE.value = false;
};

const eliminarMateria = (idMateria, materia) => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: `¿Estas seguro que deseas eliminar los datos de ` + materia + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('admin.eliminarMaterias', idMateria));
        }

    })
};

const eliminarMaterias = () => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })

    swal.fire({
        title: '¿Estas seguro que deseas eliminar los datos de las materias seleccionadas?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const materiasS = selectedMaterias.value.map((materia) => materia.idMateria);
                const $materiasIds = materiasS.join(',');
                console.log(materiasS);
                await form.delete(route('admin.elimMaterias', $materiasIds));

                // Limpia las materias seleccionadas después de la eliminación
                selectedMaterias.value = [];
            } catch (error) {
                console.log('El error se origina aquí');
                console.log(error);
            }
        }
    });
};


const searchQuery = ref("");

const handleSearch = (term) => {
    searchQuery.value = term;
};

onMounted(() => {
    // Agrega un escuchador de eventos fuera de la lógica de Vue
    document.getElementById('materiasTablaId').addEventListener('click', (event) => {
        const checkbox = event.target;
        if (checkbox.classList.contains('materia-checkbox')) {
            const materiaId = parseInt(checkbox.getAttribute('data-id'));
            console.log(materiaId);
            // Asegúrate de que props.materias.data esté definido antes de usar find
            console.log(props.materias);
            if (props.materias) {
                const materia = props.materias.find(materia => materia.idMateria === materiaId);
                console.log(materia);
                if (materia) {
                    toggleMateriaSelection(materia);
                } else {
                    console.log("No se tiene materia");
                }
            }
        }
    });

    // Manejar clic en el botón de editar
    $('#materiasTablaId').on('click', '.editar-button', function () {
        const materiaId = $(this).data('id');
        const materia = props.materias.find(m => m.idMateria === materiaId);
        abrirE(materia);
    });

    // Manejar clic en el botón de eliminar
    $('#materiasTablaId').on('click', '.eliminar-button', function () {
        const materiaId = $(this).data('id');
        const materia = props.materias.find(m => m.idMateria === materiaId);
        eliminarMateria(materiaId, materia.materia);
    });
});



</script>

<template>
    <AdminLayout title="materias">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <h2 class="text-black text-2xl text-center font-semibold p-5">Materias</h2>
            <div class="my-1"></div> <!-- Espacio de separación -->
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
            <!-- flash message start -->
            <div v-if="$page.props.flash.message"
                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert">
                <span class="font-medium">
                    {{ $page.props.flash.message }}
                </span>
            </div>
            <div class="py-3 flex flex-col md:flex-row md:items-start md:space-x-3 space-y-3 md:space-y-0">
                <!--<div class="w-full md:w-2/3 space-y-4 md:space-y-0 md:space-x-4 md:flex md:items-center md:justify-start">-->
                <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                    @click="mostrarModal = true" data-bs-toggle="modal" data-bs-target="#modalCreate">
                    <i class="fa fa-plus mr-2"></i>Agregar Materia
                </button>
                <button id="eliminarMBtn" disabled="true"
                    class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                    @click="eliminarMaterias">
                    <i class="fa fa-trash mr-2"></i>Borrar Materia(s)
                </button>
                <!--</div>-->
            </div>
            <div class="overflow-x-auto ">
                <DataTable class="w-full table-auto text-sm display stripe compact cell-border order-column"
                    id="materiasTablaId" :columns="columns" :data="materias" :options="{
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
                                Materia
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Descripción
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Taller
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

        <formulario-materia :show="mostrarModal" :max-width="maxWidth" :closeable="closeable" @close="cerrarModal"
            :title="'Añadir materia'" :op="'1'" :modal="'modalCreate'"></formulario-materia>
        <formulario-materia :show="mostrarModalE" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalE"
            :title="'Editar materia'" :op="'2'" :modal="'modalEdit'" :materias="mater"></formulario-materia>

    </AdminLayout>
</template>
<style>
.paginacion-sm {
    border: 1px
}
</style>