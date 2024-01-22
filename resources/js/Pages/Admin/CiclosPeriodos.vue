<script setup>
import { onMounted, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Ciclos from '@/Components/admin/Ciclos.vue';
import Periodos from '@/Components/admin/Periodos.vue';

const props = defineProps({
    ciclos: { type: Object },
    periodos: { type: Object },
    usuario: { type: Object }
});

// Variables y constantes para los datos
const ciclosB = ref(true);
const periodosB = ref(false);//alumnos

 // Funciones para el cambio de tablas 
 const mostrarCiclos = () => {
    const btnCiclos = document.getElementById("btnCiclos");
    const btnPeriodos = document.getElementById("btnPeriodos");
    ciclosB.value = true;
    periodosB.value = false;
    btnPeriodos.removeAttribute("disabled");
    btnCiclos.setAttribute("disabled", "");
    console.log("Mostrando ciclos");
}
const mostrarPeriodos = () => {
    const btnCiclos = document.getElementById("btnCiclos");
    const btnPeriodos = document.getElementById("btnPeriodos");
    ciclosB.value = false;
    periodosB.value = true;
    btnCiclos.removeAttribute("disabled");
    btnPeriodos.setAttribute("disabled", "");
    console.log("Mostrando periodos");
}

onMounted(() => {
   
});

</script>

<template>
    <AdminLayout title="Ciclos y Periodos" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <div class="flex items-center justify-center w-full">
                <div class="rounded-3xl border bg-slate-200 flex justify-between w-1/6">
                    <button class="bg-cyan-500 hover:bg-cyan-600 text-white rounded-3xl px-4 py-2 transition duration-300"
                        id="btnCiclos" @click="mostrarCiclos" disabled>
                        Ciclos
                    </button>
                    <button class="bg-cyan-500 hover:bg-cyan-600 text-white rounded-3xl px-4 py-2 transition duration-300"
                        id="btnPeriodos" @click="mostrarPeriodos">
                        Periodos
                    </button>
                </div>
            </div>
            <div v-show="ciclosB">
                <Ciclos :ciclos="props.ciclos"></Ciclos>
            </div>
            <div v-show="periodosB">
                <Periodos :periodos="props.periodos" :ciclos="props.ciclos"></Periodos>
            </div>
        </div>
    </AdminLayout>
</template>