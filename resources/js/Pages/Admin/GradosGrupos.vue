<script setup>
 // Importaciones necesarias para la funcionalidad de la vista en general
 import { onMounted, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Grados from '@/Components/admin/Grados.vue';
import Grupos from '@/Components/admin/Grupos.vue';

const props = defineProps({
    grados: { type: Object },
    grupos: { type: Object },
    ciclos: { type: Object },
    usuario: { type: Object }
});

const gradosB = ref(true);
const gruposB = ref(false);
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
 // Funciones para el cambio de tablas 
const mostrarGrados = () => {
    const btnGrados = document.getElementById("btnGrados");
    const btnGrupos = document.getElementById("btnGrupos");
    gradosB.value = true;
    gruposB.value = false;
    btnGrupos.removeAttribute("disabled");
    btnGrados.setAttribute("disabled", "");
    console.log("Mostrando grados");
}
const mostrarGrupos = () => {
    const btnGrados = document.getElementById("btnGrados");
    const btnGrupos = document.getElementById("btnGrupos");
    gradosB.value = false;
    gruposB.value = true;
    btnGrados.removeAttribute("disabled");
    btnGrupos.setAttribute("disabled", "");
    console.log("Mostrando grupos");
}
////////////////////////////////////////////////////////////////////////////////////////////////
 
onMounted(() => {
   
});

</script>

<template>
    <AdminLayout title="Grados y Grupos" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <div class="flex items-center justify-center w-full">
                <div class="rounded-3xl border bg-slate-200 flex justify-between w-1/6">
                    <button class="bg-cyan-500 hover:bg-cyan-600 text-white rounded-3xl px-4 py-2 transition duration-300"
                        id="btnGrados" @click="mostrarGrados" disabled>
                        Grados
                    </button>
                    <button class="bg-cyan-500 hover:bg-cyan-600 text-white rounded-3xl px-4 py-2 transition duration-300"
                        id="btnGrupos" @click="mostrarGrupos">
                        Grupos
                    </button>
                </div>
            </div>
            <div v-show="gradosB">
                <Grados :grados="props.grados" :ciclos="props.ciclos"></Grados>
            </div>
            <div v-show="gruposB">
                <Grupos :grupos="props.grupos" :ciclos="props.ciclos"></Grupos>
            </div>
        </div>
    </AdminLayout>
</template>