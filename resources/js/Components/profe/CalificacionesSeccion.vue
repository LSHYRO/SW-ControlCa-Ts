<script setup>
import formularioCalificarPeriodo from '@/Components/profe/FormularioCalPeriodos.vue'
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
    califacionesPeriodos:
    {
        type: Object,
        default: () => ({})
    }
});

const mostrarDetalles = ref({});
const mostrarModal = ref(false);
const maxWidth = 'xl';
const closeable = true;
const form = useForm({});
const mostrarDetallesFinal = ref(false);

const masInfoFinal = () => {
    mostrarDetallesFinal.value = !mostrarDetallesFinal.value;
}

const actDesModal = () => {
    mostrarModal.value = !mostrarModal.value;
};

const masInfo = (idPeriodo) => {
    mostrarDetalles.value[idPeriodo] = !mostrarDetalles.value[idPeriodo];
};

const calificarClase = () => {
    try {
        const swal = Swal.mixin({
            buttonsStyling: true
        })
        swal.fire({
            title: '¿Desea realizar las calificaciones de la clase?\n Recuerde haber realizado las calificaciones de todos los periodos de la clase',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
            cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                form.post(route('profe.calificarClase', props.clase.idClase));
            }
        })
    } catch (e) {
        console.log(e);
    }
};

const eliminarCalificacionesPer = (idClase, idPeriodo) => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: '¿Estas seguro que deseas eliminar las calificaciones de este periodo?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar',

    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('profe.eliminarCalPer', [idClase, idPeriodo]));
        }

    })
};

const eliminarCalificacionesFin = () => {
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: '¿Estas seguro que deseas eliminar las calificaciones finales de la clase?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar',

    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('profe.eliminarCalFin', props.clase.idClase));
        }

    })
};
</script>
<template>
    <div>
        <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
        <!--  //Mensaje para mostrar el mensaje de que se ha borrado o agregado correctamente un tutor               -->
        <div v-if="$page.props.flash.message" class="p-4 mb-4 text-sm rounded-lg" role="alert"
            :class="`text-${$page.props.flash.color}-700 bg-${$page.props.flash.color}-100 dark:bg-${$page.props.flash.color}-200 dark:text-${$page.props.flash.color}`">
            <span class="font-medium">
                {{ $page.props.flash.message }}
            </span>
        </div>
        <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
        <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded mt-2 mx-1"
            data-bs-toggle="modal" data-bs-target="#modalCreate" @click="actDesModal()">
            <i class="fa fa-check-double mr-2"></i> Calificar/actualizar periodo
        </button>
        <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded mt-2 mx-1"
            data-bs-toggle="modal" data-bs-target="#modalCreate" @click="calificarClase()">
            <i class="fa fa-circle-check mr-2"></i> Calificar clase
        </button>
        <div>
            <ul v-for="periodo in props.periodos" :key="periodo.idPeriodo"
                class="w-full rounded-md border-2 border-cyan-500 hover:border-cyan-600 my-4 px-2">
                <li>
                    <div class="w-full grid-cols-12 grid cursor-pointer" @click="masInfo(periodo.idPeriodo)">
                        <h4 class="text-base col-span-11">
                            <strong>Calificación de clase: </strong>
                            {{ periodo.descripcion }}
                        </h4>
                        <div class="col-span-1 items-end text-end">
                            <i v-if="!mostrarDetalles[periodo.idPeriodo]" class="fa-solid fa-caret-right text-lg"></i>
                            <i v-if="mostrarDetalles[periodo.idPeriodo]" class="fa-solid fa-caret-down text-lg"></i>
                        </div>
                    </div>
                    <div v-if="mostrarDetalles[periodo.idPeriodo]" class="w-full px-1 border-t-2 border-cyan-300">
                        <p class="text-sm m-1">
                            <strong>Fecha inicio: </strong>{{ periodo.fecha_ini }}
                        </p>
                        <p class="text-sm m-1">
                            <strong>Fecha final: </strong>{{ periodo.fecha_f }}
                        </p>
                        <div class="my-2 w-full" id="btnsActividad">
                            <a class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-1 px-1 rounded mx-1 cursor-pointer"
                                @click="eliminarCalificacionesPer(props.clase.idClase, periodo.idPeriodo)">
                                <i class="fa fa-trash plus mr-2 text-sm"></i>Eliminar calificaciones
                            </a>
                            <a class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-1 px-1 rounded mx-1"
                                :href="route('profe.mostrarCalPer', [props.clase.idClase, periodo.idPeriodo])">
                                <i class="fa fa-award plus mr-2 text-sm"></i>Ver calificaciones
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div>
            <ul class="w-full rounded-md border-2 border-cyan-500 hover:border-cyan-600 my-4 px-2">
                <li>
                    <div class="w-full grid-cols-12 grid cursor-pointer" @click="masInfoFinal()">
                        <h4 class="text-base col-span-11">
                            <strong>Calificación de clase: </strong>
                            {{ clase['materias'].materia }}
                        </h4>
                        <div class="col-span-1 items-end text-end">
                            <i v-if="!mostrarDetallesFinal" class="fa-solid fa-caret-right text-lg"></i>
                            <i v-if="mostrarDetallesFinal" class="fa-solid fa-caret-down text-lg"></i>
                        </div>
                    </div>
                    <div v-if="mostrarDetallesFinal" class="w-full px-1 border-t-2 border-cyan-300">
                        <p class="text-sm m-1">
                            <strong>Ciclo escolar: </strong>{{ clase.descripcionCiclo }}
                        </p>
                        <div class="my-2 w-full" id="btnsActividad">
                            <a
                                class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-1 px-1 rounded mx-1 cursor-pointer"
                                @click="eliminarCalificacionesFin()">
                                <i class="fa fa-trash plus mr-2 text-sm"></i>Eliminar calificaciones
                            </a>
                            <a class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-1 px-1 rounded mx-1"
                            :href="route('profe.mostrarCalFin', props.clase.idClase)">
                                <i class="fa fa-award plus mr-2 text-sm"></i>Ver calificaciones
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <formularioCalificarPeriodo :periodos="props.periodos" :show="mostrarModal" :max-width="maxWidth" :closeable="closeable"
        @close="actDesModal()" :title="'Calificar periodo'" :op="'1'" :modal="'modalCreate'" :clases="props.clase" />
</template>
<style>
.swal2-popup {
    font-size: 15px !important;
}
</style>
