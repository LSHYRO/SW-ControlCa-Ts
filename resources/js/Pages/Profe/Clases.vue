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

const tipo_usuario = ref('');
const nombre_usuario = ref('');
const nombre_persona = ref('');
const nombreMateria = ref('');
const clases = ref([]);

onMounted(async () => {
    try {
        const usuario = await axios.get(route('obtenerUsuario'));
        const idTipoUsuario = usuario.data.idTipoUsuario;
        const tipoUsuario = await axios.get(route('obtenerTipoUsuario', idTipoUsuario));
        const nombre_per = await axios.get(route('obtenerDatoPersona', usuario.data.idUsuario));
        tipo_usuario.value = tipoUsuario.data.tipoUsuario;
        nombre_usuario.value = usuario.data.usuario;
        nombre_persona.value = nombre_per.data.nombre_completo;
        const response = await axios.get(route('obtenerDatosClase', nombre_per.data.idPersonal));
        clases.value = response.data;
        console.log(response);
        const mater = await axios.get(route('obtenerDatosMateria', response.data));
        nombreMateria.value = mater.data.materia;
        console.log(nombreMateria);

    } catch (e) {
        tipo_usuario.value = "Sin asignar";
        console.log("Error: " + e);
    }
});



</script>

<template>
    <ProfeLayout title="clases">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <div class="flex">
                <a v-for="clase in clases" :key="clase.id" href="#"
                    class="flex flex-col items-center bg-cyan-300 text-black border border-cyan-400 rounded-lg shadow md:w-56 hover:bg-cyan-400 dark:border-cyan-500 dark:bg-cyan-400 dark:hover:bg-cyan-500 ml-4">
                    <div class="w-full rounded-t-lg">
                        <div class="flex flex-col justify-between p-2 leading-normal">
                            <h4 class="mb-2 text-lg font-bold tracking-tight">{{ nombreMateria }}</h4>
                            <p class="mb-3 font-normal text-sm">{{ nombre_persona }}</p>
                            <p class="mb-3 font-normal text-sm">Grado: {{ clase.idGrado }}</p>
                            <p class="mb-3 font-normal text-sm">Grupo: {{ clase.idGrupo }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </ProfeLayout>
</template>
