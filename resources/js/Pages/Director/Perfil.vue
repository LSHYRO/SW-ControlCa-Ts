<script setup>
import DirectorLayout from '@/Layouts/DirectorLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    usuario: { type: Object },
    director: { type: Object },
});

const form = useForm({
    password_actual: '',
    password_nueva: '',
    password_confirmacion: '',
    idUsuario: props.usuario.idUsuario
});

const contraActualError = ref('');
const contraNuevaError = ref('');
const contraConfirmacionError = ref('');

const validateStringNotEmpty = (value) => {
    return typeof value === 'string' && value.trim() !== '';
};

const validateContraseñas = (value1, value2) => {
    return value1 === value2 && value1.trim() !== '' && value2.trim() !== '';
};

const validateLongContraseñas = (value1, value2) => {
    return value1.length >= 8 && value2.length >= 8;
};

const update = () => {
    contraActualError.value = validateStringNotEmpty(form.password_actual) ? '' : 'Ingrese la contraseña actual';
    contraNuevaError.value = validateStringNotEmpty(form.password_nueva) ? '' : 'Ingrese la contraseña nueva';
    contraConfirmacionError.value = validateStringNotEmpty(form.password_confirmacion) ? '' : 'Ingrese la contraseña nueva';
    contraNuevaError.value = validateContraseñas(form.password_nueva, form.password_confirmacion) ? '' : 'Las contraseñas no coinciden';
    contraConfirmacionError.value = validateContraseñas(form.password_nueva, form.password_confirmacion) ? '' : 'Las contraseñas no coinciden';
    contraNuevaError.value = validateLongContraseñas(form.password_nueva, form.password_confirmacion) ? '' : 'La contraseña tiene que ser mayor a 8 digitos';


    if (contraActualError.value || contraNuevaError.value || contraConfirmacionError.value) {
        return;
    }
    form.put(route('director.actualizarContraseña', form.idUsuario), {
        onSuccess: () => {
            password_actual.value = '';
            password_nueva.value = '';
            password_confirmacion.value = '';
        }
    });
}

onMounted(async () => {
    form.idUsuario = props.usuario.idUsuario;
});
</script>


