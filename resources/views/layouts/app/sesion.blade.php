<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexalvia | Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative min-h-dvh overflow-x-hidden overflow-y-auto bg-[#0B1D3A] font-sans text-slate-200 antialiased">

    <!-- Fondo de cuadrícula sutil -->
    <div class="pointer-events-none fixed inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDQwIDQwIj48ZyBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiMzMzQxNTUiIGZpbGwtb3BhY2l0eT0iMC4xIj48cGF0aCBkPSJNMCAwaDQwdjQwSDBWMHptMjAgMjBoMjB2MjBIMjBWMjB6TTAgMjBoMjB2MjBIMFYyMHoyMCAwaDIwdjIwSDIwVjB6Ii8+PC9nPjwvZz48L3N2Zz4=')] opacity-50"></div>
    
    <!-- Resplandores de fondo azul neón -->
    <div class="pointer-events-none fixed -top-1/4 -right-1/4 h-1/2 w-1/2 rounded-full bg-[#00C2C7] opacity-20 blur-[120px] filter"></div>
    <div class="pointer-events-none fixed -bottom-1/4 -left-1/4 h-1/2 w-1/2 rounded-full bg-[#0EA5E9] opacity-20 blur-[120px] filter"></div>
    
    <!-- Líneas de acento de neón nítidas -->
    <div class="pointer-events-none fixed top-10 left-1/4 h-[80vh] w-px rotate-[30deg] bg-[#00C2C7] opacity-60"></div>
    <div class="pointer-events-none fixed top-1/4 right-10 h-[80vh] w-px rotate-[150deg] bg-[#0EA5E9] opacity-60"></div>
    <div class="pointer-events-none fixed bottom-1/4 left-10 h-[80vh] w-px rotate-[150deg] bg-[#00C2C7] opacity-60"></div>

    <!-- Contenedor Principal Centrado -->
    <div class="relative z-10 flex min-h-dvh items-start justify-center px-4 py-5 sm:items-center sm:px-6 sm:py-8">
        
        <!-- Tarjeta de Login (Gris Carbón Oscuro) -->
        <div class="relative w-full max-w-md rounded-3xl border border-[#00C2C7]/20 bg-[#0B1D3A]/95 p-6 shadow-2xl shadow-[0_0_60px_-15px_rgba(0,194,199,0.35)] backdrop-blur-sm sm:p-8 lg:p-10">
            
            <div class="mb-7 flex justify-center sm:mb-9">
                <div class="flex flex-col items-center gap-3">
                    <span class="flex size-16 items-center justify-center rounded-2xl bg-white shadow-[inset_0_3px_18px_rgba(11,29,58,0.22),0_0_34px_-10px_rgba(0,194,199,0.95)] sm:size-20">
                        <x-app-logo-icon class="size-12 sm:size-16" />
                    </span>
                    <span class="text-xl font-semibold tracking-wide text-white sm:text-2xl">Nexalvia</span>
                </div>
            </div>
            
            <div>
                <!-- Aqui va el contenido -->
                {{ $slot }}
            </div>
            
        </div>
    </div>
</body>
</html>
