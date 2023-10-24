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
    grupos: {
        type: Object,
        default: () => ({}),
    },
    title: { type: String },
    modal: { type: String },
    op: { type: String },
    grupo: String,

},
);

const close = () => {
    emit('close');
    form.reset(); // Llamar a la función reset para restablecer el formulario
};

const form = useForm({
    idGrupo: props.grupos.idGrupo,
    grupo: props.grrupos.grupo,
   idCiclo: props.grupos.idCiclo

});

const save = () => {
    form.post(route('admin.addGrupos'), {
        onSuccess: () => close()
    });
}

const update = () => {
    var idGrupo = document.getElementById('idGrupo2').value;
    console.log(idGrupo);
    console.log(document.getElementById('grupo2').value);
    form.put(route('admin.actualizarGrupos', idGrupo), {
        onSuccess: () => close()
    });
}
watch(() => props.grupos, (newVal) => {
    form.idGrupo= newVal.idGrupo;
    form.grupo = newVal.grupo;
    form.idCiclo = newVal.idCiclo;
}, { deep: true });

</script>


<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="mt-2 bg-white p-4 shadow rounded-lg">

            <form @submit.prevent="(op === '1' ? save() : update())">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">{{ title }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Rellene todos los campos para poder registrar un nuevo grupo
                    </p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-1 md:col-span-2" hidden> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="idGrupo" class="block text-sm font-medium leading-6 text-gray-900">id</label>
                            <div class="mt-2">
                                <input type="number" name="idGrado" v-model="form.idGrupo" placeholder="Ingrese id"
                                    :id="'idGrupo' + op"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="sm:col-span-1 md:col-span-2"> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="grupo" class="block text-sm font-medium leading-6 text-gray-900">Grupo</label>
                            <div class="mt-2">
                                <input type="text" name="grupo" :id="'grupo' + op" v-model="form.grupo"
                                    placeholder="Ingrese grupo"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-2">
                            <label for="ciclo" class="block text-sm font-medium leading-6 text-gray-900">Ciclo</label>
                            <div class="mt-2">
                                <select name="ciclo" v-model="form.idCiclo" :id="'ciclo' + op"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione el grupo</option>
                                    <option>A</option>
                                    <option>B</option>
                                    <option>C</option>
                                </select>
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