<script setup>
defineProps({
    
});
</script>

<template>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        :href="route('alumno.inicio')">
        <i class="fas fa-home mr-2"></i>Inicio
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        :href="route('alumno.enCurso')">
            <i class="fa fa-book mr-2"></i>En Curso
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        :href="route('alumno.perfil')">
        <i class="fas fa-user mr-2"></i>Perfil
    </a>
</template>
