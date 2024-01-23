<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

import TopContentTutor from '@/Components/tutor/TopContentTutor.vue'
import OpcionesNavTutor from '@/Components/tutor/OpcionesNavTutor.vue';

const props = defineProps({
    title: String,
    usuario: { type: Object }
});
const tipo_usuario = ref('');
const nombre_usuario = ref('');
const nombre_persona = ref('');

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};
/*
onMounted(async () => {
    try {
        const usuario = await axios.get(route('obtenerUsuario'));
        const idTipoUsuario = usuario.data.idTipoUsuario;
        const tipoUsuario = await axios.get(route('obtenerTipoUsuario', idTipoUsuario));
        const nombre_per = await axios.get(route('obtenerDatoPersona', usuario.data.idUsuario));
        tipo_usuario.value = tipoUsuario.data.tipoUsuario;
        nombre_usuario.value = usuario.data.usuario;
        nombre_persona.value = nombre_per.data.nombre_completo;

    } catch (e) {
        tipo_usuario.value = "Sin asignar";
        console.log("Error: " + e);
    }
});*/
</script>

<template>
    <div class="flex flex-col h-screen bg-gray-100">

        <Head :title="title" />

        <TopContentTutor :usuario="props.usuario"/>

        <div class="flex-1 flex flex-wrap">
            <!-- Barra lateral de navegación (oculta en dispositivos pequeños) -->
            <div class="p-2 bg-white w-full md:w-60 flex flex-col md:flex" id="sideNav">
                <nav>
                    <div class="w-60 h-16 justify-start items-center px-2 inline-flex">
                        <div class="w-12 h-12 relative">
                            <div class="w-12 h-12 left-0 top-0 absolute bg-zinc-300 rounded-full"></div>
                            <img class="w-12 h-12 left-0 top-0 absolute"
                                src="https://cdn-icons-png.flaticon.com/512/9069/9069049.png" />
                        </div>
                        <div class="flex-col justify-start items-center inline-flex">
                            <div class="text-center text-black text-base font-semibold font-['DM Sans'] px-2">{{ props.usuario.personalNombre }}
                            </div>
                            <div class="text-center text-stone-950 text-sm font-normal font-['DM Sans']">{{ props.usuario.usuario }}
                            </div>
                        </div>
                    </div>
                    <!-- Señalador de ubicación -->
                    <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mt-2"></div>
                    <OpcionesNavTutor />
                </nav>
                <!-- Señalador de ubicación -->
                <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mt-2"></div>
                <!-- Ítem de Cerrar Sesión -->
                <a class="block text-gray-500 py-2.5 px-4 my-2 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white mt-auto"
                    :href="route('cerrarSesion')">
                    <i class="fas fa-sign-out-alt mr-2"></i>Cerrar sesión
                </a>
            </div>

            <!-- Área de contenido principal -->
            <div class="flex-1 p-4 w-full md:w-1/2">
                <!-- Page Content -->
                <main>
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>