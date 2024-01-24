<script setup>
import { ref, computed, getCurrentInstance, onMounted } from 'vue';
import SearchBar from '@/Components/SearchBar.vue';
import DirectorLayout from '@/Layouts/DirectorLayout.vue';
import FormularioAlumnosClase from '@/Components/director/FormularioAlumnosClase.vue';
import MenuOpcionesDirec from '@/Components/director/MenuOpcionesDirec.vue';
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
    alumnos: { type: Object },
    personal: { type: Object },
    materias: { type: Object },
    usuario: { type: Object },
    clases_alumnos: { type: Object }
});

const getMateria = (idMateria) => {
    const materia = props.materias.find(m => m.idMateria === idMateria);
    return materia ? materia.materia : 'N/A';
};

const getGrado = (idGrado) => {
    const grado = props.grados.find(g => g.idGrado === idGrado);
    return grado ? grado.grado : 'N/A';
};

const getGrupo = (idGrupo) => {
    const grupo = props.grupos.find(g => g.idGrupo === idGrupo);
    return grupo ? grupo.grupo : 'N/A';
};

const columns = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return `<input type="checkbox" class="alumnosclase-checkbox" data-id="${row.idClaseAlumno}" ">`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) { return meta.row + 1 }
    },
    {
        data: 'idClase',
        render: function (data, type, row, meta) {
            const clase = props.clases.find(clase => clase.idClase === data);
            const materia = clase ? getMateria(clase.idMateria) : '';
            const grado = clase ? getGrado(clase.idGrado) : '';
            const grupo = clase ? getGrupo(clase.idGrupo) : '';

            return `${materia} - Grado: ${grado} - Grupo: ${grupo}`;
        }
    },
    {
        data: 'idAlumno',
        render: function (data, type, row, meta) {
            // Modificación para mostrar la descripción del ciclo
            const alumno = props.alumnos.find(alumno => alumno.idAlumno === data);
            return alumno ? alumno.nombre_completo : '';
        }
    },
    {
        data: 'calificacionClase',
    },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="editar-button" data-id="${row.idClaseAlumno}"><i class="fa fa-pencil"></i></button>`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="eliminar-button" data-id="${row.idClaseAlumno}"><i class="fa fa-trash"></i></button>`;
        }

    }
];

const botones = [{
    title: 'Alumnos registrados',
    extend: 'excelHtml5',
    text: '<i class="fa-solid fa-file-excel"></i> Excel',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Alumnos registrados',
    extend: 'pdfHtml5',
    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Alumnos registrados',
    extend: 'print',
    text: '<i class="fa-solid fa-print"></i> Imprimir',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Alumnos registrados',
    extend: 'copy',
    text: '<i class="fa-solid fa-copy"></i> Copiar Texto',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
];

const mostrarModal = ref(false);
const mostrarModalE = ref(false);
const maxWidth = 'xl';
const closeable = true;

var clase_alumnoE = ({});

const selectedAlumnosClase = ref([]);

const toggleAlumnosClaseSelection = (clase_alumno) => {
    if (selectedAlumnosClase.value.includes(clase_alumno)) {
        // Si la materia ya está seleccionada, la eliminamos del array
        console.log("Se quito la materia del la seleccion");
        selectedAlumnosClase.value = selectedAlumnosClase.value.filter((c) => c !== clase_alumno);
    } else {
        // Si la materia no está seleccionada, la agregamos al array
        console.log("Se agrego una materia a la selección");
        selectedAlumnosClase.value.push(clase_alumno);

    }
    const botonEliminar = document.getElementById("eliminarMBtn");

    if (selectedAlumnosClase.value.length > 0) {
        botonEliminar.removeAttribute("disabled");
        console.log("Se ha habilitado el botón");
    } else {
        botonEliminar.setAttribute("disabled", "");
        console.log("Se ha deshabilitado el botón");
    }
};

const form = useForm({});

const abrirE = ($clasee) => {
    clase_alumnoE = $clasee;
    mostrarModalE.value = true;
    console.log($clasee);
    console.log(clase_alumnoE);
}

const cerrarModal = () => {
    mostrarModal.value = false;
};

const cerrarModalE = () => {
    mostrarModalE.value = false;
};

const eliminarAlumnosClase = (idClaseAlumno, clase_alumno) => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: `¿Estas seguro que deseas eliminar los datos de ` + clase_alumno + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('director.eliminarAlumnosClases', idClaseAlumno));
        }

    })
};

