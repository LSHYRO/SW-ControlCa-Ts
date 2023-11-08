<script setup>
import { ref } from 'vue';
import SearchBar from '@/Components/SearchBar.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FormularioGrado from '@/Components/admin/FormularioGrado.vue';
import FormularioGrupo from '@/Components/admin/FormularioGrupo.vue';
import MenuOpciones from '@/Components/admin/MenuOpciones.vue';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    grados: { type: Object },
    grupos: { type: Object },

});
const mostrarModal = ref(false);
const mostrarModalE = ref(false);
const maxWidth = 'xl';
const closeable = true;

var grad = ({});
var grup = ({});


const form = useForm({});
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

const selectedOption = ref('Grado'); // Inicialmente, muestra la tabla de "Grado"

</script>

<template>
    <AdminLayout title="Grados y Grupos">

        <div class="mt-8 bg-white p-4 shadow rounded-lg">
             <!-- Botón o selector para elegir entre "Grado" y "Grupo" -->
             <div class="p-4 flex items-center justify-center">
                <button @click="selectedOption = 'Grado'" class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded mr-2">
                Grados
                </button>
                <button @click="selectedOption = 'Grupo'" class="bg-cyan-500 hover-bg-cyan-600 text-white font-semibold py-2 px-4 rounded mr-2">
                Grupos
                </button>
             </div>

             <!-- Sección para mostrar la tabla de "Grado" -->
             <div v-if="selectedOption === 'Grado'">
                    <!-- Agrega la tabla de datos específica para "Grado" aquí -->
                    <div class="mt-8 bg-white p-4 shadow rounded-lg">
                    <h2 class="text-black text-2xl text-center font-semibold p-5">Grados</h2>
                    <div class="my-1"></div> <!-- Espacio de separación -->
                    <div class="p-4 flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="w-full md:w-1/3 mb-4 md:mb-0">
                            <search-bar />
                        </div>
                        <div class="w-full md:w-2/3 space-y-4 md:space-y-0 md:space-x-4 md:flex md:items-center md:justify-end">
                            <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded">
                                <i class="fa fa-trash mr-2"></i>Borrar Grado(s)
                            </button>
                            <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                                @click="mostrarModal = true" data-bs-toggle="modal" data-bs-target="#modalCreate">
                                <i class="fa fa-plus mr-2"></i>Agregar Grado
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
                                <tbody>
                                <tr v-for="grado in grados" :key="grado.idGrado" class="hover:bg-grey-lighter">
                                    <td><input type="checkbox"></td>
                                    <td class="py-2 px-4 border-b border-grey-light">{{ grado.grado }}</td>
                                    <td class="py-2 px-4 border-b border-grey-light">{{ grado.ciclo }}</td>
                                    <td class="py-2 px-4 border-b border-grey-light">
                                        <button @click="abrirGrados(grado)" data-bs-toggle="modal" data-bs-target="#modalEdit">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button @click="eliminarGrado(grado.idGrado, grado.grado)">
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

                 <formulario-grado :show="mostrarModal" :max-width="maxWidth" :closeable="closeable" @close="cerrarModal"
                    :title="'Añadir grado'" :op="'1'" :modal="'modalCreate'"></formulario-grado>
                <formulario-grado :show="mostrarModalE" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalE"
                    :title="'Editar grado'" :op="'2'" :modal="'modalEdit'" :personal="person"></formulario-grado>

            </div> <!-- Aquí termina "Grado" -->

            <!-- Sección para mostrar la tabla de "Grupo" -->
            <div v-if="selectedOption === 'Grupo'">
                <!-- Agrega la tabla de datos específica para "Grupo" aquí -->

                <div class="mt-8 bg-white p-4 shadow rounded-lg">
                    <h2 class="text-black text-2xl text-center font-semibold p-5">Grupos</h2>
                    <div class="my-1"></div> <!-- Espacio de separación -->
                    <div class="p-4 flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="w-full md:w-1/3 mb-4 md:mb-0">
                            <search-bar />
                        </div>
                        <div class="w-full md:w-2/3 space-y-4 md:space-y-0 md:space-x-4 md:flex md:items-center md:justify-end">
                            <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded">
                                <i class="fa fa-trash mr-2"></i>Borrar Grupo(s)
                            </button>
                            <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                                @click="mostrarModalGrupo = true" data-bs-toggle="modal" data-bs-target="#modalCreate">
                                <i class="fa fa-plus mr-2"></i>Agregar Grupo
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
                                <tbody>
                                <tr v-for="grupo in grupos" :key="grupo.idGrupo" class="hover:bg-grey-lighter">
                                    <td><input type="checkbox"></td>
                                    <td class="py-2 px-4 border-b border-grey-light">{{ grupo.grupo }}</td>
                                    <td class="py-2 px-4 border-b border-grey-light">{{ grupo.ciclo }}</td>
                                    <td class="py-2 px-4 border-b border-grey-light">
                                        <button @click="abrirGrupos(grupo)" data-bs-toggle="modal" data-bs-target="#modalEdit">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button @click="eliminarGrupo(grupo.idGrupo, grupo.grupo)">
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

                <!--
                <formulario-grupo :show="mostrarModalGrupo" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalGrupo"
                    :title="'Añadir grupo'" :op="'1'" :modal="'modalCreate'"></formulario-grupo>
                <formulario-grupo :show="mostrarModalEGrupo" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalEGrupo"
                    :title="'Editar grupo'" :op="'2'" :modal="'modalEdit'" :personal="person"></formulario-grupo>
                    -->

            </div> <!-- Aquí termina "Grupo" -->
    </div>

    </AdminLayout>
</template>