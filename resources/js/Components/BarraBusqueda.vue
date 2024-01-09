<script setup>
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
// Datos que recibira el componente
const props = defineProps({
    link: { type: String },
    ph: { type: String },
},
);
////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////
const obtenerInformacion = () => {
    const barraBusqueda = document.getElementById('barraBusqueda');
    // Inicializa Select2 en el campo de entrada
    $(barraBusqueda).select2({
        ajax: {
            url: props.link, // Ruta que maneja la búsqueda
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    query: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        placeholder: props.ph,
        minimumInputLength: 3 // Mínimo de caracteres para iniciar la búsqueda
    });
}
////////////////////////////////////////////////////////////////////////////////////////////////
</script>
<template>
    <input type="text" id="barraBusqueda" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" @change="obtenerInformacion()">
</template>