<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexo | Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-200 antialiased min-h-screen relative overflow-hidden">

    <!-- Fondo de cuadrícula sutil -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDQwIDQwIj48ZyBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiMzMzQxNTUiIGZpbGwtb3BhY2l0eT0iMC4xIj48cGF0aCBkPSJNMCAwaDQwdjQwSDBWMHptMjAgMjBoMjB2MjBIMjBWMjB6TTAgMjBoMjB2MjBIMFYyMHoyMCAwaDIwdjIwSDIwVjB6Ii8+PC9nPjwvZz48L3N2Zz4=')] opacity-50"></div>
    
    <!-- Resplandores de fondo azul neón -->
    <div class="absolute -top-1/4 -right-1/4 w-1/2 h-1/2 bg-blue-900 rounded-full filter blur-[120px] opacity-20"></div>
    <div class="absolute -bottom-1/4 -left-1/4 w-1/2 h-1/2 bg-blue-900 rounded-full filter blur-[120px] opacity-20"></div>
    
    <!-- Líneas de acento de neón nítidas -->
    <div class="absolute top-10 left-1/4 h-[80vh] w-px bg-blue-500 rotate-[30deg] opacity-60"></div>
    <div class="absolute top-1/4 right-10 h-[80vh] w-px bg-blue-500 rotate-[150deg] opacity-60"></div>
    <div class="absolute bottom-1/4 left-10 h-[80vh] w-px bg-blue-500 rotate-[150deg] opacity-60"></div>

    <!-- Contenedor Principal Centrado -->
    <div class="relative z-10 flex items-center justify-center min-h-screen p-6">
        
        <!-- Tarjeta de Login (Gris Carbón Oscuro) -->
        <div class="bg-slate-900/95 backdrop-blur-sm p-12 rounded-3xl shadow-2xl w-full max-w-md border border-slate-800 relative shadow-[0_0_60px_-15px_rgba(59,130,246,0.3)]">
            
            <!-- Logo Nexo (Nodo Geométrico Azul Neón recreado con SVG) -->
            <div class="flex justify-center mb-10">
                <svg class="h-16 w-auto" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Nodo Central -->
                    <circle cx="50" cy="50" r="10" fill="#3b82f6" />
                    <!-- Nodos Periféricos -->
                    <circle cx="20" cy="30" r="6" fill="#3b82f6" />
                    <circle cx="80" cy="30" r="6" fill="#3b82f6" />
                    <circle cx="20" cy="70" r="6" fill="#3b82f6" />
                    <circle cx="80" cy="70" r="6" fill="#3b82f6" />
                    <!-- Líneas Conectoras -->
                    <line x1="20" y1="30" x2="50" y2="50" stroke="#3b82f6" stroke-width="3"/>
                    <line x1="80" y1="30" x2="50" y2="50" stroke="#3b82f6" stroke-width="3"/>
                    <line x1="20" y1="70" x2="50" y2="50" stroke="#3b82f6" stroke-width="3"/>
                    <line x1="80" y1="70" x2="50" y2="50" stroke="#3b82f6" stroke-width="3"/>
                </svg>
            </div>
            
            <div>
                <!-- Aqui va el contenido -->
                {{ $slot }}
            </div>
            
        </div>
    </div>
</body>
</html>