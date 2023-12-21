<script setup>
import Modal from '../Modal.vue';
import { useForm } from '@inertiajs/vue3';
const emit = defineEmits(['close']);
import { router } from '@inertiajs/vue3';
import { watch } from 'vue';


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
    descripcionCiclo: props.ciclos.descripcionCiclo
});

const save = () => {
    form.post(route('admin.addCiclos'), {
        onSuccess: () => close()
    });
}

const update = () => {
    var idGrado = document.getElementById('idGrado2').value;
    console.log(idGrado);
    console.log(document.getElementById('grado2').value);
    form.put(route('admin.actualizarGrados', idGrado), {
        onSuccess: () => close()
    });
}
watch(() => props.grados, (newVal) => {
    form.idGrado = newVal.idGrado;
    form.grado = newVal.grado;
    form.idCiclo = newVal.idCiclo;
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
                        </div>

                        <div class="sm:col-span-1 md:col-span-2">
                            <label for="fecha_fin" class="block text-sm font-medium leading-6 text-gray-900">Fecha de terminacion</label>
                            <div class="mt-2">
                                <input type="date" name="fecha_fin" :id="'fecha_fin' + op" v-model="form.fecha_fin"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <span class="text-xs text-gray-500">Seleccione la fecha</span>
                            </div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-6">
                            <label for="descripcionCiclo" class="block text-sm font-medium leading-6 text-gray-900">Descripción</label>
                            <div class="mt-2">
                                <input type="text" name="descripcionCiclo" :id="'descripcionCiclo' + op" v-model="form.descripcionCiclo"
                                    placeholder="Ingrese descripción"
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