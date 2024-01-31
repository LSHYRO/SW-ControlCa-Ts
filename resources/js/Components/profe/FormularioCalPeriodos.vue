<script setup>
// Importaciones necesarias para el funcionamiento del 
// formulario
import Modal from '../Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { onMounted, watch, ref, } from 'vue';
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
    clases: {
        type: Object,
        default: () => ({}),
    },
    periodos: {
        type: Object,
        default: () => ({}),
    },
    title: { type: String },
    modal: { type: String },
    op: { type: String },
    descripcion: String,
},
);

const tiposActividad = ref([]);
const cargando = ref(false);

const close = () => {
    emit('close');
    tiposActividad.value = {};
    form.reset();
};

const form = useForm({
    idClase: props.clases.idClase,
    periodo: "",
    calTipoAct: {}
});

const periodoError = ref('');
const porcentajesError = ref('');

const validateSelect = (selectedValue) => {
    if (selectedValue == undefined || selectedValue == "") {
        return false;
    }
    return true;
};

const validatePorcentajes = () => {
    var suma = 0;
    for (let index = 0; index < tiposActividad.value.length; index++) {
        suma += form.calTipoAct[tiposActividad.value[index]['idTipoActividad']];
    }
    return suma === 100;
}

const obtenerTiposActividad = async () => {
    try {
        cargando.value = true;
        const response = await axios.get(route('profe.buscarTipAct', [props.clases.idClase, form.periodo.idPeriodo]));
        tiposActividad.value = response.data;
    } catch ($e) {
        console.log($e);
    } finally {
        cargando.value = false;
    }

}

const save = () => {
    periodoError.value = validateSelect(form.periodo) ? '' : 'Seleccione el periodo';
    porcentajesError.value = validatePorcentajes() ? '' : 'Ingrese porcentajes que sumen 100';

    if (periodoError.value || periodoError.value) {
        return;
    }
    form.idClase = props.clases.idClase;
    form.post(route('profe.calificarPeriodo'), {
        onSuccess: () => {
            close()
            periodoError.value = '';
            tiposActividad.value = {};
        }
    });
}
/*
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
*/
</script>

<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="mt-2 bg-white p-4 shadow rounded-lg">

            <form @submit.prevent="(op === '1' ? save() : update())">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">{{ title }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Rellene todos los campos para poder registrar una nueva
                        actividad </p>

                    <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-6 justify-center items-center">
                            <label for="periodos"
                                class="block text-sm font-medium leading-6 text-gray-900 mx-2">Periodo</label>
                            <div class="mt-2 mx-2">
                                <select name="periodos" :id="'periodos' + op" v-model="form.periodo"
                                    @change="obtenerTiposActividad()"
                                    class="block rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Selecciona un periodo</option>
                                    <option v-for="periodo in periodos" :key="periodo.idPeriodo" :value="periodo">
                                        {{ periodo.descripcion }}
                                    </option>
                                </select>
                            </div>
                            <div v-if="periodoError != ''" class="mx-2 mt-2 text-red-500 text-xs">{{ periodoError }}</div>
                        </div>
                        <div class="sm:col-span-6 justify-center items-center flex">
                            <div v-if="cargando" class="loading-circle"></div>
                        </div>
                        <div v-if="!cargando" class="sm:col-span-6 justify-center items-center flex">
                            <div class="" v-for="tipoActividad in tiposActividad" :key="tipoActividad.idTipoActividad">
                                <label for="calTipoAct" class="block text-sm font-medium leading-6 text-gray-900 mt-2 mx-2">
                                    {{
                                        tipoActividad.tipoActividad }} </label>
                                <div class="mt-2">
                                    <input v-model="form.calTipoAct[tipoActividad.idTipoActividad]" type="number" required
                                        placeholder="Porcentaje"
                                        class="text-sm border rounded-md border-gray-300 min-w-32 mx-2" min="0" max="100">
                                </div>
                            </div>

                        </div>
                        <div class="sm:col-span-6 justify-center items-center flex">
                            <div v-if="porcentajesError != ''" class="mx-2 text-red-500 text-xs">{{ porcentajesError }}
                            </div>
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
    </Modal>
</template>
<style>
.loading-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 4px solid #ccc;
    /* Color del borde del círculo */
    border-top: 4px solid #3498db;
    /* Color del borde superior del círculo (puedes cambiar el color) */
    animation: spin 1s linear infinite;
    /* Animación de rotación */
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}</style>