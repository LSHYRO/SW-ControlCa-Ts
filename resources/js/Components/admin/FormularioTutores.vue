<script setup>
////////////////////////////////////////////////////////////////////////////////////////////////
// Importaciones necesarias para el funcionamiento del formulario
import Modal from '../Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { onMounted, watch, ref } from 'vue';
const emit = defineEmits(['close']);
import axios from 'axios';
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
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
    tutor: {
        type: Object,
        default: () => ({}),
    },
    generos: {
        type: Object,
        default: () => ({}),
    },
    title: { type: String },
    modal: { type: String },
    op: { type: String },
},
);
//////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////
// Constantes para cargar los estados, municipios y asentamientos
const estados = ref([]);
const municipios = ref([]);
const asentamientos = ref([]);
//////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////
// Función para cerrar el formulario
const close = () => {
    emit('close');
    form.reset();
};
//////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////
// Función para actualizar los datos del formulario con la variable
// tutor que recibe el formulario
const form = useForm({
    idTutor: props.tutor.idTutor,
    nombre: props.tutor.nombre,
    apellidoP: props.tutor.apellidoP,
    apellidoM: props.tutor.apellidoM,
    correoElectronico: props.tutor.correoElectronico,
    numTelefono: props.tutor.numTelefono,
    genero: props.tutor.idGenero,
    calle: props.tutor.calle,
    numero: props.tutor.numero,
    codigoPostal: props.tutor.codigoPos,
    estado: props.tutor.idEstado,
    municipio: props.tutor.idMunicipio,
    asentamiento: props.tutor.idAsentamiento,
    idDomicilio: props.tutor.idDireccion,
    idUsuario: props.tutor.idUsuario,

});
//////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////
// Variables para los mensajes de validación
const nombreError = ref('');
const apellidoPError = ref('');
const apellidoMError = ref('');
const correoEError = ref('');
const numeroTError = ref('');
const codigoPError = ref('');
const calleError = ref('');
const numeroCError = ref('');
const generoError = ref('');
//////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////
// Validación del correo a través de una expresion regular 
const validateEmail = (email) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
};

// Validación de cadenas no vacias
const validateStringNotEmpty = (value) => {
    return typeof value === 'string' && value.trim() !== '';
}

// Validación de numero telefonico a traves de espresion regular
const validateNumeroTelefono = (numeroTelefono) => {
    const numeroTExpReg = /^\d{10}$/;
    return numeroTExpReg.test(numeroTelefono);
};

// Validación de codigo postal a traves de expresion regular
const validateCodigoPostal = (codigoPostal) => {
    const codigoPostalRegex = /^\d{5}$/;
    return codigoPostalRegex.test(codigoPostal);
};

// Validación de numero de casa
const validateNumeroCasa = (numeroCasa) => {
    // Aquí puedes ajustar la expresión regular según tus requisitos
    const numeroCasaRegex = /^\d+$/;
    return numeroCasaRegex.test(numeroCasa);
};

