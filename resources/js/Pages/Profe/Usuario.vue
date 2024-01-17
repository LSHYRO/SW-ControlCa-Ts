<script setup>
import ProfeLayout from '@/Layouts/ProfeLayout.vue';
import { useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    usuario: { type: Object }
});

const form = useForm({
    password_actual: '',
    password_nueva: '',
    password_confirmacion: '',
});
</script>


<template>
    <ProfeLayout title="Inicio">
        
        <div class=" bg-white p-4 shadow rounded-lg h-5/6 mt-10 sm:mt-0">
            <p class="mt-1 text-sm text-gray-600">
                Esta es la descripcion
            </p>

            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="password_actual" value="Contraseña actual" />
                <TextInput id="password_actual" v-model="form.password_actual" type="password"
                    class="mt-1 block w-full" autocomplete="current-password" />                
            </div>

            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="password_nueva" value="Contraseña nueva" />
                <TextInput id="password_nueva" v-model="form.password_nueva" type="password"
                    class="mt-1 block w-full" autocomplete="new-password" />                
            </div>

            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="password_confirmacion" value="Confirmar contraseña" />
                <TextInput id="password_confirmacion" v-model="form.password_confirmacion" type="password"
                    class="mt-1 block w-full" autocomplete="new-password" />
            </div>
        </div>
    </ProfeLayout>
</template>