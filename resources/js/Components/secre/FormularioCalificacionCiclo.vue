<script setup>
// Importaciones necesarias para el funcionamiento del 
// formulario
import Modal from '../Modal.vue';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3';
import { onMounted, watch, ref } from 'vue';
import axios from 'axios';
const emit = defineEmits(['close']);

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    ciclos: {
        type: Object,
        default: () => ({}),
    },/*
    clases: {
        type: Object,
        default: () => ({}),
    },
    periodos: {
        type: Object,
        default: () => ({}),
    },
    materias: {
        type: Object,
        default: () => ({}),
    },
    tiposActividades: {
        type: Object,
        default: () => ({}),
    },*/
    title: { type: String },
    modal: { type: String },
    op: { type: String },
    descripcion: String,
},
);

const close = () => {
    emit('close');
    form.reset(); // Llamar a la función reset para restablecer el formulario
};

const form = useForm({
    /*
    idActividad: props.actividad.idActividad,
    titulo: props.actividad.titulo,
    descripcion: props.actividad.descripcion,
    fecha_inicio: props.fecha_inicio,
    fecha_entrega: props.fecha_entrega,
    idClase: props.clases.idClase,
    periodo: props.actividad.periodo,
    tipoActividad: props.actividad.idTipoActividad,
    */
    ciclo: '',

});

// Variables para los mensajes de validación
const cicloError = ref('');
/*
const periodoError = ref('');
const tipoActividadError = ref('');
const tituloError = ref('');
const fecha_inicioError = ref('');
const fecha_entregaError = ref('');
*/
/*
// Validación de cadenas no vacias
const validateStringNotEmpty = (value) => {
    return typeof value === 'string' && value.trim() !== '';
}
*/
// Validación del select 
const validateSelect = (selectedValue) => {
    if (selectedValue == undefined || selectedValue == '') {
        return false;
    }
    return true;
};
/*
const validateFechaTrabajo = (value) => {
    const fechaInicioPeriodo = form.periodo.fecha_inicio;
    const fechaFinPeriodo = form.periodo.fecha_fin;

    return value >= fechaInicioPeriodo && value <= fechaFinPeriodo;
};

const validarFechasT = () => {
    return form.fecha_entrega >= form.fecha_inicio;
}
*/

const save = () => {
    //tituloError.value = validateStringNotEmpty(form.titulo) ? '' : 'Ingrese el titulo de la actividad';
    cicloError.value = validateSelect(form.ciclo) ? '' : 'Seleccione el ciclo a calificar';
    console.log(form.ciclo);
    /*fecha_inicioError.value = validarFechasT() && validateFechaTrabajo(form.fecha_inicio) ? '' : 'Ingrese una fecha correcta';
    fecha_entregaError.value = validarFechasT() && validateFechaTrabajo(form.fecha_entrega) ? '' : 'Ingrese una fecha correcta';
    tipoActividadError.value = validateSelect(form.tipoActividad) ? '' : 'Seleccione el tipo de actividad';*/

    if (
        cicloError.value //tituloError.value || periodoError.value || fecha_inicioError.value || fecha_entregaError.value || tipoActividadError.value
    ) {
        return;
    }
    const swal = Swal.mixin({
        buttonsStyling: true
    })
    swal.fire({
        title: '¿Estas seguro de calificar este ciclo?' + '\nAsegurese que los profesores ya hayan calificado sus clases',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Confirmar',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.post(route('secre.calificarCiclo'), {
                onSuccess: () => {
                    close()
                    /*tituloError.value = '';
                    periodoError.value = '';
                    fecha_inicioError.value = '';
                    fecha_entregaError.value = '';
                    tipoActividadError.value = '';*/
                    cicloError.value = '';
                }
            });
        }

    })/*
    form.post(route('director.calificarCiclo'), {
        onSuccess: () => {
            close()            
            cicloError.value = '';
        }
    });*/
}

const update = () => {
    /*tituloError.value = validateStringNotEmpty(form.titulo) ? '' : 'Ingrese el titulo de la actividad';
    periodoError.value = validateSelect(form.periodo) ? '' : 'Seleccione el periodo';
    fecha_inicioError.value = validarFechasT() && validateFechaTrabajo(form.fecha_inicio) ? '' : 'Ingrese una fecha correcta';
    fecha_entregaError.value = validarFechasT() && validateFechaTrabajo(form.fecha_entrega) ? '' : 'Ingrese una fecha correcta';
    tipoActividadError.value = validateSelect(form.tipoActividad) ? '' : 'Seleccione el tipo de actividad';*/
    cicloError.value = validateSelect(form.ciclo) ? '' : 'Seleccione el ciclo a calificar';

    if (
        cicloError.value //tituloError.value || periodoError.value || fecha_inicioError.value || fecha_entregaError.value || tipoActividadError.value
    ) {
        return;
    }
    var idActividad = document.getElementById('idActividad2').value;
    form/*.transform(data => ({
        ...data,
        clase: props.clases.idClase
    }))*/.put(route('profe.actActividad', idActividad), {
        onSuccess: () => {
            close()/*
            tituloError.value = '';
            periodoError.value = '';
            fecha_inicioError.value = '';
            fecha_entregaError.value = '';
            tipoActividadError.value = '';*/
            cicloError.value = '';
        }
    });
}
/*
watch(() => props.actividad, (newVal) => {
    form.idActividad = newVal.idActividad;
    form.titulo = newVal.titulo;
    form.idClase = newVal.idClase;
    form.periodo = newVal.periodo;
    form.tipoActividad = newVal.idTipoActividad;
    form.descripcion = newVal.descripcion;
    form.fecha_inicio = newVal.fecha_inicio;
    form.fecha_entrega = newVal.fecha_entrega;

}, { deep: true });
*/
</script>

<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="mt-2 bg-white p-4 shadow rounded-lg">

            <form @submit.prevent="(op === '1' ? save() : update())">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">{{ title }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Seleccione el ciclo a calificar</p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        <div class="sm:col-span-3">
                            <label for="ciclos" class="block text-sm font-medium leading-6 text-gray-900">Ciclo</label>
                            <div class="mt-2">
                                <select name="ciclos" :id="'ciclos' + op" v-model="form.ciclo"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Selecciona un ciclo</option>
                                    <option v-for="ciclo in ciclos" :key="ciclo.idCiclo" :value="ciclo">
                                        {{ ciclo.descripcionCiclo }}
                                    </option>
                                </select>
                            </div>
                            <div v-if="cicloError != ''" class="text-red-500 text-xs">{{ cicloError }}</div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <button type="button" :id="'cerrar' + op" class="text-sm font-semibold leading-6 text-gray-900"
                        data-bs.dismiss="modal" @click="close">Cancelar</button>
                    <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                        :disabled="form.processing"> <i class="fa-solid fa-floppy-disk mr-2"></i>Guardar</button>
                </div>
            </form>
        </div>
    </Modal>
</template>
<style>
.swal2-popup {
    font-size: 15px !important;
}
</style>