<script setup>
////////////////////////////////////////////////////////////////////////////////////////////////
 // Importaciones necesarias para la funcionalidad de la vista en general
import { onMounted, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Tutores from '@/Components/admin/Tutores.vue';
import Alumnos from '@/Components/admin/Alumnos.vue';
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
 // Datos que recibe la vista desde el controlador
const props = defineProps({
    tutores: { type: Object },
    alumnos: { type: Object },
    generos: { type: Object },
    grados: { type: Object},
    grupos: { type: Object },
    tipoSangre: { type: Object },
    talleres: { type: Object },
});
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
 // Variables y constantes para los datos
const tutoresB = ref(true);
const alumnosB = ref(false);
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
 // Funciones para el cambio de tablas 
const mostrarTutores = () => {
    const btnTutores = document.getElementById("btnTutores");
    const btnAlumnos = document.getElementById("btnAlumnos");
    tutoresB.value = true;
    alumnosB.value = false;
    btnAlumnos.removeAttribute("disabled");
    btnTutores.setAttribute("disabled", "");
    console.log("Mostrando tutores");
}
const mostrarAlumnos = () => {
    const btnTutores = document.getElementById("btnTutores");
    const btnAlumnos = document.getElementById("btnAlumnos");
    tutoresB.value = false;
    alumnosB.value = true;
    btnTutores.removeAttribute("disabled");
    btnAlumnos.setAttribute("disabled", "");
    console.log("Mostrando alumnos");
}
////////////////////////////////////////////////////////////////////////////////////////////////
 
onMounted(() => {
   
});
</script>

<template>
    <AdminLayout title="Tutores y alumnos">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <div class="flex items-center justify-center w-full">
                <div class="rounded-3xl border bg-slate-200 flex justify-between w-1/6">
                    <button class="bg-cyan-500 hover:bg-cyan-600 text-white rounded-3xl px-4 py-2 transition duration-300"
                        id="btnTutores" @click="mostrarTutores" disabled>
                        Tutores
                    </button>
                    <button class="bg-cyan-500 hover:bg-cyan-600 text-white rounded-3xl px-4 py-2 transition duration-300"
                        id="btnAlumnos" @click="mostrarAlumnos">
                        Alumnos
                    </button>
                </div>
            </div>
            <div v-show="tutoresB">
                <Tutores :tutores="props.tutores" :generos="props.generos"></Tutores>
            </div>
            <div v-show="alumnosB">
                <Alumnos :alumnos="props.alumnos" :talleres="props.talleres" :generos="props.generos" :grados="props.grados" :grupos="props.grupos" :tipoSangre="props.tipoSangre"> </Alumnos>                
            </div>
        </div>
    </AdminLayout>
</template>