// Validación de los select 
const validateSelect = (selectedValue) => {
    if (selectedValue == undefined) {
        return false;
    }
    return true;
};
//////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////
// Función para crear (guardar) un nuevo profesor, donde primero se
// se realizan las validaciones y dependiendo del resultado se 
// guarda o se muestran los mensajes de error
const save = () => {
    // Validacion de nombre
    nombreError.value = validateStringNotEmpty(form.nombre) ? '' : 'Ingrese el nombre';
    apellidoPError.value = validateStringNotEmpty(form.apellidoP) ? '' : 'Ingrese el apellido paterno';
    apellidoMError.value = validateStringNotEmpty(form.apellidoM) ? '' : 'Ingrese el apellido materno';
    // Validacion de correo electronico
    correoEError.value = validateEmail(form.correoElectronico) ? '' : 'Correo electronico no valido';
    // Validacion de codigo postal 
    codigoPError.value = validateCodigoPostal(form.codigoPostal) ? '' : 'Ingrese el codigo postal';
    // Validacion de numero de telefono
    numeroTError.value = validateNumeroTelefono(form.numTelefono) ? '' : 'Ingrese el numero telefono';
    // Validacion de la calle
    calleError.value = validateStringNotEmpty(form.calle) ? '' : 'Ingrese la calle';
    // Validacion de numero de casa
    numeroCError.value = validateNumeroCasa(form.numero) ? '' : 'Ingrese el numero de casa';
    // Validacion de los select
    //  Genero
    generoError.value = validateSelect(form.genero) ? '' : 'Seleccione el genero';

    if (
        nombreError.value || apellidoMError.value || apellidoPError.value ||
        correoEError.value || codigoPError.value || numeroTError.value || calleError.value ||
        numeroCError.value || generoError.value 
    ) {

        return;
    }

    form.post(route('admin.addTutores'), {
        onSuccess: () => {
            close()
            nombreError.value = '';
            apellidoMError.value = '';
            apellidoPError.value = '';
            correoEError.value = '';
            codigoPError.value = '';
            numeroTError.value = '';
            calleError.value = '';
            numeroCError.value = '';
            generoError.value = '';
        }
    });
}
//////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////
// Función para modificar (actualizar) un profesor, donde primero se
// se realizan las validaciones y dependiendo del resultado se 
// guarda o se muestran los mensajes de error
const update = () => {
    // Validacion de nombre
    nombreError.value = validateStringNotEmpty(form.nombre) ? '' : 'Ingrese el nombre';
    apellidoPError.value = validateStringNotEmpty(form.apellidoP) ? '' : 'Ingrese el apellido paterno';
    apellidoMError.value = validateStringNotEmpty(form.apellidoM) ? '' : 'Ingrese el apellido materno';
    // Validacion de correo electronico
    correoEError.value = validateEmail(form.correoElectronico) ? '' : 'Correo electronico no valido';
    // Validacion de codigo postal 
    codigoPError.value = validateCodigoPostal(form.codigoPostal) ? '' : 'Ingrese el codigo postal';
    // Validacion de numero de telefono
    numeroTError.value = validateNumeroTelefono(form.numTelefono) ? '' : 'Ingrese el numero telefono';
    // Validacion de la calle
    calleError.value = validateStringNotEmpty(form.calle) ? '' : 'Ingrese la calle';
    // Validacion de numero de casa
    numeroCError.value = validateNumeroCasa(form.numero) ? '' : 'Ingrese el numero de casa';
    // Validacion de los select
    //  Genero
    generoError.value = validateSelect(form.genero) ? '' : 'Seleccione el genero';

    if (
        nombreError.value || apellidoMError.value || apellidoPError.value || correoEError.value || 
        codigoPError.value || numeroTError.value || calleError.value || numeroCError.value || 
        generoError.value
    ) {

        return;
    }

    var idTutor = document.getElementById('idTutor2').value;
    form.put(route('admin.actualizarTutor', idTutor), {
        onSuccess: () => {
            close()
            nombreError.value = '';
            apellidoMError.value = '';
            apellidoPError.value = '';
            correoEError.value = '';
            codigoPError.value = '';
            numeroTError.value = '';
            calleError.value = '';
            numeroCError.value = '';
            generoError.value = '';            
        }
    });
}
//////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////
// Observa la variable personal que se le pasa al formulario, en dado
// donde al cambiar los datos se actualiza el formulario
watch(() => props.tutor, (newVal) => {
    form.idTutor = newVal.idTutor;
    form.nombre = newVal.nombre;
    form.apellidoP = newVal.apellidoP;
    form.apellidoM = newVal.apellidoM;
    form.correoElectronico = newVal.correoElectronico;
    form.numTelefono = newVal.numTelefono;
    form.genero = newVal.idGenero;
    form.calle = newVal.calle;
    form.numero = newVal.numero;
    form.codigoPostal = newVal.codigoPos;
    form.estado = newVal.idEstado;
    form.municipio = newVal.idMunicipio;
    form.asentamiento = newVal.idAsentamiento;
    form.idDomicilio = newVal.idDireccion;
    form.idUsuario = newVal.idUsuario;
}, { deep: true }
);
//////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////
// Constante con función para cargar los municipios dependiendo de
// la selección del select estado
const cargarMunicipios = async () => {
    try {
        var idEstado = form.estado;
        const response = await axios.get(route('consMunicipiosXIdEstado', idEstado));
        municipios.value = response.data;
    } catch (error) {
        console.error('Error al obtener municipios:', error);
    }
};
//////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////
// Constante con función para cargar los asentamientos dependiendo de
// la selección del select municipio
const cargarAsentamientos = async () => {
    try {
        var idMunicipio = form.municipio;
        const response = await axios.get(route('consAsentamientosXIdMunicipio', idMunicipio));
        asentamientos.value = await response.data;
    } catch (error) {
        console.error('Error al obtener asentamientos:', error);
    }
};
//////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////
// Constante con función para cargar los datos de estado, municipio
// y asentamientos dependiendo del codigo postal que se coloque en
// el formulario
const buscarDatosXCodigoPostal = async () => {
    try {
        var codigoPostal = form.codigoPostal;
        if (validateCodigoPostal(codigoPostal)) {
            const response = await axios.get(route('consDatosXCodigoPostal', codigoPostal));
            const datos = response.data;
            console.log(datos);
            if (datos.length <= 0) {
                codigoPError.value = 'Codigo postal no existente';
                return;
            }
            if (datos.estado) {
                form.estado = await datos.estado.idEstado;
                await cargarMunicipios();
                
            }
            if (datos.municipio) {
                form.municipio = await datos.municipio.idMunicipio;
                await cargarAsentamientos();
                form.asentamiento = await asentamientos.value[0]?.idAsentamiento;
            }
            codigoPError.value = '';
        } else {
            codigoPError.value = 'Ingrese el codigo postal completo';
        }
    } catch (error) {
        console.error('Error al obtener datos por código postal:', error);
    }
};
//////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////
// Funcion onMounted para al rellenar los datos del select estado
onMounted(async () => {
    try{
    const response = await axios.get(route('consEstados'));
    estados.value = response.data;
    form.estado = estados.value[19]?.idEstado;
    await cargarMunicipios();
    form.municipio = municipios.value[0]?.idMunicipio;
    await cargarAsentamientos();
    form.asentamiento = asentamientos.value[0]?.idAsentamiento;
    }catch(error){
        console.log("Error generado en onMounted: " + error);
    }
    console.log(props.tutor);
});
//////////////////////////////////////////////////////////////////////
</script>

