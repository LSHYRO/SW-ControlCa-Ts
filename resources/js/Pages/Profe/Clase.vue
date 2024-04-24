<script setup>
import ProfeLayout from '@/Layouts/ProfeLayout.vue';
import { ref } from 'vue';
import Actividades from '@/Components/profe/ActividadesSeccion.vue';
import AlumnosSeccion from '@/Components/profe/AlumnosSeccion.vue';
import Calificaciones from '@/Components/profe/CalificacionesSeccion.vue'

const props = defineProps({
    clase: { type: Object },
    periodos: { type: Object },
    tiposActividades: { type: Object },
    usuario: { type: Object },
    actividades: { type: Object },
    alumnos: { type: Object },
    actividadesCompleto: { type: Object },
    tiposActividadesAlum: { type: Object },
    actividadesAlum: { type: Object },
});

const mostrarAc = ref(true);
const mostrarAl = ref(false);
const mostrarCal = ref(false);

const mostrarActividades = () => {
    const btnActividades = document.getElementById("btnMAc");
    const btnAlumnos = document.getElementById("btnMAl");
    const btnCalificaciones = document.getElementById("btnMCal");
    btnActividades.className = "inline-block w-full p-4 text-gray-900 bg-gray-100 border-r border-gray-200 rounded-s-lg focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer";
    btnAlumnos.className = "inline-block w-full p-4 bg-white border-r border-gray-200 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer";
    btnCalificaciones.className = "inline-block w-full p-4 bg-white border-r border-gray-200 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer";
    mostrarAc.value = true;
    mostrarAl.value = false;
    mostrarCal.value = false;
};

const mostrarAlumnos = () => {
    const btnActividades = document.getElementById("btnMAc");
    const btnAlumnos = document.getElementById("btnMAl");
    const btnCalificaciones = document.getElementById("btnMCal");
    btnAlumnos.className = "inline-block w-full p-4 text-gray-900 bg-gray-100 border-r border-gray-200 rounded-s-lg focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer";
    btnActividades.className = "inline-block w-full p-4 bg-white border-r border-gray-200 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer";
    btnCalificaciones.className = "inline-block w-full p-4 bg-white border-r border-gray-200 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer";
    mostrarAc.value = false;
    mostrarAl.value = true;
    mostrarCal.value = false;
}

const mostrarCalificaciones = () => {
    const btnActividades = document.getElementById("btnMAc");
    const btnAlumnos = document.getElementById("btnMAl");
    const btnCalificaciones = document.getElementById("btnMCal");
    btnCalificaciones.className = "inline-block w-full p-4 text-gray-900 bg-gray-100 border-r border-gray-200 rounded-s-lg focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer";
    btnAlumnos.className = "inline-block w-full p-4 bg-white border-r border-gray-200 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer";
    btnActividades.className = "inline-block w-full p-4 bg-white border-r border-gray-200 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer";
    mostrarAc.value = false;
    mostrarAl.value = false;
    mostrarCal.value = true;
}
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

            <ul class="text-sm font-medium text-center text-gray-500 rounded-lg shadow sm:flex m-2 ">
                <li class="w-full">
                    <a @click="mostrarActividades()" id="btnMAc"
                        class="inline-block w-full p-4 text-gray-900 bg-gray-100 border-r border-gray-200 rounded-s-lg focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer">Actividades</a>
                </li>
                <li class="w-full">
                    <a @click="mostrarAlumnos()" id="btnMAl"
                        class="inline-block w-full p-4 bg-white border-r border-gray-200 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer">
                        Asistencias</a>
                </li>
                <li class="w-full">
                    <a @click="mostrarCalificaciones()" id="btnMCal"
                        class="inline-block w-full p-4 bg-white border-r border-gray-200 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer">
                        Calificaciones</a>
                </li>
            </ul>
            <div v-if="mostrarAc">
                <actividades :tiposActividades="props.tiposActividades" :clase="props.clase" :periodos="props.periodos"
                    :actividades="props.actividades" />
            </div>
            <div v-if="mostrarAl">
                <AlumnosSeccion :tiposActividades="props.tiposActividadesAlum" :clase="props.clase"
                    :periodos="props.periodos" :actividades="props.actividadesAlum"/>
            </div>
            <div v-if="mostrarCal">
                <Calificaciones :periodos="props.periodos" :clase="props.clase"/>
            </div>
        </div>


    </ProfeLayout>
    <!--
    
    <formulario-actividades :show="mostrarModalE" :max-width="maxWidth" :closeable="closeable" @close="cerrarModalE"
        :title="'Editar actividad'" :op="'2'" :modal="'modalEdit'" :clases="props.clase"></formulario-actividades>
        -->
</template>

<style>
.alturaM {
    min-height: 80vh;
}
</style>
