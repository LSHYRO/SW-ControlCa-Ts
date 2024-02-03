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
    califacionesPeriodos:
    {
        type: Object,
        default: () => ({})
    }
});

console.log(props.periodos);

const mostrarDetalles = ref({});
const mostrarDetallesFinal = ref(false);

const masInfo = (idPeriodo) => {
    mostrarDetalles.value[idPeriodo] = !mostrarDetalles.value[idPeriodo];
};

const masInfoFinal = () => {
    mostrarDetallesFinal.value = !mostrarDetallesFinal.value;
}

</script>

<template>
    <div>
        <div>
            <ul v-for="periodoo in props.periodo" :key="periodoo.idPeriodo"
                class="w-full rounded-md border-2 border-cyan-500 hover:border-cyan-600 my-4 px-2">
                <li>
                    <div class="w-full grid-cols-12 grid cursor-pointer" @click="masInfo(periodoo.idPeriodo)">
                        <h4 class="text-base col-span-11">
                            <strong>Calificación de clase: </strong>
                            {{ periodo.descripcion }}
                        </h4>
                        <div class="col-span-1 items-end text-end">
                            <i v-if="!mostrarDetalles[periodoo.idPeriodo]" class="fa-solid fa-caret-right text-lg"></i>
                            <i v-if="mostrarDetalles[periodoo.idPeriodo]" class="fa-solid fa-caret-down text-lg"></i>
                        </div>
                    </div>
                    <div v-if="mostrarDetalles[periodoo.idPeriodo]" class="w-full px-1 border-t-2 border-cyan-300">
                        <p class="text-sm m-1">
                            <strong>Fecha inicio: </strong>{{ periodoo.fecha_ini }}
                        </p>
                        <p class="text-sm m-1">
                            <strong>Fecha final: </strong>{{ periodoo.fecha_f }}
                        </p>
                    </div>
                </li>
            </ul>
        </div>
        <div>
            <ul class="w-full rounded-md border-2 border-cyan-500 hover:border-cyan-600 my-4 px-2">
                <li>
                    <div class="w-full grid-cols-12 grid cursor-pointer" @click="masInfoFinal()">
                        <h4 class="text-base col-span-11">
                            <strong>Calificación final: </strong>
                            {{ clasesA['materias'].materia }}
                        </h4>
                        <div class="col-span-1 items-end text-end">
                            <i v-if="!mostrarDetallesFinal" class="fa-solid fa-caret-right text-lg"></i>
                            <i v-if="mostrarDetallesFinal" class="fa-solid fa-caret-down text-lg"></i>
                        </div>
                    </div>
                    <div v-if="mostrarDetallesFinal" class="w-full px-1 border-t-2 border-cyan-300">
                        <p class="text-sm m-1">
                            <strong>Ciclo escolar: </strong>{{ clasesA.descripcionCiclo }}
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