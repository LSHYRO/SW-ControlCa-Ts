<script setup>
import Modal from '../Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { onMounted, watch } from 'vue';
const emit = defineEmits(['close']);
import axios from 'axios';

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
    personal: {
        type: Object,
        default: () => ({}),
    },
    tipoSangre: {
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
    nombre: String,
    apellidoM: String,
    apellidoP: String,
    correoElectronico: String,
    numTelefono: String,
    fechaNacimiento: Date,
    curp: String,
    rfc: String,
    idTipoSangre: String,
    alergias: String,
    discapacidad: String,

},
);

var estados = new Array();

const close = () => {
    emit('close');
    form.reset();
};

const form = useForm({
    idPersonal: props.personal.idPersonal,
    nombre: props.personal.nombre,
    apellidoP: props.personal.apellidoP,
    apellidoM: props.personal.apellidoM,
    correoElectronico: props.personal.correoElectronico,
    numTelefono: props.personal.numTelefono,
    fechaNacimiento: props.personal.fechaNacimiento,
    genero: props.personal.idGenero,
    curp: props.personal.CURP,
    rfc: props.personal.rfc,
    tipoSangre: props.personal.tipoSangre,
    alergias: props.personal.alergias,
    discapacidad: props.personal.discapacidad,
    tipoSangre: props.personal.idTipoSangre,    
    asentamiento: props.personal.idDireccion,
    calle: props.personal.idDireccion,
    numero: props.personal.idDireccion,
});

const save = () => {
    form.post(route('admin.addProfesores'), {
        onSuccess: () => close()
    });
}

const update = () => {
    var idPersonal = document.getElementById('idPersonal2').value;
    console.log(idPersonal);
    console.log(document.getElementById('nombre2').value);
    form.put(route('admin.actualizarProfesores', idPersonal), {
        onSuccess: () => close()
    });
}
watch(() => props.personal, (newVal) => {
    form.idPersonal = newVal.idPersonal;
    form.nombre = newVal.nombre;
    form.apellidoP = newVal.apellidoP;
    form.apellidoM = newVal.apellidoM;
    form.correoElectronico = newVal.correoElectronico;
    form.numTelefono = newVal.numTelefono;
    form.fechaNacimiento = newVal.fechaNacimiento;
    form.curp = newVal.curp;
    form.rfc = newVal.rfc;
    form.tipoSangre = newVal.tipoSangre;
    form.alergias = newVal.alergias;
    form.discapacidad = newVal.discapacidad;
}, { deep: true }
);

var municipios = new Array();
var asentamientos = new Array();

const loadMunicipios = async () => {
    try {
        var idEstado = form.estado;
        const response = await axios.get(route('consMunicipiosXIdEstado', idEstado));
        municipios = response.data;
        //form.municipio = municipios.value[0].idMunicipio; // Seleccionar el primer municipio
        form.municipio = await municipios[0].idMunicipio;
    } catch (error) {
        console.error('Error al obtener municipios:', error);
    }
};

const loadAsentamientos = async () => {
    try {
        var idMunicipio = form.municipio;
        const response = await axios.get(route('consAsentamientosXIdMunicipio', idMunicipio));
        asentamientos = response.data;
        form.asentamiento = asentamientos[0].idAsentamiento; // Seleccionar el primer asentamiento
    } catch (error) {
        console.error('Error al obtener asentamientos:', error);
    }
};

const buscarDatosXCodigoPostal = async () => {
    try {
        var codigoPostal = form.codigoPostal;
        const response = await axios.get(route('consDatosXCodigoPostal', codigoPostal));
        const datos = response.data;
        console.log(datos);
        if (datos.estado) {
            form.estado = datos.estado.idEstado;
        }
        if (datos.municipio) {
            form.municipio = datos.municipio.idMunicipio;
            //loadAsentamientos(); // Esto cargará automáticamente los asentamientos correspondientes
        }
    } catch (error) {
        console.error('Error al obtener datos por código postal:', error);
    }
};


watch(() => form.estado, () => {
    console.log("idEstado = " + form.estado);
    console.log("cargando municipios");
    loadMunicipios();
});

watch(() => form.municipio, () => {
    console.log("idMunicipio = " + form.municipio);
    console.log("cargando asentamientos");
    loadAsentamientos();
});

onMounted(async () => {
    try {
        const response = await axios.get(route('consEstados'));
        estados = response.data;
        console.log(estados);
    } catch (error) {
        // Manejar el error, por ejemplo, mostrar un mensaje al usuario
        console.error('Error al obtener datos: ', error);
    }
    if (estados.length > 0) {
        form.estado = await estados[19].idEstado;
        
    }
});
</script>


