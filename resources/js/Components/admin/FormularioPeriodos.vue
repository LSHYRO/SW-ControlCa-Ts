<script setup>
import { ref, onMounted } from 'vue';
import Modal from '../Modal.vue';
import { useForm } from '@inertiajs/vue3';
const emit = defineEmits(['close']);
import { router } from '@inertiajs/vue3';
import { watch } from 'vue';
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
    periodos: {
        type: Object,
        default: () => ({}),
    },
    title: { type: String },
    modal: { type: String },
    op: { type: String },
    periodo: String,
    fecha_inicio: Date,
    fecha_fin: Date,
    idCiclo: Object
},
);

const close = () => {
    emit('close');
    form.reset();
};


const form = useForm({
    idPeriodo: props.periodos.idPeriodo,
    periodo: props.periodos.periodo,
    fecha_inicio: props.periodos.fecha_inicio,
    fecha_fin: props.periodos.fecha_fin,
    idCiclo: null // Inicialmente nulo, se llenará con los ciclos obtenidos
});

const ciclos = ref([]); // Aquí almacenarás los ciclos obtenidos de la base de datos

const obtenerCiclos = () => {
    axios.get('/ciclosperiodos')
        .then(response => {
            ciclos.value = response.data.ciclos;
        })
        .catch(error => {
            console.error('Error al obtener ciclos:', error);
        });
};


onMounted(() => {
    obtenerCiclos();
});

const save = () => {
    form.post(route('admin.addPeriodos'), {
        onSuccess: () => close()
    });
}

const update = () => {
    var idPeriodo = document.getElementById('idPeriodo2').value;
    console.log(idPeriodo);
    console.log(document.getElementById('perido2').value);
    form.put(route('admin.actualizarPeriodos', idPeriodo), {
        onSuccess: () => close()
    });
}
watch(() => props.periodos, (newVal) => {
    form.idPeriodo = newVal.idPeriodo;
    form.periodo = newVal.periodo;
    form.fecha_inicio = newVal.fecha_inicio;
    form.fecha_fin = newVal.fecha_fin;
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
                        periodo
                    </p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-1 md:col-span-2" hidden> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="idPeriodo" class="block text-sm font-medium leading-6 text-gray-900">id</label>
                            <div class="mt-2">
                                <input type="number" name="idPeriodo" v-model="form.idPeriodo" placeholder="Ingrese id"
                                    :id="'idPeriodo' + op"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-2">
                            <label for="periodo" class="block text-sm font-medium leading-6 text-gray-900">Periodo</label>
                            <div class="mt-2">
                                <input type="text" name="periodo" :id="'periodo' + op" v-model="form.periodo"
                                    placeholder="Ingrese el periodo"
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
                            <label for="fecha_fin" class="block text-sm font-medium leading-6 text-gray-900">Fecha de
                                terminacion</label>
                            <div class="mt-2">
                                <input type="date" name="fecha_fin" :id="'fecha_fin' + op" v-model="form.fecha_fin"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <span class="text-xs text-gray-500">Seleccione la fecha</span>
                            </div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-3">
                            <label for="idCiclo" class="block text-sm font-medium leading-6 text-gray-900">Ciclo</label>
                            <div class="mt-2">
                                <select name="idCiclo" :id="'idCiclo' + op" v-model="form.idCiclo"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione el ciclo</option>
                                    <!-- Utiliza v-for para iterar sobre los ciclos y llenar las opciones -->
                                    <option v-for="ciclo in ciclos" :key="ciclo.id" :value="ciclo.id">{{ ciclo.descripcionCiclo }}
                                    </option>
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