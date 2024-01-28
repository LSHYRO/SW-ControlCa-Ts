<script setup>
// Importaciones necesarias para el funcionamiento del 
// formulario
import Modal from '../Modal.vue';
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
    actividad: {
        type: Object,
        default: () => ({}),
    },
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
    },
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
    idActividad: props.actividad.idActividad,
    titulo: props.actividad.titulo,
    descripcion: props.actividad.descripcion,
    fecha_inicio: props.fecha_inicio,
    fecha_entrega: props.fecha_entrega,
    idClase: props.clases.idClase,
    periodo: props.actividad.periodo,
    tipoActividad: props.actividad.idTipoActividad,

});

// Variables para los mensajes de validación
const descripcionError = ref('');
const periodoError = ref('');
const tipoActividadError = ref('');
const tituloError = ref('');
const fecha_inicioError = ref('');
const fecha_entregaError = ref('');

// Validación de cadenas no vacias
const validateStringNotEmpty = (value) => {
    return typeof value === 'string' && value.trim() !== '';
}

// Validación del select 
const validateSelect = (selectedValue) => {
    if (selectedValue == undefined) {
        return false;
    }
    return true;
};

const validateFechaTrabajo = (value) => {
    const fechaInicioPeriodo = form.periodo.fecha_inicio;
    const fechaFinPeriodo = form.periodo.fecha_fin;

    return value >= fechaInicioPeriodo && value <= fechaFinPeriodo;
};

const validarFechasT = () => {
    return form.fecha_entrega >= form.fecha_inicio;
}


const save = () => {
    tituloError.value = validateStringNotEmpty(form.titulo) ? '' : 'Ingrese el titulo de la actividad';
    periodoError.value = validateSelect(form.periodo) ? '' : 'Seleccione el periodo';
    fecha_inicioError.value = validarFechasT() && validateFechaTrabajo(form.fecha_inicio) ? '' : 'Ingrese una fecha correcta';
    fecha_entregaError.value = validarFechasT() && validateFechaTrabajo(form.fecha_entrega) ? '' : 'Ingrese una fecha correcta';
    tipoActividadError.value = validateSelect(form.tipoActividad) ? '' : 'Seleccione el tipo de actividad';

    if (
        tituloError.value || periodoError.value || fecha_inicioError.value || fecha_entregaError.value || tipoActividadError.value
    ) {
        return;
    }
    form.idClase = props.clases.idClase;
    form.post(route('profe.agregarActividad'), {
        onSuccess: () => {
            close()
            tituloError.value = '';
            periodoError.value = '';
            fecha_inicioError.value = '';
            fecha_entregaError.value = '';
            tipoActividadError.value = '';
        }
    });
}

const update = () => {
    tituloError.value = validateStringNotEmpty(form.titulo) ? '' : 'Ingrese el titulo de la actividad';
    periodoError.value = validateSelect(form.periodo) ? '' : 'Seleccione el periodo';
    fecha_inicioError.value = validarFechasT() && validateFechaTrabajo(form.fecha_inicio) ? '' : 'Ingrese una fecha correcta';
    fecha_entregaError.value = validarFechasT() && validateFechaTrabajo(form.fecha_entrega) ? '' : 'Ingrese una fecha correcta';
    tipoActividadError.value = validateSelect(form.tipoActividad) ? '' : 'Seleccione el tipo de actividad';

    if (
        tituloError.value || periodoError.value || fecha_inicioError.value || fecha_entregaError.value || tipoActividadError.value
    ) {
        return;
    }
    var idActividad = document.getElementById('idActividad2').value;
    console.log(idActividad);
    console.log(document.getElementById('idActividad2').value);
    form.transform(data => ({
        ...data,
        clase: props.clases.idClase
    })).put(route('profe.actActividad', idActividad), {
        onSuccess: () => {
            close()
            tituloError.value = '';
            periodoError.value = '';
            fecha_inicioError.value = '';
            fecha_entregaError.value = '';
            tipoActividadError.value = '';
        }
    });
}

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

