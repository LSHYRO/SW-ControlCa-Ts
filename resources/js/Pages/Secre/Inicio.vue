<script setup>
import SecreLayout from '@/Layouts/SecreLayout.vue';

const props = defineProps({
    usuario: { type: Object },
    message: { String, default: '' },
    color: { String, default: '' },
    avisos: { type: Object },
});
</script>


<template>
    <SecreLayout title="Inicio" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg h-5/6">
            <h2 class="font-bold text-center text-xl pt-5">Bienvenido al sistema de la telesecundaria clave: 20DTV1474D</h2>
            <h2 class="text-justify p-9">Ha iniciado sesion como secretari@, donde podra realizar la consulta de datos de materias y clases.</h2>
            <div v-if="$props.message" class="p-4 mb-4 text-sm rounded-lg" role="alert"
                :class="`text-${$props.color}-700 bg-${$props.color}-100 dark:bg-${$props.color}-200 dark:text-${$props.color}-800`">
                <span class="font-medium">
                    {{ $props.message }}
                </span>
            </div>
        </div>
        <div class="mt-8 bg-white p-4 shadow rounded-lg h-5/6">
            <h2 class="font-bold text-center text-xl pt-5">Avisos</h2>
            <div class="mx-1 my-8 justify-items-center md:mx-14">
                <ul v-for="aviso in props.avisos" :key="aviso.idAviso"
                class="w-full rounded-xl shadow-md border border-cyan-200 hover:border-cyan-300 my-4 p-2">
                <li>
                    <div class="w-full">
                        <h4 class="text-lg font-semibold text-center m-2">
                            {{ aviso.titulo }} 
                        </h4>
                        <p class="text-base mx-6 my-3 text-left">
                            <strong>Publicador: </strong>{{ aviso.nombre }}
                        </p>                        
                    </div>

                    <div class="w-full px-1 border-t-2 border-cyan-200">
                        <p class="text-base mx-6 my-3 text-justify">
                            {{ aviso.descripcion }}
                        </p>   
                        <p class="text-base mx-6 my-3 text-left">
                            <strong>Fecha: </strong>{{ aviso.fecha_re }}
                        </p>
                        <p class="text-base mx-6 my-3 text-left">
                            <strong>Lugar: </strong>{{ aviso.lugar }}
                        </p>                                                                       
                    </div>
                </li>
            </ul>
            </div>            
        </div>
    </SecreLayout>
</template>