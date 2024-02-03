<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    usuario: '',
    password: '',
});

const submit = () => {
    try {
        form.post(route('registrarse'));
    } catch (e) {
        console.log(e);
    }
};
</script>

<template>
    <Head title="Registrarse" />
    <AuthenticationCard>
        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>
        <div>
            <h2 class="text-black text-2xl text-center font-semibold p-5">Registrarse</h2>
            <div class="p-4 mb-4 text-sm text-justify rounded-lg">
                <span class="">Bienvenido al sistema de control de calificaciones de la escuela telesecundaria con la clave
                    20DTV1474D. Al ser el primer ingreso al sistema es necesario que cree las credenciales del usuario
                    administrador.</span>
            </div>
        </div>
        <div v-if="$page.props.flash.message" class="p-4 mb-4 text-sm rounded-lg" role="alert"
            :class="`text-${$page.props.flash.color}-700 bg-${$page.props.flash.color}-100 dark:bg-${$page.props.flash.color}-200 dark:text-${$page.props.flash.color}-800`">
            <span class="font-medium">
                {{ $page.props.flash.message }}
            </span>
        </div>
        <form @submit.prevent="submit">
            <div class="">
                <InputLabel for="usuario" value="Usuario" />
                <TextInput id="usuario" v-model="form.usuario" type="text" class="mt-1 block w-full" required autofocus
                    autocomplete="usuario" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" required
                    autocomplete="password" />
            </div>
            <div class="flex items-center justify-end mt-4">

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Registrarse
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
