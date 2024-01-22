<script setup>
import ProfeLayout from '@/Layouts/ProfeLayout.vue';
import { ref, defineProps, defineEmits } from 'vue';
import { useForm } from '@inertiajs/vue3';
import FormularioActividades from '@/Components/profe/FormularioActividades.vue';

const props = defineProps({
    clase: { Object: Object },
    periodos: { Object: Object },
    tipoActividad: { Object: Object },
    usuario: { type: Object }
});

const mostrarModal = ref(false);
const mostrarModalE = ref(false);
const maxWidth = 'xl';
const closeable = true;

var actividadE = ({});

const selectedGrados = ref([]);

const form = useForm({});

const abrirE = ($actividadess) => {
    actividadE = $actividadess;
    mostrarModalE.value = true;
    console.log($actividadess);
    console.log(actividadE);
}

const cerrarModal = () => {
    mostrarModal.value = false;
};
const cerrarModalE = () => {
    mostrarModalE.value = false;
};

</script>

<template>
    <ProfeLayout title="clases" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg alturaM">
            <h2 class="text-black text-2xl text-center font-semibold p-5">{{ clase['materias'].materia }}</h2>

            <div class="py-3 flex flex-col md:flex-row md:items-start md:space-x-3 space-y-3 md:space-y-0">
                <div class="m-1">
                    <a :href="route('profe.clases')"><i class="fa-solid fa-arrow-left"> </i> Volver</a>
                </div>
            </div>
            <!-- Linea de separación -->
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>

            <ul
                class="hidden text-sm font-medium text-center text-gray-500 rounded-lg shadow sm:flex dark:divide-gray-700 dark:text-gray-400 mb-4">
                <li class="w-full">
                    <a href="#"
                        class="inline-block w-full p-4 text-gray-900 bg-gray-100 border-r border-gray-200 dark:border-gray-700 rounded-s-lg focus:ring-4 focus:ring-blue-300 active focus:outline-none dark:bg-gray-700 dark:text-white"
                        aria-current="page">Actividades</a>
                </li>
                <li class="w-full">
                    <a href="#"
                        class="inline-block w-full p-4 bg-white border-r border-gray-200 dark:border-gray-700 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700">Alumnos</a>
                </li>
            </ul>

            <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                @click="mostrarModal = true" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="fa fa-plus mr-2"></i> Agregar Actividad
            </button>


            <div class="m-2">
                <ul
                    class="hidden text-sm font-medium text-gray-500 rounded-lg shadow sm:flex dark:divide-gray-700 dark:text-gray-400 mb-4">
                    <li class="w-full">
                        <a href="#"
                            class="inline-block w-full p-4 text-gray-900 bg-teal-100 border-r border-gray-200 dark:border-gray-700 rounded-s-lg focus:ring-4 focus:ring-blue-300 active focus:outline-none dark:bg-gray-700 dark:text-white"
                            aria-current="page">Actividades</a>
                    </li>
                </ul>
            </div>


        </div>


    </ProfeLayout>

    <formulario-actividades :show="mostrarModal" :max-width="maxWidth" :closeable="closeable" @close="cerrarModal"
        :title="'Añadir actividad'" :op="'1'" :modal="'modalCreate'" :clases="props.clase" :periodos="props.periodos"
        :tipoActividad="props.tipoActividad"></formulario-actividades>
    <formulario-actividades :show="mostrarModalE" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalE"
        :title="'Editar actividad'" :op="'2'" :modal="'modalEdit'" :clases="props.clase"></formulario-actividades>
</template>

<style>
.alturaM {
    min-height: 80vh;
}
</style>
