<script setup>
import Modal from '../Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
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
    grados: {
        type: Object,
        default: () => ({}),
    },
    grupos: {
        type: Object,
        default: () => ({}),
    },
    personal: {
        type: Object,
        default: () => ({}),
    },
    materias: {
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
    idGrado: String,
    idGrupo: String,
    idPersonal: String,
    idMateria: String,
    idCiclo: String,
},
);

const selectedTime = ref(props.hora);
const emit = defineEmits(['close']);

const close = () => {
    emit('close');
    form.reset;
};

const form = useForm({
    idClase: props.clases.idClase,
    grados: props.clases.idGrado,
    grados: props.clases.grado,
    grupos: props.clases.idGrupo,
    grupos: props.clases.grupo,
    docentes: props.clases.idPersonal,
    docentes: props.clases.nombre_completo,
    materias: props.clases.idMateria,
    materias: props.clases.materia,
    ciclos: props.clases.idCiclo,//Le agregué la s
    ciclos: props.clases.descripcionCiclo,//Le agregue la s a ciclo
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
    form.grados= newVal.grados;
    form.grupos= newVal.grupos;
    form.docentes= newVal.docentes;
    form.materias= newVal.materias;
    form.ciclos= newVal.ciclos;
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

                        <div class="sm:col-span-3">
                            <label for="grado" class="block text-sm font-medium leading-6 text-gray-900">Grado</label>
                            <div class="mt-2">
                                <select name="grado" :id="'grado' + op" v-model="form.grados"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Selecciona un grado</option>
                                    <option v-for="grado in grados" :key="grado.idGrado" :value="grado.idGrado">
                                        {{ grado.grado }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="grupo" class="block text-sm font-medium leading-6 text-gray-900">Grupo</label>
                            <div class="mt-2">
                                <select name="grupo" :id="'grupo' + op" v-model="form.grupos"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Selecciona un grupo</option>
                                    <option v-for="grupo in grupos" :key="grupo.idGrupo" :value="grupo.idGrupo">
                                        {{ grupo.grupo }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="docente" class="block text-sm font-medium leading-6 text-gray-900">Docente</label>
                            <div class="mt-2">
                                <select name="docente" :id="'docente' + op" v-model="form.docentes"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Selecciona un docente</option>
                                    <option v-for="docente in personal" :key="docente.idPersonal" :value="docente.idPersonal">
                                        {{ docente.nombre_completo }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="materia" class="block text-sm font-medium leading-6 text-gray-900">Materia</label>
                            <div class="mt-2">
                                <select name="materia" :id="'materia' + op" v-model="form.materias"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Selecciona una materia</option>
                                    <option v-for="materia in materias" :key="materia.idMateria" :value="materia.idMateria">
                                        {{ materia.materia }}
                                    </option>
                                </select>
                            </div>
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