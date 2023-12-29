<script setup>
import { ref, computed, getCurrentInstance, onMounted } from 'vue';
import SearchBar from '@/Components/SearchBar.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FormularioGrado from '@/Components/admin/FormularioGrado.vue';
import FormularioGrupo from '@/Components/admin/FormularioGrupo.vue';
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
    grados: { type: Object },
    grupos: { type: Object },
    ciclos: { type: Object },

});

const columnsGrados = [
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
    {data: 'idCiclo',
        render: function (data, type, row, meta) {
            // Modificación para mostrar la descripción del ciclo
            const ciclo = props.ciclos.find(ciclo => ciclo.idCiclo === data);
            return ciclo ? ciclo.descripcionCiclo : '';
        }
    },
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

const columnsGrupos = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return `<input type="checkbox" class="ciclo-checkbox" data-id="${row.idGrupo}" ">`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) { return meta.row + 1 }
    },
    { data: 'grupo' },
    {data: 'idCiclo',
        render: function (data, type, row, meta) {
            // Modificación para mostrar la descripción del ciclo
            const ciclo = props.ciclos.find(ciclo => ciclo.idCiclo === data);
            return ciclo ? ciclo.descripcionCiclo : '';
        }
    },
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

const botonesGrado = [{
    title: 'Grados registrados',
    extend: 'excelHtml5',
    text: '<i class="fa-solid fa-file-excel"></i> Excel',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Grados registrados',
    extend: 'pdfHtml5',
    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Grados registrados',
    extend: 'print',
    text: '<i class="fa-solid fa-print"></i> Imprimir',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Grados registrados',
    extend: 'copy',
    text: '<i class="fa-solid fa-copy"></i> Copiar Texto',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
];

const botonesGrupo = [{
    title: 'Grupos registrados',
    extend: 'excelHtml5',
    text: '<i class="fa-solid fa-file-excel"></i> Excel',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Grupos registrados',
    extend: 'pdfHtml5',
    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Grupos registrados',
    extend: 'print',
    text: '<i class="fa-solid fa-print"></i> Imprimir',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Grupos registrados',
    extend: 'copy',
    text: '<i class="fa-solid fa-copy"></i> Copiar Texto',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
];

const mostrarModal = ref(false);
const mostrarModalE = ref(false);
const maxWidth = 'xl';
const closeable = true;

var grad = ({});
var grup = ({});

const selectedGrados = ref([]);
const selectedGrupos = ref([]);

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
    const botonEliminar = document.getElementById("eliminarMBtn");

    if (selectedGrados.value.length > 0) {
        botonEliminar.removeAttribute("disabled");
        console.log("Se ha habilitado el botón");
    } else {
        botonEliminar.setAttribute("disabled", "");
        console.log("Se ha deshabilitado el botón");
    }
};

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
    const botonEliminar = document.getElementById("eliminarMBtn");

    if (selectedGrupos.value.length > 0) {
        botonEliminar.removeAttribute("disabled");
        console.log("Se ha habilitado el botón");
    } else {
        botonEliminar.setAttribute("disabled", "");
        console.log("Se ha deshabilitado el botón");
    }
};

const form = useForm({});

const gradoEdit = ref(null);
const grupoEdit = ref(null);

const abrirGrados = ($gradoss) => {
    grad = $gradoss;
    mostrarModalE.value = true;
    console.log($gradoss);
    console.log(grad);
}
const abrirGrupos = ($gruposs) => {
    grup = $gruposs;
    mostrarModalEGrupo.value = true;
    console.log($gruposs);
    console.log(grup);
}
const cerrarModal = () => {
    mostrarModal.value = false;
};
const cerrarModalE = () => {
    mostrarModalE.value = false;
};

const mostrarModalGrupo = ref(false);
const mostrarModalEGrupo = ref(false);

const cerrarModalGrupo = () => {
    mostrarModalGrupo.value = false;
};
const cerrarModalEGrupo = () => {
    mostrarModalEGrupo.value = false;
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
            form.delete(route('admin.eliminarGrados', idGrado));
        }

    })
};

