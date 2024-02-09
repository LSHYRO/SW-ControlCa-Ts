<script setup>
import SecreLayout from '@/Layouts/SecreLayout.vue';
import { ref } from 'vue';
import Actividades from '@/Components/secre/ActividadesSeccion.vue';
import AlumnosSeccion from '@/Components/secre/AlumnosSeccion.vue';
import Calificaciones from '@/Components/secre/CalificacionesSeccion.vue';

const props = defineProps({
    clase: { type: Object },
    ciclos: { type: Object },
    alumno: { type: Object },
    claseA: { type: Object },
    clasesA: { type: Object },
    periodos: { type: Object },
    tiposActividades: { type: Object },
    usuario: { type: Object },
    actividades: { type: Object },
    alumnos: { type: Object },
    calificaciones: { type: Object },
    calificacionPer: { type: Object },
    clasesFinal: { type: Object },
    tiposActividadesAlum: { type: Object },
    actividadesAlum: { type: Object },
});
console.log(props.periodos);
const mostrarAc = ref(true);
const mostrarAl = ref(false);
const mostrarCal = ref(false);

const mostrarActividades = () => {
    const btnActividades = document.getElementById("btnMAc");
    const btnAlumnos = document.getElementById("btnMAl");
    const btnCalificaciones = document.getElementById("btnMCal");
    btnCalificaciones.className = "inline-block w-full p-4 text-gray-900 bg-gray-100 border-r border-gray-200 rounded-s-lg focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer";
    btnActividades.className = "inline-block w-full p-4 text-gray-900 bg-gray-100 border-r border-gray-200 rounded-s-lg focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer";
    btnAlumnos.className = "inline-block w-full p-4 bg-white border-r border-gray-200 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none cursor-pointer";
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
    <SecreLayout title="clases" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg alturaM">
            <h2 class="text-black text-2xl text-center font-semibold p-5">{{ clasesA['materias'].materia }}</h2>

            <h1 class="mb-0 text-md font-normal tracking-tight text-center"> Alumno: {{ alumno.nombre_completo }}</h1>

            <div class="py-3 flex flex-col md:flex-row md:items-start md:space-x-3 space-y-3 md:space-y-0">
                <div class="m-0">
                    <a :href="route('secre.verCalificaciones', { idAlumno: alumno.idAlumno})"><i class="fa-solid fa-arrow-left"> </i> Volver</a><!-- Se tiene que cambiar la ruta -->
                </div>
            </div>
            <!-- Linea de separación -->
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-5"></div>

            <ul class="hidden text-sm font-medium text-center text-gray-500 rounded-lg shadow sm:flex m-2 ">
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
                        Calificaciones finales</a>
                </li>
            </ul>
            <div v-if="mostrarAc">
                <actividades :tiposActividades="props.tiposActividades" :clase="props.clasesA" :periodos="props.periodos"
                    :actividades="props.actividades" />
            </div>
            <div v-if="mostrarAl">
                <AlumnosSeccion :tiposActividades="props.tiposActividadesAlum" :clase="props.clase"
                    :periodos="props.periodos" :actividadesAlum="props.actividadesAlum"/>
            </div>
            <div v-if="mostrarCal">
                <Calificaciones :periodos="props.periodos" :claseA="props.claseA" :clasesA="props.clasesA" :calificacionPer="props.calificacionPer" :clasesFinal="props.clasesFinal" :ciclos="props.ciclos"/>
            </div>
        </div>
    </SecreLayout>
</template>

<style>
.alturaM {
    min-height: 80vh;
}
</style>