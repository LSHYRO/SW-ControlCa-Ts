<script setup>
import { ref, computed, getCurrentInstance, onMounted } from 'vue';
import SearchBar from '@/Components/SearchBar.vue';
import ProfeLayout from '@/Layouts/ProfeLayout.vue';
import MenuOpcionesProfe from '@/Components/profe/MenuOpcionesProfe.vue';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3';
import pdfmake from 'pdfmake';
import print from 'datatables.net-buttons/js/buttons.print'
import pdfFonts from 'pdfmake/build/vfs_fonts.js';
import ButtonsHtml5 from 'datatables.net-buttons/js/buttons.html5.mjs';
import jsZip from 'jszip';
import axios from 'axios';

const props = defineProps({
    usuario: { type: Object },
    clases: { type: Object },
});

const tipo_usuario = ref('');
const nombre_usuario = ref('');
const nombre_persona = ref('');
const nombreMateria = ref('');

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
/*
onMounted(async () => {
    try {
        const usuario = await axios.get(route('obtenerUsuario'));
        const idTipoUsuario = usuario.data.idTipoUsuario;
        const idUsuario = usuario.data.idUsuario;
        const tipoUsuario = await axios.get(route('obtenerTipoUsuario', idTipoUsuario));
        const nombre_per = await axios.get(route('obtenerDatoPersona', idUsuario));
        tipo_usuario.value = tipoUsuario.data.tipoUsuario;
        nombre_usuario.value = usuario.data.usuario;
        nombre_persona.value = nombre_per.data.nombre_completo;
        const idPersonal = nombre_per.data.idPersonal;
        const response = await axios.get(route('obtenerDatosClase', idPersonal));
        clases.value = response.data;

    } catch (e) {
        tipo_usuario.value = "Sin asignar";
        console.log("Error: " + e);
    }
});*/
</script>

<template>
    <ProfeLayout title="Clases" :usuario="props.usuario">
        <div class=" bg-white p-4 shadow rounded-lg alturaM">
            <h2 class="text-black text-2xl text-center font-semibold p-5">Clases</h2>
            <div class="my-1"></div> <!-- Espacio de separación -->
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
            <div class="flex">
                <a v-for="clase in props.clases" :key="clase.idClase" :href="route('profe.mostrarClase', clase.idClase)"
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
    </ProfeLayout>
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
