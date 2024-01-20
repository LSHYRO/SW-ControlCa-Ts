<script setup>
defineProps({

});
</script>

<template>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        :href="route('director.inicio')">
        <i class="fas fa-home mr-2"></i>Inicio
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        :href="route('director.tutoresAlum')">
        <i class=" fas fa-users mr-2"></i>Alumnos y tutores
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        :href="route('director.profesores')">
        <i class="fas fa-pencil mr-2"></i>Docentes
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        :href="route('director.directivos')">
        <i class="fa fa-briefcase" aria-hidden="true"></i> Directivos
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        :href="route('director.materias')">
        <i class="fa fa-book mr-2"></i>Materias
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        :href="route('director.clases')">
        <i class="fas fa-desktop mr-2"></i>Clases
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        :href="route('director.calificaciones')">
        <i class="fa fa-star" aria-hidden="true"></i> Calificaciones
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        :href="route('director.gradosgrupos')">
        <i class="fas fa-balance-scale mr-2"></i>Grados y grupos
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        :href="route('director.ciclosperiodos')">
        <i class="fas fa-calendar mr-2"></i>Ciclos y periodos
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        :href="route('director.cuentas')">
        <i class="fa fa-address-card" aria-hidden="true"></i> Cuentas
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        :href="route('director.perfil')">
        <i class="fas fa-user mr-2"></i>Perfil
    </a>
</template>