</script>

<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="mt-2 bg-white p-4 shadow rounded-lg">

            <form @submit.prevent="(op === '1' ? save() : update())">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">{{ title }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Rellene todos los campos para poder registrar una nueva
                        actividad </p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-1 md:col-span-2" hidden> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="idActividad" class="block text-sm font-medium leading-6 text-gray-900">id</label>
                            <div class="mt-2">
                                <input type="number" name="idActividad" v-model="form.idActividad" placeholder="Ingrese id"
                                    :id="'idActividad' + op"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-4"> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="titulo" class="block text-sm font-medium leading-6 text-gray-900">Nombre de la
                                actividad</label>
                            <div class="mt-2">
                                <input type="text" name="titulo" :id="'titulo' + op" v-model="form.titulo"
                                    placeholder="Ingrese el nombre de la actividad"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <div v-if="tituloError != ''" class="text-red-500 text-xs">{{ tituloError }}</div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-6"> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="descripcion" class="block text-sm font-medium leading-6 text-gray-900">Descripción
                                de la actividad</label>
                            <div class="mt-2">
                                <textarea type="text" name="descripcion" :id="'descripcion' + op" v-model="form.descripcion"
                                    placeholder="Ingrese la descripción de la actividad"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                            <div v-if="descripcionError != ''" class="text-red-500 text-xs">{{ descripcionError }}</div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="periodos" class="block text-sm font-medium leading-6 text-gray-900">Periodo</label>
                            <div class="mt-2">
                                <select name="periodos" :id="'periodos' + op" v-model="form.periodo"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Selecciona un periodo</option>
                                    <option v-for="periodo in periodos" :key="periodo.idPeriodo" :value="periodo">
                                        {{ periodo.descripcion }}
                                    </option>
                                </select>
                            </div>
                            <div v-if="periodoError != ''" class="text-red-500 text-xs">{{ periodoError }}</div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="tipoActividad" class="block text-sm font-medium leading-6 text-gray-900">Tipo
                                Actividad</label>
                            <div class="mt-2">
                                <select name="tipoActividad" :id="'tipoActividad' + op" v-model="form.tipoActividad"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Selecciona el tipo de actividad</option>
                                    <option v-for="tipoActividad in props.tiposActividades"
                                        :key="tipoActividad.idTipoActividad" :value="tipoActividad.idTipoActividad">
                                        {{ tipoActividad.tipoActividad }}
                                    </option>
                                </select>
                            </div>
                            <div v-if="periodoError != ''" class="text-red-500 text-xs">{{ periodoError }}</div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-2"> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="fecha_inicio" class="block text-sm font-medium leading-6 text-gray-900">Fecha de
                                inicio</label>
                            <div class="mt-2">
                                <input type="date" name="fecha_inicio" :id="'fecha_inicio' + op" v-model="form.fecha_inicio"
                                    placeholder="Ingrese la descripción de la actividad"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                            <div v-if="fecha_inicioError != ''" class="text-red-500 text-xs">{{ fecha_inicioError }}</div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-2"> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="fecha_entrega" class="block text-sm font-medium leading-6 text-gray-900">Fecha de
                                entrega</label>
                            <div class="mt-2">
                                <input type="date" name="fecha_entrega" :id="'fecha_entrega' + op"
                                    v-model="form.fecha_entrega" placeholder="Ingrese la descripción de la actividad"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                            <div v-if="fecha_entregaError != ''" class="text-red-500 text-xs">{{ fecha_entregaError }}</div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-2" hidden> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="idClase" class="block text-sm font-medium leading-6 text-gray-900">id Clase</label>
                            <div class="mt-2">
                                <input type="number" name="idClase" v-model="form.idClase" placeholder="Ingrese id"
                                    :id="'idClase' + op"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
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
</Modal></template>