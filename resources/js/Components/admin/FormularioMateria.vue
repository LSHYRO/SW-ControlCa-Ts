<script setup>
import Modal from '../Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { watch } from 'vue';
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
    materias: {
        type: Object,
        default: () => ({}),
    },
    title: { type: String },
    modal: { type: String },
    op: { type: String },
    materia: String,
    descripcion: String,
    esTaller: Boolean
},
);


const close = () => {
    emit('close');
    form.reset();
};

const form = useForm({
    idMateria: props.materias.idMateria,
    materia: props.materias.materia,
    descripcion: props.materias.descripcion,
    esTaller: props.materias.esTaller,
});

const save = () => {
    form.post(route('admin.addMaterias'), {
        onSuccess: () => {
            close()
        }
    });
}

const update = () => {
    var idMateria = document.getElementById('idMateria2').value;
    console.log(idMateria);
    console.log(document.getElementById('materia2').value);
    form.put(route('admin.actualizarMaterias', idMateria), {
        onSuccess: () => close()
    });
}
watch(() => props.materias, (newVal) => {
    form.idMateria = newVal.idMateria;
    form.materia = newVal.materia;
    form.descripcion = newVal.descripcion;
    if(newVal.esTaller == "Si"){
        //form.esTaller = newVal.esTaller;
        form.esTaller = true;
    }else{
        form.esTaller = false;
    }
    
}, { deep: true });

</script>


<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="mt-2 bg-white p-4 shadow rounded-lg">

            <form @submit.prevent="(op === '1' ? save() : update())">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">{{ title }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Rellene todos los campos para poder registrar una nueva
                        materia </p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-1 md:col-span-2" hidden> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="idMateria" class="block text-sm font-medium leading-6 text-gray-900">id</label>
                            <div class="mt-2">
                                <input type="number" name="idMateria" v-model="form.idMateria" placeholder="Ingrese id"
                                    :id="'idMateria' + op"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="sm:col-span-1 md:col-span-2"> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="materia" class="block text-sm font-medium leading-6 text-gray-900">Materia</label>
                            <div class="mt-2">
                                <input type="text" name="materia" :id="'materia' + op" v-model="form.materia"
                                    placeholder="Ingrese el nombre de la materia"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-2">
                            <label for="descripcion"
                                class="block text-sm font-medium leading-6 text-gray-900">Descripción</label>
                            <div class="mt-2">
                                <textarea type="text" name="descripcion" :id="'descripcion' + op" v-model="form.descripcion"
                                    placeholder="Ingrese descripción" 
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 resize-none">
                                    </textarea>
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="esTaller" class="block text-sm font-medium leading-6 text-gray-900">¿Taller?</label>
                            <div class="mt-2">
                                <input type="checkbox" name="esTaller" :id="'esTaller' + op" :checked="form.esTaller" @change="form.esTaller = !form.esTaller">
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