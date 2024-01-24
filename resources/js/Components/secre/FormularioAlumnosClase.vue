<script setup>
import Modal from '../Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { onMounted, watch, ref } from 'vue';
import axios from 'axios';

const emit = defineEmits(['close']);

// Variables o propiedades que recibe el formulario
const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '3xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    clases: {
        type: Object,
        default: () => ({}),
    },
    alumnos: {
        type: Object,
        default: () => ({}),
    },
    materias: {
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
    clases_alumnos: {
        type: Object,
        default: () => ({}),
    },
    title: { type: String },
    modal: { type: String },
    op: { type: String },
},
);

const close = async () => {
    emit('close');
    form.reset();
};

const form = useForm({
    idClaseAlumno: props.clases_alumnos.idClaseAlumno,
    clase: props.clases_alumnos.idClase,
    alumno: props.clases_alumnos.idAlumno,
});

// Variables para los mensajes de validación
const claseError = ref('');
const alumnoError = ref('');
const calificacionError = ref('');

watch(() => props.clases_alumnos, async (newVal) => {
    form.idClaseAlumno = newVal.idClaseAlumno;
    form.clase = newVal.idClase;
    form.alumno = newVal.idAlumno;
}, { deep: true }
);


// Validación de los select 
const validateSelect = (selectedValue) => {
    if (selectedValue == undefined) {
        return false;
    }
    return true;
};

const save = async () => {
    claseError.value = validateSelect(form.clase) ? '' : 'Seleccione la clase';
    //  Tipo de sangre
    alumnoError.value = validateSelect(form.alumno) ? '' : 'Seleccione los alumnos';

    if (
        claseError.value || alumnoError.value
    ) {

        return;
    }

    console.log(form);
    await form.post(route('director.addAlumnosClases'), {
        onSuccess: () => {
            close();//le agregué el ;
            claseError.value = '';
            alumnoError.value = '';
        }
    });
}

const getMateria = (idMateria) => {
    const materia = props.materias.find(m => m.idMateria === idMateria);
    return materia ? materia.materia : 'N/A';
};

const getGrado = (idGrado) => {
    const grado = props.grados.find(g => g.idGrado === idGrado);
    return grado ? grado.grado : 'N/A';
};

const getGrupo = (idGrupo) => {
    const grupo = props.grupos.find(g => g.idGrupo === idGrupo);
    return grupo ? grupo.grupo : 'N/A';
};

// Agrega esta función en tu componente FormularioAlumnosClase.vue
const filterAlumnos = () => {
    if (form.clase) {
        const claseSeleccionada = props.clases.find(clase => clase.idClase === form.clase);
        if (claseSeleccionada) {
            const alumnosFiltrados = props.alumnos.filter(alumno => {
                return alumno.idGrado === claseSeleccionada.idGrado && alumno.idGrupo === claseSeleccionada.idGrupo;
            });
            return alumnosFiltrados;
        }
    }
    return [];
};

// Agrega este watcher en tu componente FormularioAlumnosClase.vue
watch(() => form.clase, () => {
    form.alumno = ''; // Reinicia el valor seleccionado al cambiar la clase
});


</script>

<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="mt-2 bg-white p-4 shadow rounded-lg">
            <form @submit.prevent="(op === '1' ? save() : update())">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">{{ title }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Rellene todos los campos para poder registrar a los
                        alumnos a una clase
                    </p>
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        <div class="sm:col-span-5">
                            <label for="clase" class="block text-sm font-medium leading-6 text-gray-900">Clase</label>
                            <div class="mt-2">
                                <select name="clase" :id="'clase' + op" v-model="form.clase"
                                    placeholder="Seleccione la clase"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Seleccione la clase</option>
                                    <option v-for="clase in clases" :key="clase.idClase" :value="clase.idClase">
                                        {{ `${getMateria(clase.idMateria)} - Grado: ${getGrado(clase.idGrado)} - Grupo: ${getGrupo(clase.idGrupo)}` }}
                                    </option>
                                </select>
                            </div>
                            <div v-if="claseError != ''" class="text-red-500 text-xs mt-1">{{ claseError }}</div>
                        </div>

                        <div class="sm:col-span-5">
                            <label for="alumno" class="block text-sm font-medium leading-6 text-gray-900">Alumno(s)</label>
                            <div class="mt-2">
                                <select name="alumno" :id="'alumno' + op" v-model="form.alumno" multiple
                                    placeholder="Seleccione a los alumnos"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Selecciona los alumnos</option>
                                    <option v-for="alumno in filterAlumnos()" :key="alumno.idAlumno"
                                        :value="alumno.idAlumno">
                                        {{ alumno.nombre_completo }}
                                    </option>
                                </select>
                            </div>
                            <div v-if="alumnoError != ''" class="text-red-500 text-xs mt-1">{{ alumnoError }}</div>
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
@import "vue-select/dist/vue-select.css";
</style>