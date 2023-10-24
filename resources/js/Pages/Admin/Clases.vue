<script setup>
import { ref } from 'vue';
import SearchBar from '@/Components/SearchBar.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FormularioClases from '@/Components/admin/FormularioClases.vue';
import MenuOpciones from '@/Components/admin/MenuOpciones.vue';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    clases: { type: Object },

});
const mostrarModal = ref(false);
const mostrarModalE = ref(false);
const maxWidth = 'xl';
const closeable = true;

var clas = ({});


const form = useForm({});
const abrirE = ($clasee) => {
    clas = $clasee;
    mostrarModalE.value = true;
    console.log($clasee);
    console.log(clas);
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
</script>

<template>
    <AdminLayout title="clases">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <h2 class="text-black text-2xl text-center font-semibold p-5">Clases</h2>
            <div class="my-1"></div> <!-- Espacio de separación -->
            <div class="p-4 flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="w-full md:w-1/3 mb-4 md:mb-0">
                    <search-bar />
                </div>
                <div class="w-full md:w-2/3 space-y-4 md:space-y-0 md:space-x-4 md:flex md:items-center md:justify-end">
                    <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded">
                        <i class="fa fa-trash mr-2"></i>Borrar Clase(s)
                    </button>
                    <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                        @click="mostrarModal = true" data-bs-toggle="modal" data-bs-target="#modalCreate">
                        <i class="fa fa-plus mr-2"></i>Agregar Clase
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
                                Clase
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Hora
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Días
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Grupo
                            </th>
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
                                Grado
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
                    <tbody>
                        <tr v-for="clase in clases" :key="clase.idClase" class="hover:bg-grey-lighter">
                            <td><input type="checkbox"></td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ clase.clase }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ clase.hora }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ clase.dias }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ clase.grupo }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ clase.grado }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ clase.docente }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ clase.materia }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ clase.ciclo }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">
                                <button @click="abrirE(clase)" data-bs-toggle="modal" data-bs-target="#modalEdit">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button @click="eliminarClase(clase.idClase, clase.clase)">
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

        <formulario-clases :show="mostrarModal" :max-width="maxWidth" :closeable="closeable" @close="cerrarModal"
            :title="'Añadir clase'" :op="'1'" :modal="'modalCreate'"></formulario-clases>
        <formulario-clases :show="mostrarModalE" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalE"
            :title="'Editar clase'" :op="'2'" :modal="'modalEdit'" :personal="person"></formulario-clases>

    </AdminLayout>
</template>