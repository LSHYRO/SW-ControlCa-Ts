<script setup>
import TutorLayout from '@/Layouts/TutorLayout.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Actividades from '@/Components/tutor/ActividadesSeccion.vue';
import Calificaciones from '@/Components/tutor/CalificacionesSeccion.vue'

const props = defineProps({
    clase: { type: Object },
    clasesA: { type: Object },
    clasesPorAlumno:{ type: Object },
    periodos: { type: Object },
    tiposActividades: { type: Object },
    usuario: { type: Object },
    actividades: { type: Object },
    alumno: { type: Object },
    calificacion: { type: Object },
});
console.log("periodos");
console.log(props.periodos);

const mostrarAc = ref(true);
const mostrarAl = ref(false);
const mostrarCal = ref(false);

const mostrarActividades = () => {
    const btnActividades = document.getElementById("btnMAc");
    //const btnAlumnos = document.getElementById("btnMAl");
    btnActividades.className = "inline-block w-full p-4 text-gray-900 bg-gray-100 border-r border-gray-200 rounded-s-lg focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer";
    //btnAlumnos.className = "inline-block w-full p-4 bg-white border-r border-gray-200 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer";
    mostrarAc.value = true;
    //mostrarAl.value = false;
};

const mostrarCalificaciones = () => {
    const btnActividades = document.getElementById("btnMAc");
    //const btnAlumnos = document.getElementById("btnMAl");
    const btnCalificaciones = document.getElementById("btnMCal");
    btnCalificaciones.className = "inline-block w-full p-4 text-gray-900 bg-gray-100 border-r border-gray-200 rounded-s-lg focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer";
    //btnAlumnos.className = "inline-block w-full p-4 bg-white border-r border-gray-200 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer";
    btnActividades.className = "inline-block w-full p-4 bg-white border-r border-gray-200 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer";
    mostrarAc.value = false;
    //mostrarAl.value = false;
    mostrarCal.value = true;
}

</script>

<template>
    <TutorLayout title="clases" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg alturaM">
            <h2 class="text-black text-2xl text-center font-semibold p-5">{{ clasesA['materias'].materia }}</h2>

            <div class="py-3 flex flex-col md:flex-row md:items-start md:space-x-3 space-y-3 md:space-y-0">
                <div class="m-1">
                    <a :href="route('tutor.calificaciones')"><i class="fa-solid fa-arrow-left"> </i> Volver</a>
                </div>
            </div>
            <!-- Linea de separación -->
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>

            <ul class="hidden text-sm font-medium text-center text-gray-500 rounded-lg shadow sm:flex m-2 ">
                <li class="w-full">
                    <a @click="mostrarActividades()" id="btnMAc"
                        class="inline-block w-full p-4 text-gray-900 bg-gray-100 border-r border-gray-200 rounded-s-lg focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer">Actividades</a>
                </li>
                <li class="w-full">
                    <a @click="mostrarCalificaciones()" id="btnMCal"
                        class="inline-block w-full p-4 bg-white border-r border-gray-200 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer">
                        Calificaciones finales</a>
                </li>
            </ul>
            <div v-if="mostrarAc">
                <actividades :tiposActividades="props.tiposActividades" :clase="props.clasesA" :periodos="props.periodos"
                    :actividades="props.actividades" :clasesPorAlumno="props.clasesPorAlumno" :usuario="$props.usuario" :alumno="props.alumno"/>
            </div>
            <div v-if="mostrarCal">
                <Calificaciones :periodos="props.periodos" :clasesA="props.clasesA"/>
            </div>
        </div>


    </TutorLayout>
</template>

<style>
.alturaM {
    min-height: 80vh;
}
</style>