<script setup>
import ProfeLayout from '@/Layouts/ProfeLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    clase: { type: Object },
    usuario: { type: Object },
    actividad: { type: Object },
    alumnos: { type: Object },
    periodos: { type: Object },
    calificaciones: {
        type: Object,
        default: () => ({})
    },
});

const form = useForm({
    periodo: props.actividad.idPeriodo,
    fecha_inicio: props.actividad.fecha_inicio,
    calificaciones: props.calificaciones
});

const paseLista = async () => {
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

const validateFechaTrabajo = (value) => {
    const fechaInicioPeriodo = form.periodo.fecha_inicio;
    const fechaFinPeriodo = form.periodo.fecha_fin;

    return value >= fechaInicioPeriodo && value <= fechaFinPeriodo;
};
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
            <form @submit.prevent="paseLista()">
                <div class="w-full">
                    <div class="sm:col-span-3">
                        <label for="periodos" class="block text-sm font-medium leading-6 text-gray-900">Periodo</label>
                        <div class="mt-2">
                            <select name="periodos" :id="'periodos' + op" v-model="form.periodo"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="" disabled selected>Selecciona un periodo</option>
                                <option v-for="periodo in periodos" :key="periodo.idPeriodo" :value="periodo">
                                    {{ periodo.descripcion }}
                                </option>
                            </select>
                        </div>
                        <div v-if="periodoError != ''" class="text-red-500 text-xs">{{ periodoError }}</div>
                    </div>
                    <div class="sm:col-span-1 md:col-span-2"> <!-- Definir el tamaño del cuadro de texto -->
                        <label for="fecha_inicio" class="block text-sm font-medium leading-6 text-gray-900">Fecha</label>
                        <div class="mt-2">
                            <input type="date" name="fecha_inicio" :id="'fecha_inicio' + op" v-model="form.fecha_inicio"
                                placeholder="Ingrese la descripción de la actividad"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                        </div>
                        <div v-if="fecha_inicioError != ''" class="text-red-500 text-xs">{{ fecha_inicioError }}</div>
                    </div>
                    <table class="rounded-xl p-2 border-cyan-400 w-full">
                        <thead>
                            <tr class="border-b border-cyan-300">
                                <th class="border-r border-cyan-300">Alumno</th>
                                <th>Calificación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="alumno in alumnos" :key="alumno.idAlumno" class="border-b border-cyan-100">
                                <td class="border-r border-cyan-300 text-sm pl-3">{{ alumno.nombre_completo }}: </td>
                                <td class="text-center text-sm">
                                    <input v-model="form.calificaciones[alumno.idAlumno]" type="number"
                                        placeholder="Calificación"
                                        class="text-sm border rounded-md border-gray-300 min-w-24 " min="0" max="10">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="my-2 text-right pr-3">
                        <a class="bg-white hover:bg-cyan-100 text-black font-semibold py-2 px-4 mr-2 rounded"
                            :href="route('profe.mostrarClase', clase.idClase)">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded mt-2">Guardar</button>
                    </div>

                </div>
            </form>
        </div>
    </ProfeLayout>
</template>
<style>
.alturaM {
    min-height: 80vh;
}
</style>