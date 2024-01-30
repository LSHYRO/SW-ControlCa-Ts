<script setup>
import TutorLayout from '@/Layouts/TutorLayout.vue';
import { ref } from 'vue';


const props = defineProps({
    usuario: { type: Object },
    tutor: { type: Object },
    alumnos: { type: Object },
    clasesPorAlumno: { type: Array },
});
console.log(props.alumnos);
console.log(props.clasesPorAlumno);

</script>

<template>
    <TutorLayout title="Calificacinoes" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg alturaM">
            <h4 class="text-black text-2xl text-center font-semibold p-5">Calificaciones</h4>
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>

            <div class="mt-8 bg-white p-4 shadow rounded-lg alturaM">
                <div v-for="alumno in props.clasesPorAlumno" :key="alumno.info.idAlumno" class="mb-4">
                    <div class="border p-3 rounded-lg">
                        <h2 class="text-md font-semibold">Alumno: {{ alumno.info.nombre_completo }}</h2>
                        <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
                        
                        <div class="flex">
                            <a v-for="clase in alumno.clases_cursadas" :key="clase.idClase"
                                :href="route('tutor.mostrarClasessHijo', { idClase: clase.idClase })"
                                class="flex flex-col items-center bg-cyan-300 text-black border border-cyan-400 rounded-lg shadow md:w-56 hover:bg-cyan-400 dark:border-cyan-500 dark:bg-cyan-400 dark:hover:bg-cyan-500 ml-4">
                                <div class="w-full h-full rounded-t-lg fondo">
                                    <div class="w-full h-full rounded-t-lg filtro">
                                        <div class="flex flex-col justify-between p-2 leading-normal">
                                            <h2 class="mb-2 text-md font-bold tracking-tight">
                                            {{ clase.materias.materia }}
                                            </h2>
                                            <p class="mb-3 font-normal text-sm">{{ nombre_persona }}</p>
                                            <p class="mb-3 font-normal text-sm">Grado: {{ clase["grados"].grado }}</p>
                                            <p class="mb-3 font-normal text-sm">Grupo: {{ clase["grupos"].grupo }}</p>

                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </TutorLayout>
</template>



<style>
.alturaM {
    min-height: 80vh;
}

.fondo {
    /* Ubicación de la imagen */
    background-image: url("https://i.pinimg.com/originals/74/86/4d/74864db7d1b9fbb141458a35aef1426f.png");
    /* Para que la imagen de fondo se adapte al tamaño de la pantalla */
    background-size: cover;

}

.filtro {
    background-color: rgba(255, 255, 255, 0.78);
    backdrop-filter: blur(1px);
}
</style>