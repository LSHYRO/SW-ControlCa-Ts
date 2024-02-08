<script setup>
import Modal from '../Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

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
    clase: {
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
},
);

const emit = defineEmits(['close']);

/* const grupos = ref([]);
const ciclos = ref([]); */

const close = () => {
    emit('close');
    form.reset();
    try {
        const grupoS = document.getElementById('grupo' + props.op);
        grupoS.setAttribute("disabled", "");
        const cicloS = document.getElementById('ciclo' + props.op);
        cicloS.setAttribute("disabled", "");
    } catch (e) {
        console.log(e);
    }
};

const form = useForm({
    idClase: props.clase.idClase,
    grados: props.clase.idGrado,
    //grados: props.clase.grado,
    grupos: props.clase.idGrupo,
    //grupos: props.clase.grupo,
    personal: props.clase.personalP,
    //personal: props.clases.nombre_completo,
    materias: props.clase.idMateria,
    //materias: props.clases.materia,
    ciclos: props.clase.idCiclo,//Le agregué la s
    //ciclos: props.clases.descripcionCiclo,//Le agregue la s a ciclo
    tipoPersonal: props.personal.id_tipo_personal,
});

// Variables para los mensajes de validación
const gradoError = ref('');
const grupoError = ref('');
const personalError = ref('');
const materiaError = ref('');
const ciclosError = ref('');

// Validación del select 
const validateSelect = (selectedValue) => {
    if (selectedValue == undefined) {
        return false;
    }
    return true;
};

const save = () => {
    gradoError.value = validateSelect(form.grados) ? '' : 'Seleccione el grado';
    grupoError.value = validateSelect(form.grupos) ? '' : 'Seleccione el grupo';
    personalError.value = validateSelect(form.personal) ? '' : 'Seleccione el docente';
    materiaError.value = validateSelect(form.materias) ? '' : 'Seleccione la materia';
    ciclosError.value = validateSelect(form.ciclos) ? '' : 'Seleccione el ciclo';

    if (
        gradoError.value || grupoError.value || personalError.value || materiaError.value || ciclosError.value
    ) {

        return;
    }
    console.log("Llame al metodo");
    try {
        console.log(form);
        form.post(route('director.addClases'), {
            onSuccess: () => {
                close()
                gradoError.value = '';
                grupoError.value = '';
                personalError.value = '';
                materiaError.value = '';
                ciclosError.value = '';
            }
        });
    } catch (error) {
        console.log("wasaa " + error)
    }
    console.log("Llame al metodo2");
}

const update = () => {
    console.log("Entró en update");
    gradoError.value = validateSelect(form.grados) ? '' : 'Seleccione el grado';
    grupoError.value = validateSelect(form.grupos) ? '' : 'Seleccione el grupo';
    personalError.value = validateSelect(form.personal) ? '' : 'Seleccione el docente';
    materiaError.value = validateSelect(form.materias) ? '' : 'Seleccione la materia';
    ciclosError.value = validateSelect(form.ciclos) ? '' : 'Seleccione el ciclo';

    if (
        gradoError.value || grupoError.value || personalError.value || materiaError.value || ciclosError.value
    ) {

        return;
    }
    var idClase = document.getElementById('idClase2').value;
    form.put(route('director.actualizarClases', idClase), { //Está mal la ruta
        onSuccess: () => {
            close()
            gradoError.value = '';
            grupoError.value = '';
            personalError.value = '';
            materiaError.value = '';
            ciclosError.value = '';
        }
    });
}

watch(() => props.clase, (newVal) => {
    console.log("Entré en watch");
    console.log(newVal); // Verifica que props.clases tenga valores
    form.idClase = newVal.idClase;
    form.grados = newVal.idGrado;
    form.grupos = newVal.idGrupo;
    form.personal = newVal.personalP;
    form.materias = newVal.idMateria;
    form.ciclos = newVal.idCiclo;
}, { deep: true });

