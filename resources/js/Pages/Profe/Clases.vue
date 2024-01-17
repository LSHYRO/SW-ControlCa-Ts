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



</script>

<template>
    <ProfeLayout title="clases">
        <div class="mt-8 bg-white p-4 shadow rounded-lg">
            <div class="flex">
                <a href="#"
                    class="flex flex-col items-center bg-cyan-300 text-black border border-cyan-400 rounded-lg shadow md:w-56 hover:bg-cyan-400 dark:border-cyan-500 dark:bg-cyan-400 dark:hover:bg-cyan-500">
                    <div class="w-full rounded-t-lg">
                        <div class="flex flex-col justify-between p-2 leading-normal">
                            <h4 class="mb-2 text-lg font-bold tracking-tight">Fundamentos de inteligencia de negocios</h4>
                            <p class="mb-3 font-normal text-sm">Rogelio Limón</p>
                            <p class="mb-3 font-normal text-sm">Grado: 1</p>
                            <p class="mb-3 font-normal text-sm">Grupo: B</p>
                        </div>
                    </div>
                </a>

                <a href="#"
                    class="flex flex-col items-center bg-cyan-300 text-black border border-cyan-400 rounded-lg shadow md:w-56 hover:bg-cyan-400 dark:border-cyan-500 dark:bg-cyan-400 dark:hover:bg-cyan-500 ml-4">
                    <!-- Agregué ml-4 para un margen izquierdo entre los cards -->
                    <div class="w-full rounded-t-lg">
                        <div class="flex flex-col justify-between p-2 leading-normal">
                            <h4 class="mb-2 text-lg font-bold tracking-tight">Matemáticas</h4>
                            <p class="mb-3 font-normal text-sm">Rogelio Limón</p>
                            <p class="mb-3 font-normal text-sm">Grado: 1</p>
                            <p class="mb-3 font-normal text-sm">Grupo: B</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </ProfeLayout>
</template>
