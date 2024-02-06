<script setup>
import { ref, computed, getCurrentInstance, onMounted, watch } from 'vue';
import SearchBar from '@/Components/SearchBar.vue';
import DirectorLayout from '@/Layouts/DirectorLayout.vue';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3';
import DataTable from 'datatables.net-vue3';
import DataTablesLib from 'datatables.net';
import Buttons from 'datatables.net-buttons-dt';
import pdfmake from 'pdfmake';
import print from 'datatables.net-buttons/js/buttons.print'
import pdfFonts from 'pdfmake/build/vfs_fonts.js';
import ButtonsHtml5 from 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-responsive-dt';
import Select from 'datatables.net-select-dt';
import jsZip from 'jszip';
window.JSZip = jsZip;

// Datos que recibe la vista para su uso
const props = defineProps({
    alumno: { type: Object },
    clases: { type: Object },
    generos: { type: Object },
    grados: { type: Object },
    tipoSangre: { type: Object },
    talleres: { type: Object },
    usuario: { type: Object },
});

console.log(props.clases);
const idAlumno = 1;
const materias = (datos) => {
    try {
        const clases = datos.map((clase) => clase.idMateria);
        const idMaterias = materias.join(',');
        return idMaterias;
    } catch (error) {
        console.log("Error al eliminar varias materias: " + error);
        return [];
    }
}

const grupos = (datos) => {
    try {
        const clases = datos.map((clase) => clase.idGrupo);
        const idGrupo = grupos.join(',');
        return idGrupo;
    } catch (error) {
        console.log("Error al eliminar varios grados: " + error);
        return [];
    }
}

</script>

<template>

<DirectorLayout title="materias" :usuario="props.usuario">
    <div class=" bg-white p-4 shadow rounded-lg alturaM">
            <h2 class="text-black text-2xl text-center font-semibold p-5">Clases cursando</h2>
            <div class="my-1"></div> <!-- Espacio de separación -->
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
            <div class="flex">
                <a v-for="clase in props.clases" :key="clase.idClase" :href="route('director.mostrarClase', { idClase: clase.idClase, idAlumno })"
                    class="flex flex-col items-center bg-cyan-300 text-black border border-cyan-400 rounded-lg shadow md:w-56 hover:bg-cyan-400 dark:border-cyan-500 dark:bg-cyan-400 dark:hover:bg-cyan-500 ml-4">
                    <div class="w-full h-full rounded-t-lg fondo">
                        <div class="w-full h-full rounded-t-lg filtro">
                            <div class="flex flex-col justify-between p-2 leading-normal">
                               <h4 class="mb-2 text-lg font-bold tracking-tight">{{ clase["materias"].materia }}</h4>
                                <p class="mb-3 font-normal text-sm">{{ nombre_persona }}</p>
                                <p class="mb-3 font-normal text-sm">Grado: {{ clase["grados"].grado }}</p>
                                <p class="mb-3 font-normal text-sm">Grupo: {{ clase["grupos"].grupo }}</p>

                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
</DirectorLayout>

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