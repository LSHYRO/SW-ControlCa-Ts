<script setup>
import ProfeLayout from '@/Layouts/ProfeLayout.vue';

const props = defineProps({
    clase: { Object: Object }
});


</script>
<template>
    <ProfeLayout title="clases">
        <div class="mt-8 bg-white p-4 shadow rounded-lg alturaM">
            <h2 class="text-black text-2xl text-center font-semibold p-5">{{ clase['materias'].materia }}</h2>
            <div class="my-1"></div> <!-- Espacio de separación -->
            <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mb-6"></div>
            <div class="py-3 flex flex-col md:flex-row md:items-start md:space-x-3 space-y-3 md:space-y-0">
                <div class="m-4">
                    <a :href="route('profe.clases')"><i class="fa-solid fa-arrow-left">  </i>   Volver</a>
                </div>
                <div>

                </div>
            </div>
        </div>
    </ProfeLayout>
</template>
<style>
.alturaM {
    min-height: 80vh;
}
</style>