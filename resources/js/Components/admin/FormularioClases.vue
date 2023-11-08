<script setup>
import Modal from '../Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
const emit = defineEmits(['close']);
import { router } from '@inertiajs/vue3';
import { watch } from 'vue';
import TimePicker from 'vue3-timepicker';


const props = defineProps({
    show: {
        type: Boolean,
        default: false,
        hora: String,
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
    title: { type: String },
    modal: { type: String },
    op: { type: String },
    clase: String,
    hora: TimeRanges,
    dias: String,
    grupo: Boolean,
    grado: Boolean,
    docente: String,
    materia: String,
    Ciclo: String,
},
);

const selectedTime = ref(props.hora);

const close = () => {
    emit('close');
    form.reset;
};

const form = useForm({
    idClase: props.clases.idClase,
    clase: props.clases.clase,
    hora: props.clases.hora,
    dias: props.clases.dias,
    grupo: props.clases.grupo,
    grado: props.clases.grado,
    docente: props.clases.docente,
    materia: props.clases.materia,
    hora: props.hora,
    ciclo: props.clases.ciclo,
    dias: []
});

const save = () => {
    form.post(route('admin.addClases'), {
        onSuccess: () => close()
    });
}

const update = () => {
    var idClase = document.getElementById('idClase2').value;
    console.log(idClase);
    console.log(document.getElementById('clase2').value);
    form.put(route('admin.actualizarClases', idClase), {
        onSuccess: () => close()
    });
}
watch(() => props.clases, (newVal) => {
    form.idClase = newVal.idClase;
    form.clase = newVal.clase;
    form.hora = newVal.hora;
    form.dias= newVal.dias;
    form.grupo= newVal.grupo;
    form.grado= newVal.grado;
    form.docente= newVal.docente;
    form.materia= newVal.materia;
    form.ciclo= newVal.ciclo;
}, { deep: true });

</script>


<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="mt-2 bg-white p-4 shadow rounded-lg">

            <form @submit.prevent="(op === '1' ? save() : update())">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">{{ title }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Rellene todos los campos para poder registrar una nueva clase </p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-1 md:col-span-2" hidden> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="idClase" class="block text-sm font-medium leading-6 text-gray-900">id</label>
                            <div class="mt-2">
                                <input type="number" name="idClase" v-model="form.idMateria" placeholder="Ingrese id"
                                    :id="'idClase' + op"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="sm:col-span-1 md:col-span-2"> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="clase" class="block text-sm font-medium leading-6 text-gray-900">Clase</label>
                            <div class="mt-2">
                                <input type="text" name="clase" :id="'clase' + op" v-model="form.clase"
                                    placeholder="Ingrese la clase"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-2"> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="hora" class="block text-sm font-medium leading-6 text-gray-900">Hora</label>
                            <div class="mt-2">
                                <input type="TimeRanges" name="hora" :id="'hora' + op" v-model="form.hora"
                                    placeholder="Ingrese la hora"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-2"> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="dias" class="block text-sm font-medium leading-6 text-gray-900">Días</label>
                            <div class="mt-2">
                                <input type="text" name="dias" :id="'dias' + op" v-model="form.dias"
                                    placeholder="Ingrese los días"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-2">
                            <label for="grupo" class="block text-sm font-medium leading-6 text-gray-900">Grupo</label>
                            <div class="mt-2">
                                <select name="grupo" v-model="form.grupo" :id="'grupo' + op"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione el grupo</option>
                                    <option>A</option>
                                    <option>B</option>
                                    <option>C</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-2">
                            <label for="grado" class="block text-sm font-medium leading-6 text-gray-900">Grado</label>
                            <div class="mt-2">
                                <select name="grado" v-model="form.grado" :id="'grado' + op"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione el grado</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-3">
                            <label for="docente" class="block text-sm font-medium leading-6 text-gray-900">Docente</label>
                            <div class="mt-2">
                                <select name="docente" v-model="form.docente" :id="'docente' + op"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione al docente</option>
                                    <option>Se debe cargar</option>
                                    <option>Se debe cargar</option>
                                    <option>Se debe cargar</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-3">
                            <label for="materia" class="block text-sm font-medium leading-6 text-gray-900">Materia</label>
                            <div class="mt-2">
                                <select name="materia" v-model="form.materia" :id="'materia' + op"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione la materia</option>
                                    <option>Falta materia</option>
                                    <option>Falta materia</option>
                                    <option>Falta materia</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-1 md:col-span-2">
                            <label for="ciclo" class="block text-sm font-medium leading-6 text-gray-900">Ciclo</label>
                            <div class="mt-2">
                                <select name="ciclo" v-model="form.ciclo" :id="'ciclo' + op"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione el ciclo</option>
                                    <option>Falta ciclo</option>
                                    <option>Falta ciclo</option>
                                    <option>Falta ciclo</option>
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