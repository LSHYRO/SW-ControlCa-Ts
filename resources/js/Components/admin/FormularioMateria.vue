<script setup>
////////////////////////////////////////////////////////////////
 // Importaciones necesarias para el funcionamiento del 
 // formulario
import Modal from '../Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { watch, ref } from 'vue';
const emit = defineEmits(['close']);
////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////
 // Propiedades que recibirá el formulario 
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
},
);
////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////
 // Funcion para cerrar el formulario
const close = () => {
    emit('close');
    form.reset();
};
////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////
// Variables para mensajes de validaciones
const nombreMateriaError = ref('');
const descripcionMateriaError = ref('');
////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////
// Funciones para la validacion del nombre de la materia y 
// descripcion

// Validacion de cadenas no vacias
const validateStringNotEmpty = (value) => {
    return typeof value === 'string' && value.trim() !== '';
}
////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////
 // Actualizacion del formulario al editar el objeto
const form = useForm({
    idMateria: props.materias.idMateria,
    materia: props.materias.materia,
    descripcion: props.materias.descripcion,
    esTaller: props.materias.esTaller,
    esTaller: props.materias.esTaller,
});
////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////
 // Guardar la materia, en dado caso que la informacion no se 
 // valide, no avanza (No guarda la materia) y muestra los 
 // mensajes
const save = () => {
    nombreMateriaError.value = validateStringNotEmpty(form.materia) ? '' : 'Ingrese el nombre de la materia';
    descripcionMateriaError.value = validateStringNotEmpty(form.descripcion) ? '' : 'Ingrese la descripcion de la materia';

    if (nombreMateriaError.value || descripcionMateriaError.value) {
        return;
    }

    form.post(route('admin.addMaterias'), {
        onSuccess: () => {
            close()
            nombreMateriaError.value = '';
            descripcionMateriaError.value = '';
        }
    });
}
////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////
 // Actualiza la información de la materia, en dado caso que la 
 // informacion no se valide, no avanza (No guarda la materia) 
 // y muestra los mensajes
const update = () => {
    nombreMateriaError.value = validateStringNotEmpty(form.materia) ? '' : 'Ingrese el nombre de la materia';
    descripcionMateriaError.value = validateStringNotEmpty(form.descripcion) ? '' : 'Ingrese la descripcion de la materia';

    if (nombreMateriaError.value || descripcionMateriaError.value) {
        return;
    }

    var idMateria = document.getElementById('idMateria2').value;
    console.log(idMateria);
    console.log(document.getElementById('materia2').value);
    form.put(route('admin.actualizarMaterias', idMateria), {
        onSuccess: () => {
            close()
            nombreMateriaError.value = '';
            descripcionMateriaError.value = '';
        }
    });
}
////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////
 // Observa la propíedad materias (si materia tiene algun valor)
 // y actualiza los datos
watch(() => props.materias, (newVal) => {
    form.idMateria = newVal.idMateria;
    form.materia = newVal.materia;
    form.descripcion = newVal.descripcion;
    form.esTaller = newVal.esTaller;
}, { deep: true });
////////////////////////////////////////////////////////////////
</script>

<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="mt-2 bg-white p-4 shadow rounded-lg">
            <!-- //////////////////////////////////////////////////////////////// -->
            <!--  // Dependiendo del valor de op (operación guardar o actualizar) -->
            <!--  // el formulario servira para editar la información de la       -->
            <!--  // materia o crear una nueva                                    -->
            <form @submit.prevent="(op === '1' ? save() : update())"
                @keydown.enter.prevent="(op === '1' ? save() : update())">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">{{ title }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Rellene todos los campos para poder registrar una nueva
                        materia o editar su información</p>
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
                                <input type="text" name="materia" :id="'materia' + op" v-model="form.materia" placeholder="Ingrese el nombre de la materia"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <div v-if=" nombreMateriaError != ''" class="text-red-500 text-xs mt-1">{{  nombreMateriaError }}</div>
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
                            <div v-if=" descripcionMateriaError != ''" class="text-red-500 text-xs mt-1">{{ descripcionMateriaError }}</div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="esTaller" class="block text-sm font-medium leading-6 text-gray-900">¿Taller?</label>
                            <div class="mt-2">
                                <input type="checkbox" name="esTaller" :id="'esTaller' + op" :checked="form.esTaller"
                                    @change="form.esTaller = !form.esTaller">
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