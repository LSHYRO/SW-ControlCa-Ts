<script setup>
// Importaciones necesarias para la vista 
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, onMounted } from 'vue';
import FormularioUsuarios from '@/Components/admin/FormularioUsuarios.vue';
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
// Variables e inicializaciones para el datatable
window.JSZip = jsZip;
pdfmake.vfs = pdfFonts.pdfMake.vfs;
DataTable.use(DataTablesLib);
DataTable.use(ButtonsHtml5);
DataTable.use(pdfmake);
DataTable.use(Select);

// Variables que recibe la vista para la funcionalidad
const props = defineProps({
    usuarios: { type: Object },
    usuario: { type: Object }
});

const mostrarModal = ref(false);
const mostrarModalE = ref(false);
const maxWidth = 'xl';
const closeable = true;

const descripcionCrear = "Rellene todos los campos para poder registrar un nuevo usuario administrador";
const descripcionEditar = "Rellene todos los campos para poder actualizar la información de un usuario";

var usuariosE = ({});

const selectedUsuarios = ref([]);

const form = useForm({});

const abrirUsuarios = ($usuarioss) => {
    usuariosE = $usuarioss;
    console.log(usuariosE);
    mostrarModalE.value = true;
}

const cerrarModal = () => {
    mostrarModal.value = false;
};

const cerrarModalE = () => {
    mostrarModalE.value = false;
};

const columnsUsuario = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return `<input type="checkbox" class="usuario-checkbox" data-id="${row.idUsuario}" ">`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) { return meta.row + 1 }
    },
    { data: 'usuario' },
    //{ data: 'contrasenia' },
    {
        data: 'contrasenia', render: function (data, type, row, meta) {
            return `<div class="password-container">
                        <span class="ph password-hidden">${'*'.repeat(data.length)}</span>
                        <button class="mostrar-password-button" data-id="${row.idUsuario}"><i class="fa fa-eye"></i></button>
                    </div>`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="restaurar-usuario" data-id="${row.idUsuario}"><i class="fa fa-arrows-rotate"></i></button>`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="editar-button" data-id="${row.idUsuario}"><i class="fa fa-pencil"></i></button>`;
        }
    },
    {
        data: null, render: function (data, type, row, meta) {
            return `<button class="eliminar-button" data-id="${row.idUsuario}"><i class="fa fa-trash"></i></button>`;
        }

    }
];

const botonesUsuario = [{
    title: 'Usuarios registrados',
    extend: 'excelHtml5',
    text: '<i class="fa-solid fa-file-excel"></i> Excel',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Usuarios registrados',
    extend: 'pdfHtml5',
    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Usuarios registrados',
    extend: 'print',
    text: '<i class="fa-solid fa-print"></i> Imprimir',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
{
    title: 'Usuarios registrados',
    extend: 'copy',
    text: '<i class="fa-solid fa-copy"></i> Copiar Texto',
    className: 'bg-cyan-500 hover:bg-cyan-600 text-white py-1/2 px-3 rounded'
},
];

const toggleUsuarioSelection = (usuario) => {
    if (selectedUsuarios.value.includes(usuario)) {
        // Si la materia ya está seleccionada, la eliminamos del array
        console.log("Se quito la materia del la seleccion");
        selectedUsuarios.value = selectedUsuarios.value.filter((u) => u !== usuario);
    } else {
        selectedUsuarios.value.push(usuario);

    }
    const botonEliminar = document.getElementById("eliminarUBtn");

    if (selectedUsuarios.value.length > 0) {
        botonEliminar.removeAttribute("disabled");
        console.log("Se ha habilitado el botón");
    } else {
        botonEliminar.setAttribute("disabled", "");
        console.log("Se ha deshabilitado el botón");
    }
};

const eliminarUsuario = (idUsuario, usuario) => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: `¿Estas seguro que deseas eliminar los datos de ` + usuario + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('admin.eliminarUsuarios', idUsuario));
        }

    })
};

const eliminarUsuarios = () => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })

    swal.fire({
        title: '¿Estas seguro que deseas eliminar los datos de los usuarios seleccionados?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const usuariosS = selectedUsuarios.value.map((usuario) => usuario.idUsuario);
                const $usuariosIds = usuariosS.join(',');
                console.log(usuariosS);
                await form.delete(route('admin.elimUsuarios', $usuariosIds));

                // Limpia las materias seleccionadas después de la eliminación
                selectedUsuarios.value = [];
            } catch (error) {
                console.log('El error se origina aquí');
                console.log(error);
            }
        }
    });
};

const restaurarUsuario = (usuario) => {
    const idUsuario = usuario.idUsuario;
    const swal = Swal.mixin({
        buttonsStyling: true
    })

    swal.fire({
        title: '¿Estas seguro de restaurar los intentos y/o tiempo de cambio de contraseña?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await form.put(route('admin.restUsuario', idUsuario));
            } catch (error) {
                console.log('El error se origina aquí');
                console.log(error);
            }
        }
    });
};

