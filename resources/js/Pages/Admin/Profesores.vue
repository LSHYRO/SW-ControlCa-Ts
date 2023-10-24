<script setup>
import { ref } from 'vue';
import SearchBar from '@/Components/SearchBar.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FormularioProf from '@/Components/admin/FormularioProf.vue';
import MenuOpciones from '@/Components/admin/MenuOpciones.vue';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    personal: { type: Object },

});
const mostrarModal = ref(false);
const mostrarModalE = ref(false);
const maxWidth = 'xl';
const closeable = true;

var person = ({});


const form = useForm({});
const abrirE = ($profe) => {
    person = $profe;
    mostrarModalE.value = true;
    console.log($profe);
    console.log(person);
}
const cerrarModal = () => {
    mostrarModal.value = false;
};
const cerrarModalE = () => {
    mostrarModalE.value = false;
};

const eliminarProfesor = (idPersonal, nombre) => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: `¿Estas seguro que deseas eliminar los datos de ` + nombre + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('admin.eliminarProfesores', idPersonal));
        }

    })
};
</script>

<template>
    <AdminLayout title="profesores">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <h2 class="text-black text-2xl text-center font-semibold p-5">Docentes</h2>
            <div class="my-1"></div> <!-- Espacio de separación -->
            <div class="p-4 flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="w-full md:w-1/3 mb-4 md:mb-0">
                    <search-bar />
                </div>
                <div class="w-full md:w-2/3 space-y-4 md:space-y-0 md:space-x-4 md:flex md:items-center md:justify-end">
                    <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded">
                        <i class="fa fa-trash mr-2"></i>Borrar Docente(s)
                    </button>
                    <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                        @click="mostrarModal = true" data-bs-toggle="modal" data-bs-target="#modalCreate">
                        <i class="fa fa-plus mr-2"></i>Agregar docente
                    </button>
                </div>
            </div>
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
            <!-- Línea con gradiente -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto text-sm">
                    <thead>
                        <tr class="text-sm leading-normal">
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Apellido P
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Apellido M
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Nombre
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Fecha de nacimiento
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Correo Electronico
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                activo
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Numero de telefono
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="profesor in personal" :key="profesor.idPersonal" class="hover:bg-grey-lighter">
                            <td><input type="checkbox"></td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ profesor.apellidoP }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ profesor.apellidoM }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ profesor.nombre }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ profesor.fechaNacimiento }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ profesor.correoElectronico }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ profesor.activo }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ profesor.numTelefono }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">
                                <a href="tel:{{ profesor.numTelefono }}">
                                    <i class="fa fa-phone" aria-hidden="true"></i>
                                </a>
                            </td>
                            <td class="py-2 px-4 border-b border-grey-light">
                                <button @click="abrirE(profesor)" data-bs-toggle="modal" data-bs-target="#modalEdit">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button @click="eliminarProfesor(profesor.idPersonal, profesor.nombre_completo)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Botón "Ver más" para la tabla de Autorizaciones Pendientes -->
            <div class="text-right mt-4">
                <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded">
                    Ver más
                </button>
            </div>
        </div>

        <formulario-prof :show="mostrarModal" :max-width="maxWidth" :closeable="closeable" @close="cerrarModal"
            :title="'Añadir profesor'" :op="'1'" :modal="'modalCreate'"></formulario-prof>
        <formulario-prof :show="mostrarModalE" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalE"
            :title="'Editar profesor'" :op="'2'" :modal="'modalEdit'" :personal="person"></formulario-prof>

    </AdminLayout>
</template>