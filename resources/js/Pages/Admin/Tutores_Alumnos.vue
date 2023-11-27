<script setup>
import { ref } from 'vue';
import SearchBar from '@/Components/SearchBar.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Tutores from '@/Components/admin/Tutores.vue';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    tutores: { type: Object },
    alumnos: { type: Object },

});

const tutoresB = ref(true);
const alumnosB = ref(false);

const mostrarTutores = () => {
    const btnT = document.getElementById("btnTutores");
    const btnA = document.getElementById("btnAlumnos");
    tutoresB.value = true;
    alumnosB.value = false;
    //btnT.setAttribute("disabled", "true");
    //btnA.setAttribute("disabled", "false");

    return tutoresB;
}

const mostrarAlumnos = () => {
    const btnT = document.getElementById("btnTutores");
    const btnA = document.getElementById("btnAlumnos");
    tutoresB.value = false;
    alumnosB.value = true;
    //btnT.setAttribute("disabled", "false");
    //btnA.setAttribute("disabled", "true");

    return alumnosB;
}
</script>

<template>
    <AdminLayout title="Tutores y alumnos">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <div class="flex items-center justify-center w-full">
                <div class="rounded-3xl border bg-slate-200 flex justify-between w-1/6">
                    <button class="bg-cyan-500 hover:bg-cyan-600 text-white rounded-3xl px-4 py-2 transition duration-300"
                        id="btnTutores" @click="mostrarTutores" >
                        Tutores
                    </button>
                    <button class="bg-cyan-500 hover:bg-cyan-600 text-white rounded-3xl px-4 py-2 transition duration-300"
                        id="btnAlumnos" @click="mostrarAlumnos" >
                        Alumnos
                    </button>
                </div>
            </div>
            <div v-if="tutoresB">
                <Tutores :tutores="tutores"></Tutores>
            </div>
            <div v-else-if="alumnosB">

            </div>
        </div>
    </AdminLayout>
</template>