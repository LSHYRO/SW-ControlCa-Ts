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
    aviso: {
        type: Object,
        default: () => ({}),
    },
    title: { type: String },
    modal: { type: String },
    op: { type: String },
    descripcion: { type: String }
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
const tituloError = ref('');
const descripcionError = ref('');
const fechaHoraInicioError = ref('');
const fechaHoraFinError = ref('');
const lugarError = ref('');
const fechaRealizacionError = ref('');
/////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////
// Funciones para la validacion del nombre de la materia y 
// descripcion

// Validacion de cadenas no vacias
const validateStringNotEmpty = (value) => {
    return typeof value === 'string' && value.trim() !== '';
}

const validateDate = (date) => {
    return date !== null && date !== undefined && date !== '' && validarFechasT();
};

const validateDate2 = (date) => {
    const now = new Date().toISOString().split('.')[0];
    return date !== null && date !== undefined && date >= now;
}

const validarFechasT = () => {
     return form.fechaHoraFin >= form.fechaHoraInicio;
}
////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////
 // Actualizacion del formulario al editar el objeto
const form = useForm({
    idAviso: props.aviso.idAviso,
    titulo: props.aviso.titulo,
    descripcion: props.aviso.descripcion,
    fechaRealizacion: props.aviso.fechaRealizacion,
    lugar: props.aviso.lugar,
    fechaHoraInicio: props.aviso.fechaHoraInicio,
    fechaHoraFin: props.aviso.fechaHoraFin,
});
////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////
 // Guardar el aviso, en dado caso que la informacion no se 
 // valide, no avanza (No guarda el aviso) y muestra los 
 // mensajes
const save = () => {
    tituloError.value = validateStringNotEmpty(form.titulo) ? '' : 'Ingrese el nombre de la materia';
    descripcionError.value = validateStringNotEmpty(form.descripcion) ? '' : 'Ingrese la descripcion de la materia';
    fechaHoraInicioError.value = validateDate (form.fechaHoraInicio) ? '' : 'Ingrese la fecha correcta';
    fechaHoraFinError.value = validateDate (form.fechaHoraFin) ? '' : 'Ingrese la fecha correcta';
    fechaRealizacionError.value = validateDate2 (form.fechaRealizacion) ? '' : 'Ingrese la fecha correcta';
    lugarError.value = validateStringNotEmpty(form.lugar) ? '' : 'Ingrese el lugar';

    if (tituloError.value || descripcionError.value || fechaHoraInicioError.value || fechaHoraFinError.value || fechaRealizacionError.value || lugarError.value ) {
        return;
    }

    form.post(route('secre.agregarAv'), {
        onSuccess: () => {
            close()
            tituloError.value = '';
            descripcionError.value = '';
            fechaHoraInicioError.value = '';
            fechaHoraFinError.value = '';
            fechaRealizacionError.value = '';
            lugarError.value = '';
        }
    });
}
////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////
 // Actualiza la información de la materia, en dado caso que la 
 // informacion no se valide, no avanza (No guarda la materia) 
 // y muestra los mensajes
const update = () => {
    tituloError.value = validateStringNotEmpty(form.titulo) ? '' : 'Ingrese el nombre de la materia';
    descripcionError.value = validateStringNotEmpty(form.descripcion) ? '' : 'Ingrese la descripcion de la materia';
    fechaHoraInicioError.value = validateDate (form.fechaHoraInicio) ? '' : 'Ingrese la fecha correcta';
    fechaHoraFinError.value = validateDate (form.fechaHoraFin) ? '' : 'Ingrese la fecha correcta';
    fechaRealizacionError.value = validateDate2 (form.fechaRealizacion) ? '' : 'Ingrese la fecha correcta';
    lugarError.value = validateStringNotEmpty(form.lugar) ? '' : 'Ingrese el lugar';

    if (tituloError.value || descripcionError.value || fechaHoraInicioError.value || fechaHoraFinError.value || fechaRealizacionError.value || lugarError.value ) {
        return;
    }

    var idAviso = document.getElementById('idAviso2').value;
    form.put(route('secre.actualizarAv'), {
        onSuccess: () => {
            close()
            tituloError.value = '';
            descripcionError.value = '';
            fechaHoraInicioError.value = '';
            fechaHoraFinError.value = '';
            fechaRealizacionError.value = '';
            lugarError.value = '';
        }
    });
}
////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////
 // Observa la propíedad materias (si materia tiene algun valor)
 // y actualiza los datos
