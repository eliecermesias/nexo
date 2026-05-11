<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexalvia | Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#0B1D3A] text-slate-200 antialiased min-h-screen relative overflow-hidden">

    <!-- Fondo de cuadrícula sutil -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDQwIDQwIj48ZyBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiMzMzQxNTUiIGZpbGwtb3BhY2l0eT0iMC4xIj48cGF0aCBkPSJNMCAwaDQwdjQwSDBWMHptMjAgMjBoMjB2MjBIMjBWMjB6TTAgMjBoMjB2MjBIMFYyMHoyMCAwaDIwdjIwSDIwVjB6Ii8+PC9nPjwvZz48L3N2Zz4=')] opacity-50"></div>
    
    <!-- Resplandores de fondo azul neón -->
    <div class="absolute -top-1/4 -right-1/4 w-1/2 h-1/2 bg-[#00C2C7] rounded-full filter blur-[120px] opacity-20"></div>
    <div class="absolute -bottom-1/4 -left-1/4 w-1/2 h-1/2 bg-[#0EA5E9] rounded-full filter blur-[120px] opacity-20"></div>
    
    <!-- Líneas de acento de neón nítidas -->
    <div class="absolute top-10 left-1/4 h-[80vh] w-px bg-[#00C2C7] rotate-[30deg] opacity-60"></div>
    <div class="absolute top-1/4 right-10 h-[80vh] w-px bg-[#0EA5E9] rotate-[150deg] opacity-60"></div>
    <div class="absolute bottom-1/4 left-10 h-[80vh] w-px bg-[#00C2C7] rotate-[150deg] opacity-60"></div>

    <!-- Contenedor Principal Centrado -->
    <div class="relative z-10 flex items-center justify-center min-h-screen p-6">
        
        <!-- Tarjeta de Login (Gris Carbón Oscuro) -->
        <div class="bg-[#0B1D3A]/95 backdrop-blur-sm p-12 rounded-3xl shadow-2xl w-full max-w-md border border-[#00C2C7]/20 relative shadow-[0_0_60px_-15px_rgba(0,194,199,0.35)]">
            
            <div class="flex justify-center mb-10">
                <div class="flex flex-col items-center gap-3">
                    <span class="flex size-20 items-center justify-center rounded-2xl bg-white shadow-[0_0_34px_-10px_rgba(0,194,199,0.95)]">
                        <x-app-logo-icon class="size-16" />
                    </span>
                    <span class="text-2xl font-semibold tracking-wide text-white">Nexalvia</span>
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
