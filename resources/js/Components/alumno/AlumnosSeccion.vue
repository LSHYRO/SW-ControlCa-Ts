<script setup>
import { ref } from 'vue';
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
    actividadesAlum: {
        type: Object,
        default: () => ({})
    }
});
console.log("ActiviadesAlum en AlumnosSeccion");
console.log(props.actividadesAlum);

const mostrarDetalles = ref({});
const mostrarModal = ref(false);
const maxWidth = 'xl';
const closeable = true;
const mostrarModalEditar = ref(false);
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
            <ul v-for="actividad in props.actividadesAlum" :key="actividad.idActividad"
                class="w-full rounded-md border-2 border-cyan-500 hover:border-cyan-600 my-4 px-2">
                <li>
                    <div class="w-full grid-cols-12 grid cursor-pointer" @click="masInfo(actividad.idActividad)">
                        <h4 class="text-base col-span-11">
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
                            <strong>Fecha: </strong>{{ actividad.fecha_i }}
                        </p>
                        <p class="underline mb-2 text-sm">
                            <strong style="font-weight: bold; color: black;">Calificación: </strong>
                            <span style="font-weight: normal; color: your_color_here;">{{ actividad.calificacion }}</span>
                        </p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
