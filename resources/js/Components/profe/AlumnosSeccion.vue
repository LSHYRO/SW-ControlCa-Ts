<script setup>
import { ref } from 'vue';
import FormularioAlumPaseL from './FormularioAlumPaseL.vue';
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
    }
});

const mostrarDetalles = ref({});
const mostrarModal = ref(false);
const maxWidth = 'xl';
const closeable = true;
const mostrarModalEditar = ref(false);
const form = useForm({});

const actDesModal = () => {
    mostrarModal.value = !mostrarModal.value;
};

const actDesModalEditar = () => {
    mostrarModalEditar.value = !mostrarModalEditar.value;
};

const masInfo = (idActividad) => {
    mostrarDetalles.value[idActividad] = !mostrarDetalles.value[idActividad];
};

const actE = ref({});

const abrirE = (actividad) => {
    actE.value = actividad;
    actDesModalEditar();
}

const editar = (idAct) => {
    const actividad = props.actividades.find(a => a.idActividad === idAct);
    abrirE(actividad);
}

const eliminarActividad = (idClase, idActividad, nombreAct) => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: `¿Estas seguro que deseas eliminar actividad ` + nombreAct + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('profe.eliminarAct', [idClase, idActividad]));
        }

    })
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
        <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
        <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded mt-2 mx-1"
            @click="actDesModal()" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus mr-2"></i> Agregar Pase de Lista
        </button>
        <div>
            <ul v-for="actividad in props.actividades" :key="actividad.idActividad"
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
                        <div class="my-2 w-full">
                            <a class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-1 px-1 rounded mx-1 cursor-pointer"
                                @click="editar(actividad.idActividad)">
                                <i class="fa fa-pencil plus mr-2 text-sm"></i>Editar actividad
                            </a>
                            <a class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-1 px-1 rounded mx-1 cursor-pointer"
                                @click="eliminarActividad(props.clase.idClase, actividad.idActividad, actividad.titulo)">
                                <i class="fa fa-trash plus mr-2 text-sm"></i>Eliminar actividad
                            </a>
                            <a class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-1 px-1 rounded mx-1"
                                :href="route('profe.paseLista', [props.clase.idClase, actividad.idActividad])">
                                <i class="fa fa-pen-to-square plus mr-2 text-sm"></i>Pasar Lista
                            </a>
                            <a class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-1 px-1 rounded mx-1"
                                :href="route('profe.mostrarPasLis', [props.clase.idClase, actividad.idActividad])">
                                <i class="fa fa-award plus mr-2 text-sm"></i>Ver calificaciones
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <FormularioAlumPaseL :show="mostrarModal" :max-width="maxWidth" :closeable="closeable" @close="actDesModal()"
        :title="'Añadir pase de lista'" :op="'1'" :modal="'modalCreate'" :clases="props.clase" :periodos="props.periodos"
        :tiposActividades="props.tiposActividades"></FormularioAlumPaseL>
    <FormularioAlumPaseL :show="mostrarModalEditar" :max-width="maxWidth" :closeable="closeable" @close="actDesModalEditar()"
        :title="'Añadir pase de lista'" :op="'2'" :modal="'modalCreate'" :clases="props.clase" :periodos="props.periodos"
        :tiposActividades="props.tiposActividades" :actividad="actE"></FormularioAlumPaseL>
</template>