<template>
    <DirectorLayout title="Perfil" :usuario="props.usuario">
        <div class=" bg-white p-4 shadow rounded-lg h-5/6 mt-10 sm:mt-0">
            <h2 class="text-black text-2xl text-center font-semibold p-5">Perfil</h2>
            <div class="my-1"></div>
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
            <div>
                <form>
                    <div>
                        <div class="md:grid md:grid-cols-8">
                            <div class="col-span-4">
                                <h3 class="m-3 text-lg font-medium text-gray-900">
                                    Informacion personal
                                </h3>
                                <p class="m-3 text-sm text-gray-600 text-justify">
                                    Para realizar modificaciones a este tipo de informacion, es necesario que acudir a la
                                    dirección.
                                </p>
                            </div>
                            <div class="col-span-4 m-2 md:grid md:grid-4">
                                <div class="col-span-3 sm:col-span-4 mt-2">
                                    <InputLabel for="nombre_completo" value="Nombre completo" />
                                    <TextInput id="nombre_completo" v-model="props.director.nombre_completo" type="text"
                                        class="mt-1 block w-full" disabled />
                                </div>
                                <div class="col-span-6 sm:col-span-4 mt-2">
                                    <InputLabel for="fechaNac" value="Fecha de nacimiento" />
                                    <TextInput id="fechaNac" v-model="props.director.fechaNacimiento" type="date"
                                        class="mt-1 block w-full" disabled />
                                </div>
                                <div class="col-span-6 sm:col-span-4 mt-2">
                                    <InputLabel for="genero" value="Genero" />
                                    <TextInput id="genero" v-model="props.director['generos'].genero" type="text"
                                        class="mt-1 block w-full" disabled />
                                </div>
                                <div class="col-span-6 sm:col-span-4 mt-2">
                                    <InputLabel for="CURP" value="CURP" />
                                    <TextInput id="CURP" v-model="props.director.CURP" type="text" class="mt-1 block w-full"
                                        disabled />
                                </div>
                                <div class="col-span-6 sm:col-span-4 mt-2">
                                    <InputLabel for="RFC" value="RFC" />
                                    <TextInput id="RFC" v-model="props.director.RFC" type="text" class="mt-1 block w-full"
                                        disabled />
                                </div>
                                <div class="col-span-6 sm:col-span-4 mt-2">
                                    <InputLabel for="correoElectronico" value="Correo electronico" />
                                    <TextInput id="correoElectronico" v-model="props.director.correoElectronico" type="text"
                                        class="mt-1 block w-full" disabled />
                                </div>
                                <div class="col-span-6 sm:col-span-4 mt-2">
                                    <InputLabel for="numTelefono" value="Telefono" />
                                    <TextInput id="numTelefono" v-model="props.director.numTelefono" type="text"
                                        class="mt-1 block w-full" disabled />
                                </div>
                                <div class="col-span-6 sm:col-span-4 mt-2">
                                    <InputLabel for="tipoSangre" value="Tipo de sangre" />
                                    <TextInput id="tipoSangre" v-model="props.director['tipo_sangre'].tipoSangre" type="text"
                                        class="mt-1 block w-full" disabled />
                                </div>
                                <div class="col-span-6 sm:col-span-4 mt-2">
                                    <InputLabel for="alergias" value="Alergias" />
                                    <TextInput id="alergias" v-model="props.director.alergias" type="text"
                                        class="mt-1 block w-full" disabled />
                                </div>
                                <div class="col-span-6 sm:col-span-4 mt-2">
                                    <InputLabel for="discapacidad" value="Discapacidad" />
                                    <TextInput id="discapacidad" v-model="props.director.discapacidad" type="text"
                                        class="mt-1 block w-full" disabled />
                                </div>
                                <div class="col-span-6 sm:col-span-4 mt-2">
                                    <InputLabel for="direccion" value="Direccion" />
                                    <TextInput id="direccion" v-model="props.director.domicilio" type="text"
                                        class="mt-1 block w-full" disabled />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6 mt-5"></div>
            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
            <!--  //Mensaje para mostrar el mensaje de que se ha borrado o agregado correctamente un tutor               -->
            <div v-if="$page.props.flash.message" class="p-4 mb-4 text-sm rounded-lg" role="alert"
                :class="`text-${$page.props.flash.color}-700 bg-${$page.props.flash.color}-100 dark:bg-${$page.props.flash.color}-200 dark:text-${$page.props.flash.color}-800`">
                <span class="font-medium">
                    {{ $page.props.flash.message }}
                </span>
            </div>
            <div>
                <form @submit.prevent="update()">
                    <div>
                        <div class="md:grid md:grid-cols-8">
                            <div class="col-span-4">
                                <h3 class="m-3 text-lg font-medium text-gray-900">
                                    Cambiar contraseña
                                </h3>
                                <p class="m-3 text-sm text-gray-600 text-justify">
                                    Para realizar el cambio de contraseña es necesario recordar la contraseña actual, en
                                    caso de
                                    haber olvidado la contraseña, es necesario acudir a la dirección para su recuperación.
                                    La nueva contraseña tiene que ser mayor a 8 digitos.
                                </p>
                            </div>
                            <div class="col-span-4 m-2 md:grid md:grid-4">
                                <div class="col-span-3 sm:col-span-4 mt-2">
                                    <InputLabel for="password_actual" value="Contraseña actual" />
                                    <TextInput id="password_actual" v-model="form.password_actual" type="password"
                                        class="mt-1 block w-full" autocomplete="current-password" />
                                </div>
                                <div v-if="contraActualError != ''" class="text-red-500 text-xs mt-1">{{ contraActualError
                                }}</div>
                                <div class="col-span-6 sm:col-span-4 mt-2">
                                    <InputLabel for="password_nueva" value="Contraseña nueva" />
                                    <TextInput id="password_nueva" v-model="form.password_nueva" type="password"
                                        class="mt-1 block w-full" autocomplete="new-password" />
                                </div>
                                <div v-if="contraNuevaError != ''" class="text-red-500 text-xs mt-1">{{ contraNuevaError }}
                                </div>
                                <div class="col-span-6 sm:col-span-4 mt-2">
                                    <InputLabel for="password_confirmacion" value="Confirmar contraseña" />
                                    <TextInput id="password_confirmacion" v-model="form.password_confirmacion"
                                        type="password" class="mt-1 block w-full" autocomplete="new-password" />
                                </div>
                                <div v-if="contraConfirmacionError != ''" class="text-red-500 text-xs mt-1">{{
                                    contraConfirmacionError }}</div>
                                <div class="col-span-6 sm:col-span-4 mt-2" hidden>
                                    <InputLabel for="idUsuario" value="idUsuario" />
                                    <input id="idUsuario" v-model="form.idUsuario" type="number"
                                        class="mt-1 block w-full" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <button type="submit"
                                class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded right"
                                :class="{ 'opacity-25': form.processing }" :disabled="form.processing"> <i
                                    class="fa-solid fa-floppy-disk mr-2"></i>Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </DirectorLayout>
</template>