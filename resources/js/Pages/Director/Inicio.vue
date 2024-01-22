<script setup>
import DirectorLayout from '@/Layouts/DirectorLayout.vue';

const props = defineProps({
    usuario: { type: Object }
});
</script>


<template>
    <DirectorLayout title="Inicio" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg h-5/6">
            <h2 class="font-bold text-center text-xl pt-5">Bienvenido al sistema de la telesecundaria clave: 20DTV1474D</h2>
            <h2 class="text-justify p-9">Ha iniciado sesion como director, donde podra realizar la gestión de usuarios, materias y clases.</h2>
        </div>
    </DirectorLayout>
</template>