<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="mt-2 bg-white p-4 shadow rounded-lg">

            <form @submit.prevent="(op === '1' ? save() : update())">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">{{ title }}</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Rellene todos los campos para poder registrar a un nuevo
                        profesor
                    </p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-1 md:col-span-2" hidden> <!-- Definir el tamaño del cuadro de texto -->
                            <label for="idPersonal" class="block text-sm font-medium leading-6 text-gray-900">id</label>
                            <div class="mt-2">
                                <input type="number" name="idPersonal" v-model="form.idPersonal" placeholder="Ingrese id"
                                    :id="'idPersonal' + op"
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
                        </div>

                        <div class="sm:col-span-1 md:col-span-2">
                            <label for="apellidoM" class="block text-sm font-medium leading-6 text-gray-900">Apellido
                                Materno</label>
                            <div class="mt-2">
                                <input type="text" name="apellidoM" :id="'apellidoM' + op" v-model="form.apellidoM"
                                    placeholder="Ingrese el apellido materno"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="nombre" class="block text-sm font-medium leading-6 text-gray-900">Nombres</label>
                            <div class="mt-2">
                                <input type="text" name="nombre" :id="'nombre' + op" v-model="form.nombre"
                                    placeholder="Ingrese el nombre"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="sm:col-span-4 ">
                            <label for="correoElectronico" class="block text-sm font-medium leading-6 text-gray-900">Correo
                                electrónico</label>
                            <div class="mt-2">
                                <input :id="'correoElectronico' + op" name="correoElectronico" type="correoElectronico"
                                    v-model="form.correoElectronico" placeholder="Ingrese el correo electronico"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="numTelefono" class="block text-sm font-medium leading-6 text-gray-900">Número de
                                teléfono</label>
                            <div class="mt-2">
                                <input type="tel" :id="'numTelefono' + op" name="numTelefono" v-model="form.numTelefono"
                                    placeholder="Ingrese el numero de telefono"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="genero" class="block text-sm font-medium leading-6 text-gray-900">Genero</label>
                            <div class="mt-2">
                                <select name="genero" :id="'genero' + op" v-model="form.genero"
                                    placeholder="Seleccione el tipo de sangre"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option v-for="genero in generos" :key="genero.idGenero"
                                        :value="genero.idGenero">
                                        {{genero.genero}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="fechaNacimiento" class="block text-sm font-medium leading-6 text-gray-900">Fecha de
                                nacimiento</label>
                            <div class="mt-2">
                                <input type="date" name="fechaNacimiento" :id="'fechaNacimiento' + op"
                                    v-model="form.fechaNacimiento"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <label for="curp" class="block text-sm font-medium leading-6 text-gray-900">CURP</label>
                            <div class="mt-2">
                                <input type="text" name="curp" :id="'curp' + op" v-model="form.curp"
                                    placeholder="Ingrese la CURP"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <label for="rfc" class="block text-sm font-medium leading-6 text-gray-900">RFC</label>
                            <div class="mt-2">
                                <input type="text" name="rfc" :id="'rfc' + op" v-model="form.rfc"
                                    placeholder="Ingrese la RFC"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="tipoSangre" class="block text-sm font-medium leading-6 text-gray-900">Tipo de
                                sangre</label>
                            <div class="mt-2">
                                <select name="tipoSangre" :id="'tipoSangre' + op" v-model="form.tipoSangre"
                                    placeholder="Seleccione el tipo de sangre"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option v-for="tSangre in tipoSangre" :key="tSangre.idTipoSangre"
                                        :value="tSangre.idTipoSangre">
                                        {{ tSangre.tipoSangre }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <label for="alergias" class="block text-sm font-medium leading-6 text-gray-900">Alergias</label>
                            <div class="mt-2">
                                <input type="text" name="alergias" :id="'alergias' + op" v-model="form.alergias"
                                    placeholder="Ingrese si padece de alguna alergia"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <label for="discapacidad"
                                class="block text-sm font-medium leading-6 text-gray-900">Discapacidad</label>
                            <div class="mt-2">
                                <input type="text" name="discapacidad" :id="'discapacidad' + op" v-model="form.discapacidad"
                                    placeholder="Ingrese si padece de alguna discapacidad"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
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

                        </div>
                        <div class="sm:col-span-3">
                            <label for="estado" class="block text-sm font-medium leading-6 text-gray-900">Estado</label>
                            <div class="mt-2">
                                <select name="estado" :id="'estado' + op" v-model="form.estado"
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
                        </div>
                        <div class="sm:col-span-2">
                            <label for="numero" class="block text-sm font-medium leading-6 text-gray-900">Número</label>
                            <div class="mt-2">
                                <input type="number" name="numero" :id="'numero' + op" v-model="form.numero"
                                    placeholder="Ingrese el número"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="sm:col-span-3" hidden>
                            <label for="idUsuario" class="block text-sm font-medium leading-6 text-gray-900">idUsuario</label>
                            <div class="mt-2">
                                <input type="text" name="idUsuario" :id="'idUsuario' + op" v-model="form.idUsuario"
                                    placeholder="Ingrese el usuario"
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