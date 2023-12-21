<script setup>
import { ref } from 'vue';
import SearchBar from '@/Components/SearchBar.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import MenuOpciones from '@/Components/admin/MenuOpciones.vue';
import FormularioCiclos from '@/Components/admin/FormularioCiclos.vue';
import FormularioPeriodos from '@/Components/admin/FormularioPeriodos.vue';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    ciclos: { type: Object },
    periodos: { type: Object },
});

const mostrarModal = ref(false);
const mostrarModalE = ref(false);
const maxWidth = 'xl';
const closeable = true;

var cic = ({});
var per = ({});


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

const selectedOption = ref('Ciclos'); // Inicialmente, muestra la tabla de "Ciclos"

// Antes de abrir el formulario, reinicia el objeto form
const openFormCiclos = () => {
    form.value = { idCiclo: null, fecha_inicio: null, fecha_fin: null, descripcionCiclo: null };
    mostrarModal.value = true;
};

// Antes de abrir el formulario, reinicia el objeto form
const openFormPeriodos = () => {
    form.value = { idCiclo: null, fecha_inicio: null, fecha_fin: null, idCiclo: null };
    mostrarModal.value = true;
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
                    <div class="p-4 flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="w-full md:w-1/3 mb-4 md:mb-0">
                            <search-bar />
                        </div>
                        <div
                            class="w-full md:w-2/3 space-y-4 md:space-y-0 md:space-x-4 md:flex md:items-center md:justify-end">
                            <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded">
                                <i class="fa fa-trash mr-2"></i>Borrar Ciclo(s)
                            </button>
                            <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                                @click="openFormCiclos" data-bs-toggle="modal" data-bs-target="#modalCreate">
                                <i class="fa fa-plus mr-2"></i>Agregar Ciclo
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
                            <tbody>
                                <tr v-for="ciclo in ciclos" :key="ciclo.idCiclo" class="hover:bg-grey-lighter">
                                    <td><input type="checkbox"></td>
                                    <td class="py-2 px-4 border-b border-grey-light">{{ ciclo.fecha_inicio }}</td>
                                    <td class="py-2 px-4 border-b border-grey-light">{{ ciclo.fecha_fin }}</td>
                                    <td class="py-2 px-4 border-b border-grey-light">{{ ciclo.descripcionCiclo }}</td>
                                    <td class="py-2 px-4 border-b border-grey-light">
                                        <button @click="abrirCiclos(ciclo)" data-bs-toggle="modal"
                                            data-bs-target="#modalEdit">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button @click="eliminarCiclo(ciclo.idCiclo)">
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
                    <div class="p-4 flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="w-full md:w-1/3 mb-4 md:mb-0">
                            <search-bar />
                        </div>
                        <div
                            class="w-full md:w-2/3 space-y-4 md:space-y-0 md:space-x-4 md:flex md:items-center md:justify-end">
                            <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded">
                                <i class="fa fa-trash mr-2"></i>Borrar Periodo(s)
                            </button>
                            <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                                @click="mostrarModalPeriodos = true" data-bs-toggle="modal" data-bs-target="#modalCreate">
                                <i class="fa fa-plus mr-2"></i>Agregar Periodo
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
                            <tbody>
                                <tr v-for="periodo in periodos" :key="periodo.idPeriodo" class="hover:bg-grey-lighter">
                                    <td><input type="checkbox"></td>
                                    <td class="py-2 px-4 border-b border-grey-light">{{ periodo.periodo }}</td>
                                    <td class="py-2 px-4 border-b border-grey-light">{{ periodo.fecha_inicio }}</td>
                                    <td class="py-2 px-4 border-b border-grey-light">{{ periodo.fecha_fin }}</td>
                                    <td class="py-2 px-4 border-b border-grey-light">{{ periodo.idCiclo }}</td>
                                    <td class="py-2 px-4 border-b border-grey-light">
                                        <button @click="abrirPeriodos(periodo)" data-bs-toggle="modal"
                                            data-bs-target="#modalEdit">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button @click="eliminarPeriodo(periodo.idPeriodo, periodo.periodo)">
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

                <formulario-periodos :show="mostrarModalPeriodos" :max-width="maxWidth" :closeable="closeable"
                    @close="cerrarModalPeriodos" :title="'Añadir periodo'" :op="'1'"
                    :modal="'modalCreate'"></formulario-periodos>
                <formulario-periodos :show="mostrarModalPeriodos" :max-width="maxWidth" :closeable="closeable"
                    @close="cerrarModalEPeriodos" :title="'Editar periodo'" :op="'2'" :modal="'modalEdit'"
                    :personal="person"></formulario-periodos>


        </div> <!-- Aquí termina "Grupo" -->
    </div>

</AdminLayout></template>