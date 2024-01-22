<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    usuario: { type: Object },
    message: { String, default: '' },
    color: { String, default: '' }
});

</script>


<template>
    <AdminLayout title="Inicio" :usuario="props.usuario">
        <div class="mt-8 bg-white p-4 shadow rounded-lg h-5/6">
            <h2 class="font-bold text-center text-xl pt-5">Bienvenido al sistema de la telesecundaria clave: 20DTV1474D</h2>
            <h2 class="text-justify p-9">Ha iniciado sesion como administrador, donde podra realizar la gestión de usuarios,
                materias y clases. Para realizar dichas acciones seleccione la categoria a administrar {{ $page.props.flash.message }}</h2>
            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
            <!--  //Mensaje para mostrar el mensaje de que se ha borrado o agregado correctamente un alumno              -->
            <div v-if="$props.message" class="p-4 mb-4 text-sm rounded-lg" role="alert"
                :class="`text-${$props.color}-700 bg-${$props.color}-100 dark:bg-${$props.color}-200 dark:text-${$props.color}-800`">
                <span class="font-medium">
                    {{ $props.message }}
                </span>
            </div>
            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
        </div>
    </AdminLayout>
</template>