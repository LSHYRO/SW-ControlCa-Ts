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
    estados: { type: Object },

});

const tutores = ref(true);
const alumnos = ref(false);

const mostrarTutores = () => {
    const btnT = document.getElementById("tutores");
    const btnA = document.getElementById("alumnos");
    if (tutores) {
        tutores.value = false;
        alumnos.value = true;
        btnT.setAttribute("disabled", "true");
        btnA.setAttribute("disabled", "false");
    } else {
        tutores.value = true;
        alumnos.value = false;
        btnA.setAttribute("disabled", "true");
        btnT.setAttribute("disabled", "false");
    }

    return tutores;
}
</script>

<template>
    <AdminLayout title="Tutores y alumnos">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <div class="flex items-center justify-center w-full">
                <div class="rounded-3xl border bg-slate-200 flex justify-between w-1/6">
                    <button class="bg-cyan-500 hover:bg-cyan-600 text-white rounded-3xl px-4 py-2 transition duration-300"
                        id="tutores" @click="mostrarTutores">
                        Tutores
                    </button>
                    <button class="bg-cyan-500 hover:bg-cyan-600 text-white rounded-3xl px-4 py-2 transition duration-300"
                        id="alumnos" @click="mostrarTutores">
                        Alumnos
                    </button>
                </div>
            </div>
            <div v-if="tutores">
                <Tutores></Tutores>
            </div>
            <div v-else-if="alumnos">

            </div>
        </div>
    </AdminLayout>
</template>