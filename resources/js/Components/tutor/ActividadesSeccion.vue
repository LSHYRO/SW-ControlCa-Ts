<script setup>
import { ref, onMounted } from 'vue';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    tiposActividades: {
        type: Object,
        default: () => ({})
    },
    clase: {
        type: Object,
        default: () => ({})
    },
    periodos: {
        type: Object,
        default: () => ({})
    },
    actividades: {
        type: Object,
        default: () => ({})
    },
    calificacion: {
        type: Object,
        default: () => ({})
    },
    clasesPorAlumno: {
        type: Object,
        default: () => ({})
    },
    alumno: {
        type: Object,
        default: () => ({})
    },
    usuario: {
        type: Object,
        default: () => ({})
    },
});
console.log("clases por alumno en Seccion");
console.log(props.clasesPorAlumno);
console.log("alumno en Seccion");
console.log(props.alumno);

const calificaciones = ref([]);
const mostrarDetalles = ref({});
const maxWidth = 'xl';
const closeable = true;
const form = useForm({});

const masInfo = (idActividad) => {
    mostrarDetalles.value[idActividad] = !mostrarDetalles.value[idActividad];
};


</script>

<template>
    <div>
        <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
        <!--  //Mensaje para mostrar el mensaje de que se ha borrado o agregado correctamente un tutor               -->
        <div v-if="$page.props.flash.message" class="p-4 mb-4 text-sm rounded-lg" role="alert"
            :class="`text-${$page.props.flash.color}-700 bg-${$page.props.flash.color}-100 dark:bg-${$page.props.flash.color}-200 dark:text-${$page.props.flash.color}-800`">
            <span class="font-medium">
                {{ $page.props.flash.message }}
            </span>
        </div>

        <div>
            <ul v-for="actividad in props.actividades" :key="actividad.idActividad"
                class="w-full rounded-md border-2 border-cyan-500 hover:border-cyan-600 my-4 mb-2 px-2">
                <li>
                    <div class="w-full grid-cols-12 grid cursor-pointer" @click="masInfo(actividad.idActividad)">
                        <h4 class="text-base col-span-11 mb-2">
                            <strong>{{ actividad.tipoActividadD }}:</strong>
                            {{ actividad.titulo }}
                        </h4>
                        <div class="col-span-1 items-end text-end">
                            <i v-if="!mostrarDetalles[actividad.idActividad]" class="fa-solid fa-caret-right text-lg"></i>
                            <i v-if="mostrarDetalles[actividad.idActividad]" class="fa-solid fa-caret-down text-lg"></i>
                        </div>
                    </div>
                    <div v-if="mostrarDetalles[actividad.idActividad]" class="w-full px-1 border-t-2 border-cyan-300">
                        <p class="text-sm m-1">
                            <strong>Descripción: </strong>{{ actividad.descripcion }}
                        </p>
                        <p class="text-sm m-1">
                            <strong>Fecha de inicio: </strong>{{ actividad.fecha_i }}
                        </p>
                        <p class="text-sm m-1 ">
                            <strong>Fecha de entrega: </strong>{{ actividad.fecha_e }}
                        </p>
                        <p class="underline mb-2">
                        <div v-for="alumno in props.clasesPorAlumno" :key="alumno.info.idAlumno" class="mb-4">
                            <strong style="font-weight: bold; color: black;">Calificación: {{ actividad.calificacionesAlumnos[alumno.info.idAlumno]}}</strong>
                        </div>
                        </p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
