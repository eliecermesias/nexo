<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexalvia - Simplifica tu Gestión Comercial</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/front.css', 'resources/js/front.js'])
</head>
<body class="front-page text-white">
    <!-- Grid Pattern Overlay -->
    <div class="fixed inset-0 grid-pattern pointer-events-none"></div>
    
    <!-- Header -->
    <header class="relative z-50 py-6">
        <div class="container mx-auto px-6">
            <nav class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-white shadow-[0_0_24px_-8px_rgba(0,194,199,0.9)]">
                        <x-app-logo-icon class="size-8" />
                    </span>
                    <span class="logo-text text-2xl text-white">Nexalvia</span>
                </div>
                
                <!-- Navigation -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#caracteristicas" class="nav-link text-gray-300 hover:text-white">Características</a>
                    <a href="#precios" class="nav-link text-gray-300 hover:text-white">Precios</a>
                    <div class="relative group">
                        <a href="#recursos" class="nav-link text-gray-300 hover:text-white flex items-center gap-1">
                            Recursos
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- CTA Buttons -->
                @if (Route::has('login'))
                    @auth
                        <div class="flex items-center gap-4">
                            <a
                                href="{{ route('dashboard') }}" class="btn-primary px-6 py-2.5 rounded-lg font-medium text-white">
                                Dashboard
                            </a>
                        </div>
                        @else
                            <div class="flex items-center gap-4">
                                {{-- <a href="#" class="hidden md:block text-gray-300 hover:text-white transition">Iniciar Sesión</a> --}}
                                <a href="{{ route('login') }}" class="btn-primary px-6 py-2.5 rounded-lg font-medium text-white">
                                    Iniciar Sesión
                                </a>
                            </div>

                    @endauth
                @endif              
            </nav>
        </div>
    </header>
    
    <!-- Hero Section -->
    <section class="relative py-20 overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="space-y-8 animate-fadeInUp opacity-0">
                    <h1 class="hero-title text-5xl lg:text-6xl xl:text-7xl text-white leading-tight">
                        Simplifica tu Gestión Comercial.
                        <span class="block mt-4">Cotizaciones, Propuestas y Cuentas de Cobro en un solo lugar.</span>
                    </h1>
                    
                    <p class="text-lg text-gray-300 max-w-xl leading-relaxed">
                        Nexalvia es la plataforma modular diseñada para empresas que exigen profesionalismo y agilidad. Optimiza tu flujo de trabajo, cierra más tratos y gestiona cobros sin esfuerzo.
                    </p>
                    
                    <div class="flex flex-wrap gap-4">
                        {{-- <a href="#" class="btn-primary px-8 py-4 rounded-lg font-semibold text-white inline-block">
                            Empieza Ahora
                        </a>
                        <a href="#" class="btn-secondary px-8 py-4 rounded-lg font-semibold text-white inline-block">
                            Ver Demostración
                        </a> --}}
                    </div>
                </div>
                
                <!-- Right Content - Dashboard Mockup -->
                <div class="relative floating-element animate-fadeInUp opacity-0 delay-200">
                    <div class="dashboard-glow"></div>
                    <div class="dashboard-mockup">
                        <!-- Dashboard Container -->
                        <div class="relative rounded-2xl overflow-hidden border border-gray-700 shadow-2xl glow-effect">
                            <!-- Dashboard Header -->
                            <div class="bg-slate-800 p-4 border-b border-gray-700">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="flex size-7 items-center justify-center rounded-lg bg-white">
                                            <x-app-logo-icon class="size-6" />
                                        </span>
                                        <span class="font-semibold text-sm">Nexalvia</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Dashboard Content -->
                            <div class="bg-gradient-to-br from-slate-900 to-slate-800 p-6">
                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <!-- KPI Card 1 -->
                                    <div class="bg-slate-800/50 rounded-lg p-4 border border-cyan-500/20">
                                        <div class="text-xs text-gray-400 mb-1">Total Revenue</div>
                                        <div class="text-2xl font-bold text-gradient">$271,906</div>
                                        <div class="text-xs text-green-400 mt-1">+12.5%</div>
                                    </div>
                                    
                                    <!-- KPI Card 2 -->
                                    <div class="bg-slate-800/50 rounded-lg p-4 border border-cyan-500/20">
                                        <div class="text-xs text-gray-400 mb-1">Active Deals</div>
                                        <div class="text-2xl font-bold text-white">156</div>
                                        <div class="text-xs text-cyan-400 mt-1">+8 new</div>
                                    </div>
                                </div>
                                
                                <!-- Chart Area -->
                                <div class="bg-slate-800/30 rounded-lg p-4 border border-cyan-500/10">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-xs text-gray-400">Revenue Trend</span>
                                        <span class="text-xs text-cyan-400">Last 30 days</span>
                                    </div>
                                    
                                    <!-- Simplified Chart -->
                                    <svg viewBox="0 0 300 120" class="w-full h-24">
                                        <defs>
                                            <linearGradient id="chartGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                                <stop offset="0%" style="stop-color:#0EA5E9;stop-opacity:0.3" />
                                                <stop offset="100%" style="stop-color:#0EA5E9;stop-opacity:0" />
                                            </linearGradient>
                                        </defs>
                                        <!-- Area fill -->
                                        <path d="M 0 100 L 0 80 Q 50 60, 75 70 T 150 50 T 225 60 T 300 40 L 300 100 Z" 
                                              fill="url(#chartGradient)"/>
                                        <!-- Line -->
                                        <path d="M 0 80 Q 50 60, 75 70 T 150 50 T 225 60 T 300 40" 
                                              stroke="#0EA5E9" 
                                              stroke-width="2" 
                                              fill="none"
                                              filter="drop-shadow(0 0 4px rgba(14, 165, 233, 0.6))"/>
                                        <!-- Data points -->
                                        <circle cx="0" cy="80" r="3" fill="#0EA5E9"/>
                                        <circle cx="75" cy="70" r="3" fill="#0EA5E9"/>
                                        <circle cx="150" cy="50" r="3" fill="#0EA5E9"/>
                                        <circle cx="225" cy="60" r="3" fill="#0EA5E9"/>
                                        <circle cx="300" cy="40" r="3" fill="#0EA5E9"/>
                                    </svg>
                                </div>
                                
                                <!-- Recent Activity -->
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center gap-3 bg-slate-800/30 rounded p-2">
                                        <div class="w-8 h-8 rounded bg-cyan-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs text-white truncate">Nueva Cotización</div>
                                            <div class="text-xs text-gray-500">Hace 5 min</div>
                                        </div>
                                        <div class="text-sm font-semibold text-cyan-400">$24,500</div>
                                    </div>
                                    
                                    <div class="flex items-center gap-3 bg-slate-800/30 rounded p-2">
                                        <div class="w-8 h-8 rounded bg-green-500/20 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs text-white truncate">Pago Recibido</div>
                                            <div class="text-xs text-gray-500">Hace 1 hora</div>
                                        </div>
                                        <div class="text-sm font-semibold text-green-400">$15,000</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="relative py-16">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="card-feature rounded-2xl p-8 animate-fadeInUp opacity-0 delay-200">
                    <div class="icon-wrapper mb-6">
                        <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-white">Cotizaciones Rápidas</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Cotización rápida, comercial, cotizaciones, propuestas y cuentas de cobro en segundos.
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="card-feature rounded-2xl p-8 animate-fadeInUp opacity-0 delay-300">
                    <div class="icon-wrapper mb-6">
                        <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-white">Propuestas Profesionales</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Profesionales propuestas, malabares de componentes de profesionales.
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="card-feature rounded-2xl p-8 animate-fadeInUp opacity-0 delay-400">
                    <div class="icon-wrapper mb-6">
                        <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-white">Cuentas de Cobro Eficientes</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Cuentas de cobro eficientes, reciben días acalización y de informes profesional.
                    </p>
                </div>
            </div>
        </div>
    </section>
    
</body>
</html>
