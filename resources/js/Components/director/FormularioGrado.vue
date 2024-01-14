<script setup>
// Importaciones necesarias para el funcionamiento del formulario
import Modal from '../Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { onMounted, watch, ref } from 'vue';
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
    grados: {
        type: Object,
        default: () => ({}),
    },
    ciclos: {
        type: Object,
        default: () => ({}),
    },
    title: { type: String },
    modal: { type: String },
    op: { type: String },
    grado: Number,
    idCiclo: String,
},
);

const emit = defineEmits(['close']);

const close = () => {
    emit('close');
    form.reset(); // Llamar a la función reset para restablecer el formulario
};

const form = useForm({
    idGrado: props.grados.idGrado,
    grado: props.grados.grado,
    ciclos: props.grados.idCiclo,//Le agregué la s
    ciclos: props.grados.descripcionCiclo//Le agregue la s a ciclo

});

// Variables para los mensajes de validación
const gradoError = ref('');
const ciclosError = ref('');

// Validación de cadenas no vacias
const validateStringNotEmpty = (value) => {
    return typeof value === 'string' && value.trim() !== '';
}

const validateInteger = (value) => {
    return /^\d+$/.test(value); // Verifica si el valor es una cadena de dígitos
};

// Validación del select 
const validateSelect = (selectedValue) => {
    if (selectedValue == undefined) {
        return false;
    }
    return true;
};

const save = () => {
    gradoError.value = validateInteger(form.grado) ? '' : 'Ingrese el grado en número';
    ciclosError.value = validateSelect(form.ciclos) ? '' : 'Seleccione el ciclo';

    if (
        gradoError.value || ciclosError.value
    ) {

        return;
    }

    form.post(route('director.addGrados'), {
        onSuccess: () => {
            close()
            gradoError.value = '';
            ciclosError.value = '';
        }
    });
}

const update = () => {
    gradoError.value = validateInteger(form.grado) ? '' : 'Ingrese el grado';
    ciclosError.value = validateSelect(form.ciclos) ? '' : 'Seleccione el ciclo';

    if (
        gradoError.value || ciclosError.value
    ) {

        return;
    }

    var idGrado = document.getElementById('idGrado2').value;
    console.log(idGrado);
    console.log(document.getElementById('grado2').value);
    form.put(route('director.actualizarGrados', idGrado), {
        onSuccess: () => {
            close()
            gradoError.value = '';
            ciclosError.value = '';
        }
    });
}
watch(() => props.grados, (newVal) => {
    form.idGrado = newVal.idGrado;
    form.grado = newVal.grado;
    form.ciclos = newVal.idCiclo;
}, { deep: true });

</script>


<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="mt-2 bg-white p-4 shadow rounded-lg">

            <form @submit.prevent="(op === '1' ? save() : update())">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">{{ title }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Rellene todos los campos para poder registrar un nuevo
                        grado
                    </p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-1 md:col-span-2" hidden> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="idGrado" class="block text-sm font-medium leading-6 text-gray-900">id</label>
                            <div class="mt-2">
                                <input type="number" name="idGrado" v-model="form.idGrado" placeholder="Ingrese id"
                                    :id="'idGrado' + op"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="sm:col-span-1 md:col-span-2"> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="grado" class="block text-sm font-medium leading-6 text-gray-900">Grado</label>
                            <div class="mt-2">
                                <input type="text" name="grado" :id="'grado' + op" v-model="form.grado"
                                    placeholder="Ingrese grado"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <div v-if="gradoError != ''" class="text-red-500 text-xs">{{ gradoError }}</div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="ciclo" class="block text-sm font-medium leading-6 text-gray-900">Ciclo</label>
                            <div class="mt-2">
                                <select name="ciclo" :id="'ciclo' + op" v-model="form.ciclos"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Selecciona un ciclo</option>
                                    <option v-for="ciclo in ciclos" :key="ciclo.idCiclo" :value="ciclo.idCiclo">
                                        {{ ciclo.descripcionCiclo }}
                                    </option>
                                </select>
                            </div>
                            <div v-if="ciclosError != ''" class="text-red-500 text-xs">{{ ciclosError }}</div>
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