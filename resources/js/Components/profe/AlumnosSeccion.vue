<script setup>

const props = defineProps({
    alumnos: {
        type: Object,
        default: () => ({})
    }
});

</script>
<template>
    <div>
        <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
        <!--  //Mensaje para mostrar el mensaje de que se ha borrado o agregado correctamente un tutor               -->
        <div v-if="$page.props.flash.message" class="p-4 mb-4 text-sm rounded-lg" role="alert"
            :class="`text-${$page.props.flash.color}-700 bg-${$page.props.flash.color}-100 dark:bg-${$page.props.flash.color}-200 dark:text-${$page.props.flash.color}-800`">
            <span class="font-medium">
                {{ $page.props.flash.message }}
            </span>
        </div>
        <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////// -->
        <div class="flex justify-items-end">
            <button class="bg-cyan-500 hover:bg-cyan-600 text-white font-semibold py-2 px-4 rounded mt-2">
                <i class="fa fa-check mr-2"></i> Pase de lista
            </button>
        </div>
        <div class="w-full rounded-md border-2 border-cyan-500 hover:border-cyan-600 my-4 px-2">
            <div v-for="alumno in props.alumnos" :key="alumno.idAlumno"  class="w-full border-b-2 border-cyan-500 hover:border-cyan-600 my-1 px-2">
                <p> {{ alumno.nombre_completo }} </p>
            </div>
        </div>

    </div>
</template>