const eliminarGrados = () => {
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
            try {
                const gradosS = selectedGrados.value.map((grado) => grado.idGrado);
                const $gradosIds = gradosS.join(',');
                console.log(gradosS);
                await form.delete(route('admin.elimGrados', $gradosIds));

                // Limpia las materias seleccionadas después de la eliminación
                selectedGrados.value = [];
            } catch (error) {
                console.log('El error se origina aquí');
                console.log(error);
            }
        }
    });
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
            form.delete(route('admin.eliminarGrupos', idGrupo));
        }

    })
};

const eliminarGrupos = () => {
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
            try {
                const gruposS = selectedGrupos.value.map((grupo) => grupo.idGrupo);
                const $gruposIds = gruposS.join(',');
                console.log(gruposS);
                await form.delete(route('admin.elimGrupos', $gruposIds));

                // Limpia los periodos seleccionados después de la eliminación
                selectedGrupos.value = [];
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

//Esto es de grados y grupos
onMounted(() => {
    const gradosTabla = document.getElementById('gradosTablaId');
    const gruposTabla = document.getElementById('gruposTablaId');

    if (gradosTabla) {
        gradosTabla.addEventListener('click', (event) => {
            const checkbox = event.target;
            if (checkbox.classList.contains('grado-checkbox')) {
                const gradoId = parseInt(checkbox.getAttribute('data-id'));
                console.log(gradoId);
                if (props.grados) {
                    const grado = props.grados.find(grado => grado.idGrado === gradoId);
                    console.log(grado);
                    if (grado) {
                        toggleGradoSelection(grado);
                    } else {
                        console.log("No se tiene grado");
                    }
                }
            }
        });

        // Manejar clic en el botón de editar Grado
        $('#gradosTablaId').on('click', '.editar-button', function () {
            const gradoId = $(this).data('id');
            const grado = props.grados.find(gr => gr.idGrado === gradoId);
            abrirGrados(grado);
        });

        // Manejar clic en el botón de eliminar Grado
        $('#gradosTablaId').on('click', '.eliminar-button', function () {
            const gradoId = $(this).data('id');
            const grado = props.grados.find(gr => gr.idGrado === gradoId);
            eliminarGrado(gradoId, grado);
        });
    }

    if (gruposTabla) {
        gruposTabla.addEventListener('click', (event) => {
            const checkbox = event.target;
            if (checkbox.classList.contains('grupo-checkbox')) {
                const grupoId = parseInt(checkbox.getAttribute('data-id'));
                console.log(grupoId);
                if (props.grupos) {
                    const grupo = props.grupos.find(grupo => grupo.idGrupo === grupoId);
                    console.log(grupo);
                    if (grupo) {
                        toggleGrupoSelection(grupo);
                    } else {
                        console.log("No se tiene grupo");
                    }
                }
            }
        });

        // Manejar clic en el botón de editar Periodo
        $('#gruposTablaId').on('click', '.editar-button', function () {
            const grupoId = $(this).data('id');
            const grupo = props.grupos.find(g => g.idGrupo === grupoId);
            abrirGrupos(grupo);
        });

        // Manejar clic en el botón de eliminar Periodo
        $('#gruposTablaId').on('click', '.eliminar-button', function () {
            const grupoId = $(this).data('id');
            const grupo = props.grupos.find(g => g.idGrupo === grupoId);
            eliminarGrupo(grupoId, grupo);
        });
    }
});

const selectedOption = ref('Grados'); // Inicialmente, muestra la tabla de "Grados"

// Declaración de optionsCiclo y optionsPeriodo
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

// Antes de abrir el formulario, reinicia el objeto form
const openFormGrados = () => {
    form.value = { idGrado: null, grado: null, idCiclo: null };
    mostrarModal.value = true;

    // Desvincula eventos y destruye DataTable antes de cambiar de vista
    $('#gradosTablaId').off();
    $('#gradosTablaId').DataTable().destroy();

    // Reinicializa el DataTable después de cambiar de vista
    $('#gradosTablaId').DataTable(optionsGrado); // Reemplaza 'options' con tus opciones
};

// Antes de abrir el formulario, reinicia el objeto form
const openFormGrupos = () => {
    form.value = { idGrupo: null, grupo: null, idCiclo: null };
    mostrarModalGrupo.value = true;

    // Desvincula eventos y destruye DataTable antes de cambiar de vista
    $('#gruposTablaId').off();
    $('#gruposTablaId').DataTable().destroy();

    // Reinicializa el DataTable después de cambiar de vista
    $('#gruposTablaId').DataTable(optionsGrupo); // Reemplaza 'options' con tus opciones
};

</script>

<template>
    <AdminLayout title="Grados y Grupos">

        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <!-- Botón o selector para elegir entre "Grado" y "Grupo" -->
            <div class="p-4 flex items-center justify-center">
                <button @click="selectedOption = 'Grados'"
                    class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded mr-2">
                    Grados
                </button>
                <button @click="selectedOption = 'Grupos'"
                    class="bg-cyan-500 hover-bg-cyan-600 text-white font-semibold py-2 px-4 rounded mr-2">
                    Grupos
                </button>
            </div>

            <!-- Sección para mostrar la tabla de "Grados" -->
            <div v-if="selectedOption === 'Grados'">
                <!-- Agrega la tabla de datos específica para "Grado" aquí -->
                <div class="mt-8 bg-white p-4 shadow rounded-lg">
                    <h2 class="text-black text-2xl text-center font-semibold p-5">Grados</h2>
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
                            <i class="fa fa-plus mr-2"></i>Agregar Grado
                        </button>
                        <button id="eliminarMBtn" disabled="true"
                            class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                            @click="eliminarGrados">
                            <i class="fa fa-trash mr-2"></i>Borrar Grado(s)
                        </button>
                        <!--</div>-->
                    </div>
                    <!--<div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>-->
                    <!-- Línea con gradiente -->
                    <div class="overflow-x-auto">
                        <DataTable class="w-full table-auto text-sm display stripe compact cell-border order-column"
                            id="gradosTablaId" :columns="columnsGrados" :data="grados" :options="optionsGrado">
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

                <formulario-grado :show="mostrarModal" :max-width="maxWidth" :closeable="closeable" @close="cerrarModal"
                    :title="'Añadir grado'" :op="'1'" :modal="'modalCreate'" :ciclos="props.ciclos"></formulario-grado>
                <formulario-grado :show="mostrarModalE" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalE"
                    :title="'Editar grado'" :op="'2'" :modal="'modalEdit'" :grado="gradoEdit"></formulario-grado>

            </div> <!-- Aquí termina "Grados" -->

            <!-- Sección para mostrar la tabla de "Grupos" -->
            <div v-if="selectedOption === 'Grupos'">
                <!-- Agrega la tabla de datos específica para "Grado" aquí -->
                <div class="mt-8 bg-white p-4 shadow rounded-lg">
                    <h2 class="text-black text-2xl text-center font-semibold p-5">Grupos</h2>
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
                            @click="mostrarModalGrupo = true" data-bs-toggle="modal" data-bs-target="#modalCreate">
                            <i class="fa fa-plus mr-2"></i>Agregar Grupo
                        </button>
                        <button id="eliminarMBtn" disabled="true"
                            class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                            @click="eliminarGrupos">
                            <i class="fa fa-trash mr-2"></i>Borrar Grupo(s)
                        </button>
                        <!--</div>-->
                    </div>
                    <!--<div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>-->
                    <!-- Línea con gradiente -->
                    <div class="overflow-x-auto">
                        <DataTable class="w-full table-auto text-sm display stripe compact cell-border order-column"
                            id="gruposTablaId" :columns="columnsGrupos" :data="grupos" :options="optionsGrupo">
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
                                        Grupo
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

                <formulario-grupo :show="mostrarModalGrupo" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalGrupo"
                    :title="'Añadir grupo'" :op="'1'" :modal="'modalCreate'" :ciclos="props.ciclos"></formulario-grupo>
                <formulario-grupo :show="mostrarModalEGrupo" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalEGrupo"
                    :title="'Editar grupo'" :op="'2'" :modal="'modalEdit'" :grupo="grupoEdit"></formulario-grupo>

            </div> <!-- Aquí termina "Grupos" -->

        </div>

    </AdminLayout>
</template>