<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

import TopContent from '@/Components/admin/TopContent.vue'
import OpcionesNavAdmin from '@/Components/admin/OpcionesNavAdmin.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="flex flex-col h-screen bg-gray-100">

        <Head :title="title" />

        <TopContent />

        <div class="flex-1 flex flex-wrap">
            <!-- Barra lateral de navegación (oculta en dispositivos pequeños) -->
            <div class="p-2 bg-white w-full md:w-60 flex flex-col md:flex" id="sideNav">
                <nav>
                    <div class="w-60 h-16 justify-start items-center gap-4 inline-flex">
                        <div class="w-12 h-12 relative">
                            <div class="w-12 h-12 left-0 top-0 absolute bg-zinc-300 rounded-full"></div>
                            <img class="w-12 h-12 left-0 top-0 absolute" src="{{ asset('images/perfil.png') }}" />
                        </div>
                        <div class="flex-col justify-start items-center inline-flex ">
                            <div class="text-center text-black text-lg font-normal font-['DM Sans']">Rigoberto López
                            </div>
                            <div class="text-center text-stone-950 text-sm font-normal font-['DM Sans']">SALOR850201
                            </div>
                        </div>
                    </div>
                    <!-- Señalador de ubicación -->
                    <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mt-2"></div>

                    <OpcionesNavAdmin />

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
