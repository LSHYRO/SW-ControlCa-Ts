<script setup>
import { ref, onMounted } from 'vue';
import AlumnoLayout from '@/Layouts/AlumnoLayout.vue';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    tiposActividades: {
        type: Object,
        default: () => ({})
    },
    clasesA: {
        type: Object,
        default: () => ({})
    },
    periodos: {
        type: Object,
        default: () => ({})
    },
    calificacionPer:
    {
        type: Object,
        default: () => ({})
    },
    clasesFinal:
    {
        type: Object,
        default: () => ({})
    },
    ciclos:
    {
        type: Object,
        default: () => ({})
    },
});
console.log(props.clasesA);
const mostrarDetalles = ref({});
const mostrarDetallesFinal = ref(false);

const masInfo = (idPeriodo) => {
    mostrarDetalles.value[idPeriodo] = !mostrarDetalles.value[idPeriodo];
};

const masInfoFinal = () => {
    mostrarDetallesFinal.value = !mostrarDetallesFinal.value;
}

const obtenerPeriodo = (idPeriodo) => {
    return props.periodos.find(periodo => periodo.idPeriodo === idPeriodo) || {};
};

</script>

<template>
    <div>
        <div>
            <ul v-for="periodoo in props.calificacionPer" :key="periodoo.idPeriodo"
                class="w-full rounded-md border-2 border-cyan-500 hover:border-cyan-600 my-4 px-2">
                <li>
                    <div class="w-full grid-cols-12 grid cursor-pointer" @click="masInfo(periodoo.idPeriodo)">
                        <h4 class="text-base col-span-11">
                            <strong>Periodo: </strong>{{ obtenerPeriodo(periodoo.idPeriodo).periodo }}
                            <br>
                            <strong>Calificación: </strong>{{ periodoo.calificacion }}
                        </h4>
                        <div class="col-span-1 items-end text-end">
                            <i v-if="!mostrarDetalles[periodoo.idPeriodo]" class="fa-solid fa-caret-right text-lg"></i>
                            <i v-if="mostrarDetalles[periodoo.idPeriodo]" class="fa-solid fa-caret-down text-lg"></i>
                        </div>
                    </div>
                    <div v-if="mostrarDetalles[periodoo.idPeriodo]" class="w-full px-1 border-t-2 border-cyan-300">
                        <template v-for="periodo in props.periodos">
                            <p v-if="periodo.idPeriodo === periodoo.idPeriodo" class="text-sm m-1">
                                <strong>Fecha inicio: </strong>{{ periodo.fecha_inicio }}
                            </p>
                            <p v-if="periodo.idPeriodo === periodoo.idPeriodo" class="text-sm m-1">
                                <strong>Fecha final: </strong>{{ periodo.fecha_fin }}
                            </p>
                        </template>
                    </div>
                </li>
            </ul>
        </div>
        <div>
            <ul class="w-full rounded-md border-2 border-cyan-500 hover:border-cyan-600 my-4 px-2">
                <li>
                    <div class="w-full grid-cols-12 grid cursor-pointer" @click="masInfoFinal()">
                        <h4 class="text-base col-span-11">
                            <strong>Calificación final de {{ clasesA['materias'].materia }}: </strong>
                            {{ props.clasesFinal.calificacionClase }}
                        </h4>
                        <div class="col-span-1 items-end text-end">
                            <i v-if="!mostrarDetallesFinal" class="fa-solid fa-caret-right text-lg"></i>
                            <i v-if="mostrarDetallesFinal" class="fa-solid fa-caret-down text-lg"></i>
                        </div>
                    </div>
                    <div v-if="mostrarDetallesFinal" class="w-full px-1 border-t-2 border-cyan-300">
                        <p class="text-sm m-1">
                            <strong>Ciclo escolar: </strong>{{ clasesA.ciclos.descripcionCiclo }}
                        </p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
<style>
.swal2-popup {
    font-size: 15px !important;
}
</style>