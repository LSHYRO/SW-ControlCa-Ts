<script setup>
import ProfeLayout from '@/Layouts/ProfeLayout.vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    clase: { type: Object },
    usuario: { type: Object },
    actividad: { type: Object },
    alumnos: { type: Object },
    calificaciones: {
        type: Object,
        default: () => ({})
    },
});

const guardarCalificaciones = async () => {
    try {        
        await form.transform(data => ({
            ...data,
            clase: props.clase.idClase,
            actividad: props.actividad.idActividad
        })).post(route('profe.almacCalificaciones'));
    } catch (error) {
        console.error('Error al enviar datos:', error);
    }
};

const form = useForm({
    calificaciones: props.calificaciones
});
</script>
<template>
    <ProfeLayout :title="props.actividad.titulo" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg alturaM">
            <h2 class="text-black text-2xl text-center font-semibold p-5">{{ actividad.titulo }}</h2>
            <h3 class="text-black text-base text-center m-2 px-12">{{ actividad.descripcion }} </h3>
            <div class="py-3 flex flex-col md:flex-row md:items-start md:space-x-3 space-y-3 md:space-y-0">
                <div class="m-1">
                    <a :href="route('profe.mostrarClase', clase.idClase)"><i class="fa-solid fa-arrow-left"> </i> Volver</a>
                </div>
            </div>
            <!-- Linea de separación -->
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
            <div class="my-2">
                <p class="my-1">
                    <strong>Fecha de inicio: </strong> {{ props.actividad.fecha_i }}
                </p>
                <p class="my-1">
                    <strong>Fecha de entrega: </strong> {{ props.actividad.fecha_e }}
                </p>
                <p class="my-1">
                    <strong>Periodo: </strong> {{ props.actividad.periodoD }}
                </p>
            </div>
            <div class="w-full">
                <form @submit.prevent="guardarCalificaciones">
                    <table class="rounded-xl border-2 p-2 border-cyan-400 w-full">
                        <thead>
                            <tr class="border border-cyan-300">
                                <th class="border-r border-cyan-300">Alumno</th>
                                <th>Calificación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="alumno in alumnos" :key="alumno.idAlumno" class="border-t border-cyan-100">
                                <td class="border-r border-cyan-300 text-sm pl-3">{{ alumno.nombre_completo }}: </td>
                                <td class="text-center text-sm">
                                    <input v-model="form.calificaciones[alumno.idAlumno]" type="number"
                                        placeholder="Calificación"
                                        class="text-sm border rounded-md border-gray-300 min-w-24 " min="0" max="10">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <a class="bg-white hover:bg-cyan-100 text-black font-semibold py-2 px-4 mr-2 rounded"
                        :href="route('profe.mostrarClase', clase.idClase)">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded mt-2">Guardar</button>
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