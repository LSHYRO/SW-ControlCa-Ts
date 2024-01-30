<script setup>
import TutorLayout from '@/Layouts/TutorLayout.vue';
import { ref } from 'vue';


const props = defineProps({
    usuario: { type: Object },
    tutor: { type: Object },
    alumnos: { type: Object },
});

</script>

<template>
    <TutorLayout title="Calificacinoes" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg alturaM">
            <h4 class="text-black text-2xl text-center font-semibold p-5">Calificaciones</h4>
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>

            <div class="mt-8 bg-white p-4 shadow rounded-lg alturaM">
                <div v-for="alumno in props.alumnos" :key="alumno.idAlumno" class="mb-4">
                    <div class="border p-3 rounded-lg">
                        <h2 class="text-lg font-semibold">Alumno: {{ alumno.nombre_completo }}</h2>
                    </div>
                </div>
            </div>

        </div>
    </TutorLayout>
</template>