onMounted(() => {
    // Agrega un escuchador de eventos fuera de la lógica de Vue
    document.getElementById('usuariosTablaId').addEventListener('click', (event) => {
        const checkbox = event.target;
        //console.log(checkbox);
        if (checkbox.classList.contains('usuario-checkbox')) {
            const usuarioId = parseInt(checkbox.getAttribute('data-id'));
            // Se asegura que props.materias.data esté definido antes de usar find
            if (props.usuarios) {
                const usuario = props.usuarios.find(usuario => usuario.idUsuario === usuarioId);
                if (usuario) {
                    toggleUsuarioSelection(usuario);
                } else {
                    console.log("No se tiene tutor");
                }
            }
        }
    });

    // Manejar clic en el botón de editar
    $('#usuariosTablaId').on('click', '.editar-button', function () {
        const usuarioId = $(this).data('id');
        const usuario = props.usuarios.find(u => u.idUsuario === usuarioId);
        abrirUsuarios(usuario);
    });

    // Manejar clic en el botón de eliminar
    $('#usuariosTablaId').on('click', '.eliminar-button', function () {
        const usuarioId = $(this).data('id');
        const usuario = props.usuarios.find(u => u.idUsuario === usuarioId);
        eliminarUsuario(usuarioId, usuario.usuario);
    });

    $('#usuariosTablaId').on('click', '.mostrar-password-button', function () {
        const usuarioId = $(this).data('id');
        const usuario = props.usuarios.find(u => u.idUsuario === usuarioId);
        const passwordCell = $(this).closest('td').find('.ph');

        if (passwordCell.hasClass('password-hidden')) {
            // Muestra la contraseña
            passwordCell.removeClass('password-hidden').text(usuario.contrasenia);
        } else {
            // Oculta la contraseña
            passwordCell.addClass('password-hidden').text('*'.repeat(usuario.contrasenia.length));
        }
    });

    $('#usuariosTablaId').on('click', '.restaurar-usuario', function () {
        const usuarioId = $(this).data('id');
        const usuario = props.usuarios.find(u => u.idUsuario === usuarioId);
        restaurarUsuario(usuario);
    })

});

const optionsUsuario = {
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
    buttons: botonesUsuario,
};

</script>

<template>
    <AdminLayout title="usuarios" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <h2 class="text-black text-2xl text-center font-semibold p-5">Usuarios</h2>
            <div class="my-1"></div> <!-- Espacio de separación -->
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
            <!-- flash message start -->
            <div v-if="$page.props.flash.message" class="p-4 mb-4 text-sm rounded-lg" role="alert"
                :class="`text-${$page.props.flash.color}-700 bg-${$page.props.flash.color}-100 dark:bg-${$page.props.flash.color}-200 dark:text-${$page.props.flash.color}`">
                <span class="font-medium">
                    {{ $page.props.flash.message }}
                </span>
            </div>
            <div class="py-3 flex flex-col md:flex-row md:items-start md:space-x-3 space-y-3 md:space-y-0">
                <!--<div class="w-full md:w-2/3 space-y-4 md:space-y-0 md:space-x-4 md:flex md:items-center md:justify-start">-->
                <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                    @click="mostrarModal = true" data-bs-toggle="modal" data-bs-target="#modalCreate">
                    <i class="fa fa-plus mr-2"></i>Agregar Usuario
                </button>
                <!--
                <button id="eliminarUBtn" disabled="true"
                    class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                    @click="eliminarUsuarios">
                    <i class="fa fa-trash mr-2"></i>Borrar Usuario(s)
                </button>
               </div>-->
            </div>
            <!--<div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>-->
            <!-- Línea con gradiente -->
            <div class="overflow-x-auto">
                <DataTable class="w-full table-auto text-sm display stripe compact cell-border order-column"
                    id="usuariosTablaId" :columns="columnsUsuario" :data="usuarios" :options="optionsUsuario">
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
                                Usuario
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Contrasenia
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Re
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Ed
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                El
                            </th>
                        </tr>
                    </thead>
                </DataTable>
            </div>
        </div>
        <formulario-usuarios :show="mostrarModal" :max-width="maxWidth" :closeable="closeable" @close="cerrarModal"
            :title="'Añadir usuario'" :op="'1'" :modal="'modalCreate'"
            :descripcion="descripcionCrear"></formulario-usuarios>
        <formulario-usuarios :show="mostrarModalE" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalE"
            :title="'Editar usuario'" :op="'2'" :modal="'modalEdit'" :usuarios="usuariosE"
            :descripcion="descripcionEditar"></formulario-usuarios>
    </AdminLayout>
</template>
<style>
.password-container {
    display: flex;
    align-items: center;
}

.password-hidden {
    font-family: monospace;
    color: gray;
}

.mostrar-password-button {
    cursor: pointer;
    border: none;
    background: none;
    color: black;
    text-decoration: underline;
    margin-left: auto;
    /* Esto alineará el botón a la derecha del contenedor */
}

.swal2-popup {
    font-size: 14px !important;
}
</style>
