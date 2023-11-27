<script setup>
import { ref, computed, getCurrentInstance } from 'vue';
import SearchBar from '@/Components/SearchBar.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FormularioMateria from '@/Components/admin/FormularioMateria.vue';
import MenuOpciones from '@/Components/admin/MenuOpciones.vue';
import Swal from 'sweetalert2';
import { useForm, usePage, Link } from '@inertiajs/vue3';

const props = defineProps({
    materias: { type: Object },

});


const mostrarModal = ref(false);
const mostrarModalE = ref(false);
const searchTerm = ref('');
const maxWidth = 'xl';
const closeable = true;

var mater = ({});

const selectedMaterias = ref([]);

const filteredMaterias = computed(() => {
    if (!searchQuery.value) {
        return props.materias.data; // Mostrar todas las materias si no hay término de búsqueda
    }

    const searchTermLower = searchQuery.value.toLowerCase();
    return props.materias.data.filter((materia) =>
        materia.materia.toLowerCase().includes(searchTermLower) ||
        materia.descripcion.toLowerCase().includes(searchTermLower) ||
        materia.esTaller.toString().toLowerCase().includes(searchTermLower)
    );
});

const toggleMateriaSelection = (materia) => {
    const materiaId = materia.idMateria;

    if (selectedMaterias.value.includes(materia)) {
        // Si la materia ya está seleccionada, la eliminamos del array
        selectedMaterias.value = selectedMaterias.value.filter((m) => m !== materia);
    } else {
        // Si la materia no está seleccionada, la agregamos al array
        selectedMaterias.value.push(materia);

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

</script>

<template>
    <AdminLayout title="materias">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <h2 class="text-black text-2xl text-center font-semibold p-5">Materias</h2>
            <div class="my-1"></div> <!-- Espacio de separación -->

            <div class="p-4 flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="w-full md:w-1/3 mb-4 md:mb-0">
                    <search-bar @update-search="handleSearch" />
                </div>
                <div class="w-full md:w-2/3 space-y-4 md:space-y-0 md:space-x-4 md:flex md:items-center md:justify-end">
                    <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                        @click="eliminarMaterias">
                        <i class="fa fa-trash mr-2"></i>Borrar Materia(s)
                    </button>
                    <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                        @click="mostrarModal = true" data-bs-toggle="modal" data-bs-target="#modalCreate">
                        <i class="fa fa-plus mr-2"></i>Agregar Materia
                    </button>
                </div>
            </div>
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
            <!-- Línea con gradiente -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto text-sm display" id="materiasTablaId">
                    <thead>
                        <tr class="text-sm leading-normal">
                            <th
                                class="py-2 px-4 bg-grey-lightest font-bold uppercase text-sm text-grey-light border-b border-grey-light">
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
                    <tbody>
                        <tr v-for="mmateria in filteredMaterias" :key="mmateria.idMateria" class="hover:bg-grey-lighter">
                            <td><input type="checkbox" @click="toggleMateriaSelection(mmateria)"></td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ mmateria.materia }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ mmateria.descripcion }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">{{ mmateria.esTaller }}</td>
                            <td class="py-2 px-4 border-b border-grey-light">
                                <button @click="abrirE(mmateria)" data-bs-toggle="modal" data-bs-target="#modalEdit">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button @click="eliminarMateria(mmateria.idMateria, mmateria.materia)">
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

        <formulario-materia :show="mostrarModal" :max-width="maxWidth" :closeable="closeable" @close="cerrarModal"
            :title="'Añadir materia'" :op="'1'" :modal="'modalCreate'"></formulario-materia>
        <formulario-materia :show="mostrarModalE" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalE"
            :title="'Editar materia'" :op="'2'" :modal="'modalEdit'" :materias="mater"></formulario-materia>

    </AdminLayout>
</template>