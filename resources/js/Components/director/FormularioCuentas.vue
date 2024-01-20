<script setup>
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
    usuarios: {
        type: Object,
        default: () => ({}),
    },
    title: { type: String },
    modal: { type: String },
    op: { type: String },
    usuario: String,
    contrasenia: String,
},
);

const close = () => {
    emit('close');
    form.reset();
};


const form = useForm({
    idUsuario: props.usuarios.idUsuario,
    usuario: props.usuarios.usuario,
    contrasenia: props.usuarios.contrasenia,
});

// Variables para los mensajes de validación
const usuarioError = ref('');
const contraseniaError = ref('');

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

const save = () => {
    usuarioError.value = validateStringNotEmpty(form.usuario) ? '' : 'Ingrese el usuario';
    contraseniaError.value = validateStringNotEmpty(form.contrasenia) ? '' : 'Ingrese la contraseña';

    if (
        usuarioError.value || contraseniaError.value
    ) {

        return;
    }

    form.post(route('director.addCuentas'), {
        onSuccess: () => {
            close()
            usuarioError.value = '';
            contraseniaError.value = '';
        }
    });
}

const update = () => {

usuarioError.value = validateStringNotEmpty(form.usuario) ? '' : 'Ingrese el usuario';
contraseniaError.value = validateStringNotEmpty(form.contrasenia) ? '' : 'Ingrese la contraseña';

if (
    usuarioError.value || contraseniaError.value
) {

    return;
}

var idUsuario = document.getElementById('idUsuario2').value;
form.put(route('director.actualizarCuentas', idUsuario), {
    onSuccess: () => {
        close()
        usuarioError.value = '';
        contraseniaError.value = '';
    }
});
}

watch(() => props.usuarios, (newVal) => {
console.log(newVal);
form.idUsuario = newVal.idUsuario;
form.usuario = newVal.usuario;
form.contrasenia = newVal.contrasenia;
}, { deep: true });

</script>

<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="mt-2 bg-white p-4 shadow rounded-lg">

            <form @submit.prevent="(op === '1' ? save() : update())">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">{{ title }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Rellene todos los campos para poder registrar un nuevo
                        usuario
                    </p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-1 md:col-span-2" hidden> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="idUsuario" class="block text-sm font-medium leading-6 text-gray-900">id</label>
                            <div class="mt-2">
                                <input type="number" name="idUsuario" v-model="form.idUsuario" placeholder="Ingrese id"
                                    :id="'idUsuario' + op"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-6">
                            <label for="usuario"
                                class="block text-sm font-medium leading-6 text-gray-900">Usuario</label>
                            <div class="mt-2">
                                <input type="text" name="usuario" :id="'usuario' + op"
                                    v-model="form.usuario" placeholder="Ingrese el usuario"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <div v-if="usuarioError != ''" class="text-red-500 text-xs mt-1">{{
                                usuarioError }}</div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-6">
                            <label for="contrasenia"
                                class="block text-sm font-medium leading-6 text-gray-900">Contraseña</label>
                            <div class="mt-2">
                                <input type="text" name="contrasenia" :id="'contrasenia' + op"
                                    v-model="form.contrasenia" placeholder="Ingrese la contraseña"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <div v-if="contraseniaError != ''" class="text-red-500 text-xs mt-1">{{
                                contraseniaError }}</div>
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