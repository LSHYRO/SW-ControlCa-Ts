<template>
    <div class="w-full h-full bg-gray-100 fondo">
        <div class=" min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 filtro">
            <div>
                <slot name="logo" />
            </div>

            <div class="w-full sm:w-1/2 mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                <slot />
            </div>
        </div>
    </div>
</template>
<style>
.fondo {
    /* Ubicación de la imagen */
    background-image: url("https://www.gob.mx/cms/uploads/article/main_image/113085/telesecundaria-01.jpg");
    /* Para que la imagen de fondo se adapte al tamaño de la pantalla */
    background-size: cover;    
}

.filtro {
    backdrop-filter: blur(4px);
}

</style>