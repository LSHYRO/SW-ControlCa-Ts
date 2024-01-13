<script setup>
import { ref, computed, getCurrentInstance, onMounted } from 'vue';
import SearchBar from '@/Components/SearchBar.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FormularioClases from '@/Components/admin/FormularioClases.vue';
import MenuOpciones from '@/Components/admin/MenuOpciones.vue';
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
    clases: { type: Object },
    grados: { type: Object },
    grupos: { type: Object },
    personal: { type: Object },
    materias: { type: Object },
    ciclos: { type: Object },
});

const columns = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return `<input type="checkbox" class="clase-checkbox" data-id="${row.idClase}" ">`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) { return meta.row + 1 }
    },
    {
        data: 'idGrado',
        render: function (data, type, row, meta) {
            // Modificación para mostrar la descripción del ciclo
            const grado = props.grados.find(grado => grado.idGrado === data);
            return grado ? grado.grado : '';
        }
    },
    {
        data: 'idGrupo',
        render: function (data, type, row, meta) {
            // Modificación para mostrar la descripción del ciclo
            const grupo = props.grupos.find(grupo => grupo.idGrupo === data);
            return grupo ? grupo.grupo : '';
        }
    },
    {
        data: 'idPersonal',
        render: function (data, type, row, meta) {
            // Modificación para mostrar la descripción del ciclo
            const docente = props.personal.find(docente => docente.idPersonal === data);
            return docente ? docente.nombre_completo : '';
        }
    },
    {
        data: 'idMateria',
        render: function (data, type, row, meta) {
            // Modificación para mostrar la descripción del ciclo
            const materia = props.materias.find(materia => materia.idMateria === data);
            return materia ? materia.materia : '';
        }
    },
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
            return `<button class="editar-button" data-id="${row.idClase}"><i class="fa fa-pencil"></i></button>`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="eliminar-button" data-id="${row.idClase}"><i class="fa fa-trash"></i></button>`;
        }

    }
    /*
    <button @click="abrirE(mmateria)" data-bs-toggle="modal" data-bs-target="#modalEdit">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button @click="eliminarMateria(mmateria.idMateria, mmateria.materia)">
                                    <i class="fa fa-trash"></i>
                                </button>
    */
];

const botones = [{
    title: 'Clases registradas',
    extend: 'excelHtml5',
    text: '<i class="fa-solid fa-file-excel"></i> Excel',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Clases registradas',
    extend: 'pdfHtml5',
    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Clases registradas',
    extend: 'print',
    text: '<i class="fa-solid fa-print"></i> Imprimir',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Clases registradas',
    extend: 'copy',
    text: '<i class="fa-solid fa-copy"></i> Copiar Texto',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
];

const mostrarModal = ref(false);
const mostrarModalE = ref(false);
const maxWidth = 'xl';
const closeable = true;

var claseE = ({});

const selectedClases = ref([]);

const toggleClaseSelection = (clase) => {
    if (selectedClases.value.includes(clase)) {
        // Si la materia ya está seleccionada, la eliminamos del array
        console.log("Se quito la materia del la seleccion");
        selectedClases.value = selectedClases.value.filter((c) => c !== clase);
    } else {
        // Si la materia no está seleccionada, la agregamos al array
        console.log("Se agrego una materia a la selección");
        selectedClases.value.push(clase);

    }
    const botonEliminar = document.getElementById("eliminarMBtn");

    if (selectedClases.value.length > 0) {
        botonEliminar.removeAttribute("disabled");
        console.log("Se ha habilitado el botón");
    } else {
        botonEliminar.setAttribute("disabled", "");
        console.log("Se ha deshabilitado el botón");
    }
};

const form = useForm({});

const abrirE = ($clasee) => {
    claseE = $clasee;
    mostrarModalE.value = true;
    console.log($clasee);
    console.log(claseE);
}

const cerrarModal = () => {
    mostrarModal.value = false;
};

const cerrarModalE = () => {
    mostrarModalE.value = false;
};

const eliminarClase = (idClase, clase) => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: `¿Estas seguro que deseas eliminar los datos de ` + clase + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('admin.eliminarClases', idClase));
        }

    })
};

const eliminarClases = () => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })

    swal.fire({
        title: '¿Estas seguro que deseas eliminar los datos de las clases seleccionadas?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const clasesS = selectedClases.value.map((clase) => clase.idClase);
                const $clasesIds = clasesS.join(',');
                console.log(clasesS);
                await form.delete(route('admin.elimClases', $clasesIds));

                // Limpia las materias seleccionadas después de la eliminación
                selectedClases.value = [];
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
    document.getElementById('clasesTablaId').addEventListener('click', (event) => {
        const checkbox = event.target;
        if (checkbox.classList.contains('clase-checkbox')) {
            const claseId = parseInt(checkbox.getAttribute('data-id'));
            console.log(claseId);
            // Asegúrate de que props.materias.data esté definido antes de usar find
            console.log(props.clases);
            if (props.clases) {
                const clase = props.clases.find(clase => clase.idClase === claseId);
                console.log(clase);
                if (clase) {
                    toggleClaseSelection(clase);
                } else {
                    console.log("No se tiene clase");
                }
            }
        }
    });

    // Manejar clic en el botón de editar
    $('#clasesTablaId').on('click', '.editar-button', function () {
        console.log("Entró en editar");
        const claseId = $(this).data('id');
        const clase = props.clases.find(c => c.idClase === claseId);
        abrirE(clase);
    });

    // Manejar clic en el botón de eliminar
    $('#clasesTablaId').on('click', '.eliminar-button', function () {
        const claseId = $(this).data('id');
        const clase = props.clases.find(c => c.idClase === claseId);
        eliminarClase(claseId, clase.idClase);
    });
});

</script>

<template>
    <AdminLayout title="clases">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <h2 class="text-black text-2xl text-center font-semibold p-5">Clases</h2>
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
                    <i class="fa fa-plus mr-2"></i>Agregar Clase
                </button>
                <button id="eliminarMBtn" disabled="true"
                    class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                    @click="eliminarClases">
                    <i class="fa fa-trash mr-2"></i>Borrar Clase(s)
                </button>
                <!--</div>-->
            </div>
            <div class="overflow-x-auto ">
                <DataTable class="w-full table-auto text-sm display stripe compact cell-border order-column"
                    id="clasesTablaId" :columns="columns" :data="clases" :options="{
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
                                Grado
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Grupo
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Docente
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Materia
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

        <formulario-clases :show="mostrarModal" :max-width="maxWidth" :closeable="closeable" @close="cerrarModal"
            :title="'Añadir clase'" :op="'1'" :modal="'modalCreate'" :grados="props.grados" :grupos="props.grupos"
            :personal="props.personal" :materias="props.materias" :ciclos="props.ciclos"></formulario-clases>
        <formulario-clases :show="mostrarModalE" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalE"
            :title="'Editar clase'" :op="'2'" :modal="'modalEdit'" :clase="claseE" :grados="props.grados" :grupos="props.grupos"
            :personal="props.personal" :materias="props.materias" :ciclos="props.ciclos"></formulario-clases>

    </AdminLayout>
</template>