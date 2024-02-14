<script setup>
import ProfeLayout from '@/Layouts/ProfeLayout.vue';

const props = defineProps({
    usuario: { type: Object },
    message: { String, default: '' },
    color: { String, default: '' },
    avisos: { type: Object },
});

</script>


<template>
    <ProfeLayout title="Inicio" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg h-5/6">
            <h2 class="font-bold text-center text-xl pt-5">Bienvenido al sistema de la telesecundaria clave: 20DTV1474D</h2>
            <h2 class="text-justify p-9">Ha iniciado sesion como docente, donde podra realizar la asignación de
                calificaciones</h2>
            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
            <!--  //Mensaje para mostrar el mensaje de que se ha borrado o agregado correctamente un alumno              -->
            <div v-if="$props.message" class="p-4 mb-4 text-sm rounded-lg" role="alert"
                :class="`text-${$props.color}-700 bg-${$props.color}-100 dark:bg-${$props.color}-200 dark:text-${$props.color}-800`">
                <span class="font-medium">
                    {{ $props.message }}
                </span>
            </div>
            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
            <!--  //Mensaje para mostrar el mensaje de que se ha borrado o agregado correctamente un tutor               -->
            <div v-if="$page.props.flash.message" class="p-4 mb-4 text-sm rounded-lg" role="alert"
                :class="`text-${$page.props.flash.color}-700 bg-${$page.props.flash.color}-100 dark:bg-${$page.props.flash.color}-200 dark:text-${$page.props.flash.color}-800`">
                <span class="font-medium">
                    {{ $page.props.flash.message }}
                </span>
            </div>
            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
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
    </ProfeLayout>
</template>