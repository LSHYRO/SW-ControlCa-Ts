<script setup>
import ProfeLayout from '@/Layouts/ProfeLayout.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Actividades from '@/Components/profe/ActividadesSeccion.vue';
import AlumnosSeccion from '@/Components/profe/AlumnosSeccion.vue';


const props = defineProps({
    clase: { type: Object },
    usuario: { type: Object },
    actividad: { type: Object },
    alumnos: { type: Object }
});

</script>
<template>
    <ProfeLayout :title="props.actividad.titulo" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg alturaM">
            <h2 class="text-black text-2xl text-center font-semibold p-5">{{ clase['materias'].materia }}</h2>

            <div class="py-3 flex flex-col md:flex-row md:items-start md:space-x-3 space-y-3 md:space-y-0">
                <div class="m-1">
                    <a :href="route('profe.mostrarClase', clase.idClase)"><i class="fa-solid fa-arrow-left"> </i> Volver</a>
                </div>
            </div>
            <!-- Linea de separación -->
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
            <div>
                <form @submit.prevent="guardarCalificaciones">
                    <table>
                        <thead>
                            <tr>
                                <th>Alumno</th>
                                <th>Calificación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="alumno in alumnos" :key="alumno.id">
                                <td>{{ alumno.nombre }}</td>
                                <td>
                                    <input v-model="calificaciones[alumno.id]" type="number" placeholder="Calificación">
                                </td>
                            </tr>
                        </tbody>
                    </table>        
                    <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded mt-2">Guardar</button>
                </form>
            </div>
        </div>
    </ProfeLayout>
</template>
<style>
.alturaM {
    min-height: 80vh;
}
</style>