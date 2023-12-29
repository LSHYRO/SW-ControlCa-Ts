<script setup>
import { ref, computed, getCurrentInstance, onMounted } from 'vue';
import SearchBar from '@/Components/SearchBar.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import MenuOpciones from '@/Components/admin/MenuOpciones.vue';
import FormularioCiclos from '@/Components/admin/FormularioCiclos.vue';
import FormularioPeriodos from '@/Components/admin/FormularioPeriodos.vue';
import Swal from 'sweetalert2';
import { useForm} from '@inertiajs/vue3';
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

const columnsPeriodo = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return `<input type="checkbox" class="ciclo-checkbox" data-id="${row.idPeriodo}" ">`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) { return meta.row + 1 }
    },
    { data: 'periodo' },
    { data: 'fecha_inicio' },
    { data: 'fecha_fin' },
    {data: 'idCiclo',
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

const botonesPeriodo = [{
    title: 'Periodos registrados',
    extend: 'excelHtml5',
    text: '<i class="fa-solid fa-file-excel"></i> Excel',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Periodos registrados',
    extend: 'pdfHtml5',
    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Periodos registrados',
    extend: 'print',
    text: '<i class="fa-solid fa-print"></i> Imprimir',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Periodos registrados',
    extend: 'copy',
    text: '<i class="fa-solid fa-copy"></i> Copiar Texto',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
];

const mostrarModal = ref(false);
const mostrarModalE = ref(false);
const maxWidth = 'xl';
const closeable = true;

var cic = ({});
var per = ({});

const selectedCiclos = ref([]);
const selectedPeriodos = ref([]);

const cicloEdit = ref(null);
const periodoEdit = ref(null);

const toggleCicloSelection = (ciclo) => {
    if (selectedCiclos.value.includes(ciclo)) {
        // Si la materia ya está seleccionada, la eliminamos del array
        console.log("Se quito la materia del la seleccion");
        selectedCiclos.value = selectedCiclos.value.filter((c) => c !== ciclo);
    } else {
        // Si la materia no está seleccionada, la agregamos al array
        console.log("Se agrego una materia a la selección");
        selectedCiclos.value.push(ciclo);

    }
    const botonEliminar = document.getElementById("eliminarMBtn");

    if (selectedCiclos.value.length > 0) {
        botonEliminar.removeAttribute("disabled");
        console.log("Se ha habilitado el botón");
    } else {
        botonEliminar.setAttribute("disabled", "");
        console.log("Se ha deshabilitado el botón");
    }
};

const togglePeriodoSelection = (periodo) => {
    if (selectedPeriodos.value.includes(periodo)) {
        // Si la materia ya está seleccionada, la eliminamos del array
        console.log("Se quito la materia del la seleccion");
        selectedPeriodos.value = selectedPeriodos.value.filter((p) => p !== periodo);
    } else {
        // Si la materia no está seleccionada, la agregamos al array
        console.log("Se agrego una materia a la selección");
        selectedPeriodos.value.push(periodo);

    }
    const botonEliminar = document.getElementById("eliminarMBtn");

    if (selectedPeriodos.value.length > 0) {
        botonEliminar.removeAttribute("disabled");
        console.log("Se ha habilitado el botón");
    } else {
        botonEliminar.setAttribute("disabled", "");
        console.log("Se ha deshabilitado el botón");
    }
};

const form = useForm({});
const abrirCiclos = ($cicloss) => {
    cic = $cicloss;
    mostrarModalE.value = true;
    console.log($cicloss);
    console.log(cic);
}
const abrirPeriodos = ($periodoss) => {
    per = $periodoss;
    mostrarModalPeriodos.value = true;
    console.log($periodoss);
    console.log(per);
}
const cerrarModal = () => {
    mostrarModal.value = false;
};
const cerrarModalE = () => {
    mostrarModalE.value = false;
};

const mostrarModalPeriodos = ref(false);
const mostrarModalEPeriodos = ref(false);

const cerrarModalPeriodos = () => {
    mostrarModalPeriodos.value = false;
};
const cerrarModalEPeriodos = () => {
    mostrarModalEPeriodos.value = false;
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
            try {
                const periodosS = selectedPeriodos.value.map((periodo) => periodo.idPeriodo);
                const $periodosIds = periodosS.join(',');
                console.log(periodosS);
                await form.delete(route('admin.elimPeriodos', $periodosIds));

                // Limpia los periodos seleccionados después de la eliminación
                selectedCiclos.value = [];
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
//Esto es de ciclos y periodos
onMounted(() => {
    const ciclosTabla = document.getElementById('ciclosTablaId');
    const periodosTabla = document.getElementById('periodosTablaId');

    if (ciclosTabla) {
        ciclosTabla.addEventListener('click', (event) => {
            const checkbox = event.target;
            if (checkbox.classList.contains('ciclo-checkbox')) {
                const cicloId = parseInt(checkbox.getAttribute('data-id'));
                console.log(cicloId);
                if (props.ciclos) {
                    const ciclo = props.ciclos.find(ciclo => ciclo.idCiclo === cicloId);
                    console.log(ciclo);
                    if (ciclo) {
                        toggleCicloSelection(ciclo);
                    } else {
                        console.log("No se tiene ciclo");
                    }
                }
            }
        });

        // Manejar clic en el botón de editar Ciclo
        $('#ciclosTablaId').on('click', '.editar-button', function () {
            const cicloId = $(this).data('id');
            const ciclo = props.ciclos.find(c => c.idCiclo === cicloId);
            abrirCiclos(ciclo);
        });

        // Manejar clic en el botón de eliminar Ciclo
        $('#ciclosTablaId').on('click', '.eliminar-button', function () {
            const cicloId = $(this).data('id');
            const ciclo = props.ciclos.find(c => c.idCiclo === cicloId);
            eliminarCiclo(cicloId, ciclo);
        });
    }

    if (periodosTabla) {
        periodosTabla.addEventListener('click', (event) => {
            const checkbox = event.target;
            if (checkbox.classList.contains('periodo-checkbox')) {
                const periodoId = parseInt(checkbox.getAttribute('data-id'));
                console.log(periodoId);
                if (props.periodos) {
                    const periodo = props.periodos.find(periodo => periodo.idPeriodo === periodoId);
                    console.log(periodo);
                    if (periodo) {
                        togglePeriodoSelection(periodo);
                    } else {
                        console.log("No se tiene periodo");
                    }
                }
            }
        });

        // Manejar clic en el botón de editar Periodo
        $('#periodosTablaId').on('click', '.editar-button', function () {
            const periodoId = $(this).data('id');
            const periodo = props.periodos.find(p => p.idPeriodo === periodoId);
            abrirPeriodos(periodo);
        });

        // Manejar clic en el botón de eliminar Periodo
        $('#periodosTablaId').on('click', '.eliminar-button', function () {
            const periodoId = $(this).data('id');
            const periodo = props.periodos.find(p => p.idPeriodo === periodoId);
            eliminarPeriodo(periodoId, periodo);
        });
    }
});


const selectedOption = ref('Ciclos'); // Inicialmente, muestra la tabla de "Ciclos"

// Declaración de optionsCiclo y optionsPeriodo
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

// Antes de abrir el formulario, reinicia el objeto form
const openFormCiclos = () => {
    form.value = { idCiclo: null, fecha_inicio: null, fecha_fin: null, descripcionCiclo: null };
    mostrarModal.value = true;

    // Desvincula eventos y destruye DataTable antes de cambiar de vista
    $('#periodosTablaId').off();
    $('#periodosTablaId').DataTable().destroy();

    // Reinicializa el DataTable después de cambiar de vista
    $('#ciclosTablaId').DataTable(optionsCiclo); // Reemplaza 'options' con tus opciones
};

// Antes de abrir el formulario, reinicia el objeto form
const openFormPeriodos = () => {
    form.value = { idCiclo: null, fecha_inicio: null, fecha_fin: null, idCiclo: null };
    mostrarModalPeriodos.value = true;

    // Desvincula eventos y destruye DataTable antes de cambiar de vista
    $('#ciclosTablaId').off();
    $('#ciclosTablaId').DataTable().destroy();

    // Reinicializa el DataTable después de cambiar de vista
    $('#periodosTablaId').DataTable(optionsPeriodo); // Reemplaza 'options' con tus opciones
};

</script>

<template>
    <AdminLayout title="Ciclos y Periodos">

        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <!-- Botón o selector para elegir entre "Grado" y "Grupo" -->
            <div class="p-4 flex items-center justify-center">
                <button @click="selectedOption = 'Ciclos'"
                    class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded mr-2">
                    Ciclos
                </button>
                <button @click="selectedOption = 'Periodos'"
                    class="bg-cyan-500 hover-bg-cyan-600 text-white font-semibold py-2 px-4 rounded mr-2">
                    Periodos
                </button>
            </div>

            <!-- Sección para mostrar la tabla de "Ciclos" -->
            <div v-if="selectedOption === 'Ciclos'">
                <!-- Agrega la tabla de datos específica para "Ciclo" aquí -->
                <div class="mt-8 bg-white p-4 shadow rounded-lg">
                    <h2 class="text-black text-2xl text-center font-semibold p-5">Ciclos</h2>
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
                            <i class="fa fa-plus mr-2"></i>Agregar Ciclo
                        </button>
                        <button id="eliminarMBtn" disabled="true"
                            class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                            @click="eliminarCiclos">
                            <i class="fa fa-trash mr-2"></i>Borrar Ciclo(s)
                        </button>
                        <!--</div>-->
                    </div>
                    <!--<div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>-->
                    <!-- Línea con gradiente -->
                    <div class="overflow-x-auto">
                        <DataTable class="w-full table-auto text-sm display stripe compact cell-border order-column"
                            id="ciclosTablaId" :columns="columnsCiclo" :data="ciclos" :options="optionsCiclo">
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
                    :title="'Editar ciclo'" :op="'2'" :modal="'modalEdit'" :ciclo="cicloEdit"></formulario-ciclos>

            </div> <!-- Aquí termina "Ciclos" -->

            <!-- Sección para mostrar la tabla de "Periodos" -->
            <div v-if="selectedOption === 'Periodos'">
                <!-- Agrega la tabla de datos específica para "Grupo" aquí -->
                <div class="mt-8 bg-white p-4 shadow rounded-lg">
                    <h2 class="text-black text-2xl text-center font-semibold p-5">Periodos</h2>
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
                            @click="mostrarModalPeriodos = true" data-bs-toggle="modal" data-bs-target="#modalCreate">
                            <i class="fa fa-plus mr-2"></i>Agregar Periodo
                        </button>
                        <button id="eliminarMBtn" disabled="true"
                            class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                            @click="eliminarPeriodos">
                            <i class="fa fa-trash mr-2"></i>Borrar Periodo(s)
                        </button>
                        <!--</div>-->
                    </div>
                    <!--<div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>-->
                    <!-- Línea con gradiente -->
                    <div class="overflow-x-auto">
                        <DataTable class="w-full table-auto text-sm display stripe compact cell-border order-column"
                            id="periodosTablaId" :columns="columnsPeriodo" :data="periodos" :options="optionsPeriodo">
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
                    @close="cerrarModalPeriodos" :title="'Añadir periodo'" :op="'1'"
                    :modal="'modalCreate'" :ciclos="props.ciclos"></formulario-periodos>
                <formulario-periodos :show="mostrarModalEPeriodos" :max-width="maxWidth" :closeable="closeable"
                    @close="cerrarModalEPeriodos" :title="'Editar periodo'" :op="'2'" :modal="'modalEdit'"
                    :periodo="periodoEdit" :ciclos="props.ciclos"></formulario-periodos>
            </div> <!-- Aquí termina "Grupo" -->
        </div>

    </AdminLayout>
</template>