/* watch(() => form.grados, async () => {
    await obtenerGruposXGrado();
    await obtenerCicloXGrado();
})

const obtenerGruposXGrado = async () => {
    const grupoS = document.getElementById('grupo' + props.op);
    grupoS.removeAttribute("disabled");
    try {
        const idGrado = form.grados.idGrado;
        const response = await axios.get(route('director.gradosXgrupos', idGrado));        
        grupos.value = await response.data;
    } catch (error) {
        console.log('Error al obtener grupos: ', error);
    }
};

const obtenerCicloXGrado = async () => {
    const cicloS = document.getElementById('ciclo' + props.op);
    cicloS.removeAttribute("disabled");
    try {
        const idGrado = form.grados.idGrado;
        const response2 = await axios.get(route('director.cicloXgrupos', idGrado));
        console.log(response2.data);
        ciclos.value = await response2.data;
    } catch (error) {
        console.log(response2);
        console.log('Error al obtener ciclos: ', error);
    }
} */


</script>


<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="mt-2 bg-white p-4 shadow rounded-lg">
            <form @submit.prevent="(op === '1' ? save() : update())"
                @keydown.enter.prevent="(op === '1' ? save() : update())">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">{{ title }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Rellene todos los campos para poder registrar una nueva
                        clase </p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-1 md:col-span-2" hidden> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="idClase" class="block text-sm font-medium leading-6 text-gray-900">id</label>
                            <div class="mt-2">
                                <input type="number" name="idClase" v-model="form.idClase" placeholder="Ingrese id"
                                    :id="'idClase' + op"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="grado" class="block text-sm font-medium leading-6 text-gray-900">Grado</label>
                            <div class="mt-2">
                                <select name="grado" :id="'grado' + op" v-model="form.grados"
                                    placeholder="Seleccione el grado"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Seleccione un grado</option>
                                    <option v-for="grado in grados" :key="grado.idgrado" :value="grado.idGrado">
                                        {{ grado.grado }}
                                    </option>
                                </select>
                            </div>
                            <div v-if="gradoError != ''" class="text-red-500 text-xs mt-1">{{ gradoError }}</div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="grupo" class="block text-sm font-medium leading-6 text-gray-900">Grupo</label>
                            <div class="mt-2">
                                <select name="grupo" :id="'grupo' + op" v-model="form.grupos"
                                    placeholder="Seleccione el grupo"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Seleccione un grupo</option>
                                    <option v-for="grupo in props.grupos" :key="grupo.idGrupo" :value="grupo.idGrupo">
                                        {{ grupo.grupo }}
                                    </option>
                                </select>
                            </div>
                            <div v-if="grupoError != ''" class="text-red-500 text-xs mt-1">{{ grupoError }}</div>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="docente" class="block text-sm font-medium leading-6 text-gray-900">Docente</label>
                            <div class="mt-2">
                                <!--
                                <select name="docente" :id="'docente' + op" v-model="form.personal"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Selecciona un docente</option>
                                    <option v-for="docente in personal" :key="docente.idPersonal"
                                        :value="docente.idPersonal">
                                        {{ docente.nombre_completo }}
                                    </option>
                                </select>
                                -->
                                <v-select type="text" name="docente" label="nombre_completo" placeholder="Ingrese el nombre del docente"
                                    :options="props.personal" v-model="form.personal" :id="'docente' + op" 
                                    :minimum-input-length="1" :filterable="true" modelValue="idPersonal" modelProp="idPersonal"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                            <div v-if="personalError != ''" class="text-red-500 text-xs">{{ personalError }}</div>
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
                            <div v-if="materiaError != ''" class="text-red-500 text-xs">{{ materiaError }}</div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="ciclo" class="block text-sm font-medium leading-6 text-gray-900">Ciclo</label>
                            <div class="mt-2">
                                <select name="ciclo" :id="'ciclo' + op" v-model="form.ciclos" 
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Selecciona un ciclo</option>
                                    <option v-for="ciclo in props.ciclos" :key="ciclo.idCiclo" :value="ciclo.idCiclo">
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
<style>
@import "vue-select/dist/vue-select.css";
</style>