<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="mt-2 bg-white p-4 shadow rounded-lg">
            <form @submit.prevent="(op === '1' ? save() : update())">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">{{ title }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Rellene todos los campos para poder registrar a un nuevo
                        tutor o actualizar los datos
                    </p>
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-1 md:col-span-2" hidden> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="idTutor" class="block text-sm font-medium leading-6 text-gray-900">idTutor</label>
                            <div class="mt-2">
                                <input type="number" name="idTutor" v-model="form.idTutor" placeholder="Ingrese id del tutor"
                                    :id="'idTutor' + op"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="sm:col-span-1 md:col-span-2"> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="apellidoP" class="block text-sm font-medium leading-6 text-gray-900">Apellido
                                Paterno</label>
                            <div class="mt-2">
                                <input type="text" name="apellidoP" :id="'apellidoP' + op" v-model="form.apellidoP"
                                    placeholder="Ingrese el apellido paterno"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <!-- //////////////////////////////////////////////////////////////////////////////////////////////// -->
                            <!--  // Div para mostrar las validaciones en dado caso que no sean correctas -->
                            <div v-if="apellidoPError != ''" class="text-red-500 text-xs mt-1">{{ apellidoPError }}</div>
                            <!-- //////////////////////////////////////////////////////////////////////////////////////////////// -->
                        </div>
                        <div class="sm:col-span-1 md:col-span-2">
                            <label for="apellidoM" class="block text-sm font-medium leading-6 text-gray-900">Apellido
                                Materno</label>
                            <div class="mt-2">
                                <input type="text" name="apellidoM" :id="'apellidoM' + op" v-model="form.apellidoM"
                                    placeholder="Ingrese el apellido materno"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <div v-if="apellidoMError != ''" class="text-red-500 text-xs mt-1">{{ apellidoMError }}</div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="nombre" class="block text-sm font-medium leading-6 text-gray-900">Nombres</label>
                            <div class="mt-2">
                                <input type="text" name="nombre" :id="'nombre' + op" v-model="form.nombre"
                                    placeholder="Ingrese el nombre"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <div v-if="nombreError != ''" class="text-red-500 text-xs mt-1">{{ nombreError }}</div>
                        </div>
                        <div class="sm:col-span-4 ">
                            <label for="correoElectronico" class="block text-sm font-medium leading-6 text-gray-900">Correo
                                electrónico</label>
                            <div class="mt-2">
                                <input :id="'correoElectronico' + op" name="correoElectronico" type="correoElectronico"
                                    v-model="form.correoElectronico" placeholder="Ingrese el correo electronico"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <div v-if="correoEError != ''" class="text-red-500 text-xs mt-1">{{ correoEError }}</div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="numTelefono" class="block text-sm font-medium leading-6 text-gray-900">Número de
                                teléfono</label>
                            <div class="mt-2">
                                <input type="tel" :id="'numTelefono' + op" name="numTelefono" v-model="form.numTelefono"
                                    placeholder="Ingrese el numero de telefono"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <div v-if="numeroTError != ''" class="text-red-500 text-xs mt-1">{{ numeroTError }}</div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="genero" class="block text-sm font-medium leading-6 text-gray-900">Genero</label>
                            <div class="mt-2">
                                <select name="genero" :id="'genero' + op" v-model="form.genero"
                                    placeholder="Seleccione el tipo de sangre"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Seleccione un genero</option>
                                    <option v-for="genero in generos" :key="genero.idGenero" :value="genero.idGenero">
                                        {{ genero.genero }}
                                    </option>
                                </select>
                            </div>
                            <div v-if="generoError != ''" class="text-red-500 text-xs mt-1">{{ generoError }}</div>
                        </div>                                                                        
                        <div class="sm:col-span-2">
                            <label for="codigoPostal" class="block text-sm font-medium leading-6 text-gray-900">Código
                                Postal</label>
                            <div class="mt-2">
                                <input type="number" name="codigoPostal" :id="'codigoPostal' + op"
                                    v-model="form.codigoPostal" placeholder="Ingrese el código Postal"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    maxlength="5" minlength="5" @blur="buscarDatosXCodigoPostal">
                            </div>
                            <div v-if="codigoPError != ''" class="text-red-500 text-xs mt-1">{{ codigoPError }}</div>
                        </div>
                        <div class="sm:col-span-3">
                            <label for="estado" class="block text-sm font-medium leading-6 text-gray-900">Estado</label>
                            <div class="mt-2">
                                <select name="estado" :id="'estado' + op" v-model="form.estado" 
                                @change="cargarMunicipios"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Selecciona un estado</option>
                                    <option v-for="estado in estados" :key="estado.idEstado" :value="estado.idEstado">
                                        {{ estado.estado }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <label for="municipio"
                                class="block text-sm font-medium leading-6 text-gray-900">Municipio</label>
                            <div class="mt-2">
                                <select name="municipio" :id="'municipio' + op" v-model="form.municipio"
                                    @change="cargarAsentamientos"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Selecciona un municipio</option>
                                    <option v-for="municipio in municipios" :key="municipio.idMunicipio"
                                        :value="municipio.idMunicipio">
                                        {{ municipio.municipio }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <label for="asentamiento" class="block text-sm font-medium leading-6 text-gray-900">Asentamiento
                                / Localidad</label>
                            <div class="mt-2">
                                <select name="asentamiento" :id="'asentamiento' + op" v-model="form.asentamiento"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Selecciona un asentamiento</option>
                                    <option v-for="asentamiento in asentamientos" :key="asentamiento.idAsentamiento"
                                        :value="asentamiento.idAsentamiento">
                                        {{ asentamiento.asentamiento }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="sm:col-span-4">
                            <label for="calle" class="block text-sm font-medium leading-6 text-gray-900">Calle</label>
                            <div class="mt-2">
                                <input type="text" name="calle" :id="'calle' + op" v-model="form.calle"
                                    placeholder="Ingrese la calle"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <div v-if="calleError != ''" class="text-red-500 text-xs mt-1">{{ calleError }}</div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="numero" class="block text-sm font-medium leading-6 text-gray-900">Número</label>
                            <div class="mt-2">
                                <input type="number" name="numero" :id="'numero' + op" v-model="form.numero"
                                    placeholder="Ingrese el número"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                            <div v-if="numeroCError != ''" class="text-red-500 text-xs mt-1">{{ numeroCError }}</div>
                        </div>
                        <div class="sm:col-span-3" hidden>
                            <label for="idDomicilio"
                                class="block text-sm font-medium leading-6 text-gray-900">idDomicilio</label>
                            <div class="mt-2">
                                <input type="text" name="idDomicilio" :id="'idDomicilio' + op" v-model="form.idDomicilio"
                                    placeholder="Ingrese el Domicilio"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="sm:col-span-3" hidden>
                            <label for="idUsuario"
                                class="block text-sm font-medium leading-6 text-gray-900">idUsuario</label>
                            <div class="mt-2">
                                <input type="text" name="idUsuario" :id="'idUsuario' + op" v-model="form.idUsuario"
                                    placeholder="Ingrese el Usuario"
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