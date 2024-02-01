<script setup>
// Importaciones necesarias para el funcionamiento del formulario
import Modal from '../Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { onMounted, watch, ref } from 'vue';
const emit = defineEmits(['close']);
import axios from 'axios';


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
    },
    title: { type: String },
    modal: { type: String },
    op: { type: String },
    ciclo: { type: Number },
    fecha_inicio: Date,
    fecha_fin: Date,
    descripcionCiclo: String
},
);

const close = () => {
    emit('close');
    form.reset();
};


const form = useForm({
    idCiclo: props.ciclos.idCiclo,
    fecha_inicio: props.ciclos.fecha_inicio,
    fecha_fin: props.ciclos.fecha_fin,
    descripcionCiclo: props.ciclos.descripcionCiclo,
});

// Variables para los mensajes de validación
const fecha_inicioError = ref('');
const fecha_finError = ref('');
const descripcionCicloError = ref('');

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

//Validación para el date
const validateDate = (date) => {
    return date !== null && date !== undefined && date !== '' && validarFechasT();
};

const validarFechasT = () => {
    return form.fecha_fin >= form.fecha_inicio;
}


const save = () => {
    fecha_inicioError.value = validateDate(form.fecha_inicio) ? '' : 'Ingrese la fecha de inicio correcto';
    fecha_finError.value = validateDate(form.fecha_fin) ? '' : 'Ingrese la fecha de terminación correcto';
    //descripcionCicloError.value = validateStringNotEmpty(form.descripcionCiclo) ? '' : 'Ingrese la descripción';

    if (
        fecha_inicioError.value || fecha_finError.value || descripcionCicloError.value
    ) {

        return;
    }

    form.post(route('admin.addCiclos'), {
        onSuccess: () => {
            close()
            fecha_inicioError.value = '';
            fecha_finError.value = '';
            descripcionCicloError.value = '';
        }
    });
}

const update = () => {
    fecha_inicioError.value = validateDate(form.fecha_inicio) ? '' : 'Ingrese la fecha de inicio correcto';
    fecha_finError.value = validateDate(form.fecha_fin) ? '' : 'Ingrese la fecha de terminación correcto';
    //descripcionCicloError.value = validateStringNotEmpty(form.descripcionCiclo) ? '' : 'Ingrese la descripción';

    if (
        fecha_inicioError.value || fecha_finError.value || descripcionCicloError.value
    ) {

        return;
    }

    var idCiclo = document.getElementById('idCiclo2').value;
    form.put(route('admin.actualizarCiclos', idCiclo), {
        onSuccess: () => {
            close()
            fecha_inicioError.value = '';
            fecha_finError.value = '';
            descripcionCicloError.value = '';
        }
    });
}

watch(() => props.ciclos, (newVal) => {
    console.log(newVal);
    form.idCiclo = newVal.idCiclo;
    form.fecha_inicio = newVal.fecha_inicio;
    form.fecha_fin = newVal.fecha_fin;
    form.descripcionCiclo = newVal.descripcionCiclo;
}, { deep: true });

</script>


<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="mt-2 bg-white p-4 shadow rounded-lg">

            <form @submit.prevent="(op === '1' ? save() : update())">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">{{ title }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Rellene todos los campos para poder registrar un nuevo
                        ciclo
                    </p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-1 md:col-span-2" hidden> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="idCiclo" class="block text-sm font-medium leading-6 text-gray-900">id</label>
                            <div class="mt-2">
                                <input type="number" name="idCiclo" v-model="form.idCiclo" placeholder="Ingrese id"
                                    :id="'idCiclo' + op"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-2">
                            <label for="fecha_inicio" class="block text-sm font-medium leading-6 text-gray-900">Fecha de
                                inicio</label>
                            <div class="mt-2">
                                <input type="date" name="fecha_inicio" :id="'fecha_inicio' + op" v-model="form.fecha_inicio"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <span class="text-xs text-gray-500">Seleccione la fecha</span>
                            </div>
                            <div v-if="fecha_inicioError != ''" class="text-red-500 text-xs">{{ fecha_inicioError }}
                            </div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-2">
                            <label for="fecha_fin" class="block text-sm font-medium leading-6 text-gray-900">Fecha de
                                terminacion</label>
                            <div class="mt-2">
                                <input type="date" name="fecha_fin" :id="'fecha_fin' + op" v-model="form.fecha_fin"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <span class="text-xs text-gray-500">Seleccione la fecha</span>
                            </div>
                            <div v-if="fecha_finError != ''" class="text-red-500 text-xs">{{ fecha_finError }}</div>
                        </div>
                        
                        <div class="sm:col-span-1 md:col-span-6" hidden>
                            <label for="descripcionCiclo"
                                class="block text-sm font-medium leading-6 text-gray-900">Descripción</label>
                            <div class="mt-2">
                                <input type="text" name="descripcionCiclo" :id="'descripcionCiclo' + op"
                                    v-model="form.descripcionCiclo" placeholder="Ingrese descripción"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <div v-if="descripcionCicloError != ''" class="text-red-500 text-xs mt-1">{{
                                descripcionCicloError }}</div>
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