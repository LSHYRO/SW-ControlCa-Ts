<!-- component -->
<!DOCTYPE html>
<html lang="es">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title')</title>
@vite('resources/css/app.css')

<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @vite('resources/css/app.css')

</head>

<body>
    <div class="flex flex-col h-screen bg-gray-100">

        <!-- Barra de navegación superior -->
        <div class="bg-cyan-600 text-white shadow w-full p-2 flex items-center justify-between">
            <div class="flex items-center">
                <div class="md:hidden flex items-center pe-2"> <!-- Se muestra solo en dispositivos pequeños -->
                    <button id="menuBtn">
                        <i class="fas fa-bars text-white text-lg"></i> <!-- Ícono de menú -->
                    </button>
                </div>
                <div class="flex items-center"> <!-- Mostrado en todos los dispositivos -->
                    <h2 class="font-bold text-xl">Telesecundaria clave: 20DTV1474D</h2>
                </div>

            </div>

            <!-- Ícono de Notificación y Perfil -->
            <div class="space-x-5">
                <!-- Botón de Perfil -->
                <div>
                    <i class="fas fa-user text-white font-thin font-['DM Sans']"></i>
                    <i class="text-white font-['DM Sans']"> Administrador </i>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="flex-1 flex flex-wrap">
            <!-- Barra lateral de navegación (oculta en dispositivos pequeños) -->
            <div class="p-2 bg-white w-full md:w-60 flex flex-col md:flex" id="sideNav">
                <nav>
                    <div class="w-60 h-16 justify-start items-center gap-4 inline-flex">
                        <div class="w-12 h-12 relative">
                            <div class="w-12 h-12 left-0 top-0 absolute bg-zinc-300 rounded-full"></div>
                            <img class="w-12 h-12 left-0 top-0 absolute" src="{{ asset('images/perfil.png') }}" />
                        </div>
                        <div class="flex-col justify-start items-center inline-flex ">
                            <div class="text-center text-black text-lg font-normal font-['DM Sans']">Rigoberto López
                            </div>
                            <div class="text-center text-stone-950 text-sm font-normal font-['DM Sans']">SALOR850201
                            </div>
                        </div>
                    </div>
                    <!-- Señalador de ubicación -->
                    <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mt-2"></div>

                    @yield('opcionesNav')

                </nav>
                <!-- Señalador de ubicación -->
                <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mt-2"></div>
                <!-- Ítem de Cerrar Sesión -->
                <a class="block text-gray-500 py-2.5 px-4 my-2 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white mt-auto"
                    href="#">
                    <i class="fas fa-sign-out-alt mr-2"></i>Cerrar sesión
                </a>
            </div>

            <!-- Área de contenido principal -->
            <div class="flex-1 p-4 w-full md:w-1/2">
                @yield('contenido')
            </div>
        </div>
    </div>
    </div>
    @yield('scripts');
    <script>
        // Agregar lógica para mostrar/ocultar la navegación lateral al hacer clic en el ícono de menú
        const menuBtn = document.getElementById('menuBtn');
        const sideNav = document.getElementById('sideNav');

        menuBtn.addEventListener('click', () => {
            sideNav.classList.toggle(
                'hidden'); // Agrega o quita la clase 'hidden' para mostrar u ocultar la navegación lateral
        });
    </script>
</body>

</html>