const eliminarAlumnosClases = () => {
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
                const clasesS_alumnos = selectedAlumnosClase.value.map((clase_alumno) => clase_alumno.idClaseAlumno);
                const $clases_alumnosIds = clasesS_alumnos.join(',');
                console.log(clasesS_alumnos);
                await form.delete(route('director.elimAlumnosClases', $clases_alumnosIds));

                // Limpia las materias seleccionadas después de la eliminación
                selectedAlumnosClase.value = [];
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
    document.getElementById('alumnosClasesTablaId').addEventListener('click', (event) => {
        const checkbox = event.target;
        if (checkbox.classList.contains('alumnosclase-checkbox')) {
            const claseAlumnoId = parseInt(checkbox.getAttribute('data-id'));
            console.log(claseAlumnoId);
            // Asegúrate de que props.materias.data esté definido antes de usar find
            console.log(props.clases_alumnos);
            if (props.clases_alumnos) {
                const clase_alumno = props.clases_alumnos.find(clase_alumno => clase_alumno.idClaseAlumno === claseAlumnoId);
                console.log(clase_alumno);
                if (clase_alumno) {
                    toggleAlumnosClaseSelection(clase_alumno);
                } else {
                    console.log("No se tiene clase");
                }
            }
        }
    });

    // Manejar clic en el botón de editar
    $('#alumnosClasesTablaId').on('click', '.editar-button', function () {
        console.log("Entró en editar");
        const claseAlumnoId = $(this).data('id');
        const clase_alumno = props.clases_alumnos.find(c => c.idClaseAlumno === claseAlumnoId);
        abrirE(clase_alumno);
    });

    // Manejar clic en el botón de eliminar
    $('#alumnosClasesTablaId').on('click', '.eliminar-button', function () {
        const claseAlumnoId = $(this).data('id');
        const clase_alumno = props.clases_alumnos.find(c => c.idClaseAlumno === claseAlumnoId);
        eliminarAlumnosClase(claseAlumnoId, clase_alumno.idClaseAlumno);
    });
});

</script>

<template>
    <DirectorLayout title="clases" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <h2 class="text-black text-2xl text-center font-semibold p-5">Agregar alumnos a clases</h2>
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
                    <i class="fa fa-plus mr-2"></i>Agregar Alumnos a Clase
                </button>
                <button id="eliminarMBtn" disabled="true"
                    class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                    @click="eliminarAlumnosClases">
                    <i class="fa fa-trash mr-2"></i>Borrar Alumnos de clase(s)
                </button>
                <!--</div>-->
            </div>
            <div class="overflow-x-auto ">
                <DataTable class="w-full table-auto text-sm display stripe compact cell-border order-column"
                    id="alumnosClasesTablaId" :columns="columns" :data="clases_alumnos" :options="{
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
                                Clase
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Nombre del alumno
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Calificación
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

        <formulario-alumnos-clase :show="mostrarModal" :max-width="maxWidth" :closeable="closeable" @close="cerrarModal"
            :title="'Añadir clase'" :op="'1'" :modal="'modalCreate'" :clases="props.clases" :materias="props.materias"
            :grados="props.grados" :grupos="props.grupos" :alumnos="props.alumnos"></formulario-alumnos-clase>
        <formulario-alumnos-clase :show="mostrarModalE" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalE"
            :title="'Editar clase'" :op="'2'" :modal="'modalEdit'" :clases="props.clases"
            :alumnos="props.alumnos"></formulario-alumnos-clase>

    </DirectorLayout>
</template>