watch(() => props.aviso, (newVal) => {
    console.log(props.aviso);
    console.log(newVal);
    form.idAviso = newVal.idAviso;
    form.titulo = newVal.titulo;
    form.descripcion = newVal.descripcion;
    form.fechaHoraInicio = newVal.fechaHoraInicio;
    form.fechaHoraFin = newVal.fechaHoraFin;
    form.lugar = newVal.lugar;
    form.fechaRealizacion = newVal.fechaRealizacion;
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
                    <h2 class="text-base font-semibold leading-7 text-gray-900">{{ props.title }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600"> {{ props.descripcion }} </p>
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-1 md:col-span-2" hidden> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="idMateria" class="block text-sm font-medium leading-6 text-gray-900">idAviso</label>
                            <div class="mt-2">
                                <input type="number" name="idAviso" v-model="form.idAviso" placeholder="Ingrese id"
                                    :id="'idAviso' + op"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="sm:col-span-1 md:col-span-6"> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="titulo" class="block text-sm font-medium leading-6 text-gray-900">Titulo/Asunto</label>
                            <div class="mt-2">
                                <input type="text" name="titulo" :id="'titulo' + op" v-model="form.titulo" placeholder="Ingrese el titulo/asunto del aviso"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
                            <!--  //Div para mostrar el mensaje de validación                                                                    -->
                            <div v-if=" tituloError != ''" class="text-red-500 text-xs mt-1">{{  tituloError }}</div>
                            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
                        </div>
                        <div class="sm:col-span-1 md:col-span-6">
                            <label for="descripcion"
                                class="block text-sm font-medium leading-6 text-gray-900">Descripción</label>
                            <div class="mt-2">
                                <textarea type="text" name="descripcion" :id="'descripcion' + op" v-model="form.descripcion"
                                    placeholder="Ingrese la descripción del aviso"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 resize-none">
                                </textarea>
                            </div>
                            <div v-if=" descripcionError != ''" class="text-red-500 text-xs mt-1">{{ descripcionError }}</div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="fechaRealizacion" class="block text-sm font-medium leading-6 text-gray-900">Fecha de realización</label>
                            <div class="mt-2">
                                <input type="datetime-local" name="fechaRealizacion" :id="'fechaRealizacion' + op" v-model="form.fechaRealizacion"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <div v-if=" fechaRealizacionError != ''" class="text-red-500 text-xs mt-1">{{ fechaRealizacionError }}</div>
                        </div>
                        <div class="sm:col-span-1 md:col-span-4"> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="lugar" class="block text-sm font-medium leading-6 text-gray-900">Lugar</label>
                            <div class="mt-2">
                                <input type="text" name="lugar" :id="'lugar' + op" v-model="form.lugar" placeholder="Ingrese el lugar"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
                            <!--  //Div para mostrar el mensaje de validación                                                                    -->
                            <div v-if=" lugarError != ''" class="text-red-500 text-xs mt-1">{{  lugarError }}</div>
                            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
                        </div>
                        <div class="sm:col-span-2">
                            <label for="fechaHoraInicio" class="block text-sm font-medium leading-6 text-gray-900">Fecha de inicio</label>
                            <div class="mt-2">
                                <input type="datetime-local" name="fechaHoraInicio" :id="'fechaHoraInicio' + op" v-model="form.fechaHoraInicio"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <div v-if=" fechaHoraInicioError != ''" class="text-red-500 text-xs mt-1">{{ fechaHoraInicioError }}</div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="fechaHoraFin" class="block text-sm font-medium leading-6 text-gray-900">Fecha de terminación</label>
                            <div class="mt-2">
                                <input type="datetime-local" name="fechaHoraFin" :id="'fechaHoraFin' + op" v-model="form.fechaHoraFin"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <div v-if=" fechaHoraFinError != ''" class="text-red-500 text-xs mt-1">{{ fechaHoraFinError }}</div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <button type="button" :id="'cerrar' + op" class="text-sm font-semibold leading-6 text-gray-900"
                        data-bs.dismiss="modal" @click="close()">Cancelar</button>
                    <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded"
                        :disabled="form.processing"> <i class="fa-solid fa-floppy-disk mr-2"></i>Guardar</button>
                </div>
            </form>
        </div>
    </Modal>
</template>