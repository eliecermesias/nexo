<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexo - Simplifica tu Gestión Comercial</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --color-primary: #0EA5E9;
            --color-primary-dark: #0284C7;
            --color-glow: rgba(14, 165, 233, 0.5);
            --color-bg: #0F172A;
            --color-bg-card: #1E293B;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            background-attachment: fixed;
        }
        
        .logo-text {
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
        }
        
        .hero-title {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            line-height: 1.1;
        }
        
        .btn-primary {
            background: var(--color-primary);
            box-shadow: 0 0 20px var(--color-glow), 0 0 40px var(--color-glow);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: var(--color-primary-dark);
            box-shadow: 0 0 30px var(--color-glow), 0 0 60px var(--color-glow);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            border-color: var(--color-primary);
            background: rgba(14, 165, 233, 0.1);
            box-shadow: 0 0 20px rgba(14, 165, 233, 0.3);
        }
        
        .glow-effect {
            box-shadow: 0 0 30px rgba(14, 165, 233, 0.3);
        }
        
        .card-feature {
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.4s ease;
        }
        
        .card-feature:hover {
            background: rgba(30, 41, 59, 0.8);
            border-color: var(--color-primary);
            box-shadow: 0 0 40px rgba(14, 165, 233, 0.3);
            transform: translateY(-8px);
        }
        
        .dashboard-mockup {
            position: relative;
            transform: perspective(1000px) rotateY(-15deg) rotateX(5deg);
            transition: all 0.6s ease;
        }
        
        .dashboard-mockup:hover {
            transform: perspective(1000px) rotateY(-10deg) rotateX(3deg) scale(1.02);
        }
        
        .dashboard-glow {
            position: absolute;
            inset: -20px;
            background: radial-gradient(circle at center, rgba(14, 165, 233, 0.3), transparent 70%);
            filter: blur(40px);
            z-index: -1;
            animation: pulse-glow 3s ease-in-out infinite;
        }
        
        @keyframes pulse-glow {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 0.8; }
        }
        
        .floating-element {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .nav-link {
            position: relative;
            transition: color 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--color-primary);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .icon-wrapper {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(14, 165, 233, 0.1);
            border-radius: 12px;
            border: 1px solid rgba(14, 165, 233, 0.3);
            transition: all 0.3s ease;
        }
        
        .card-feature:hover .icon-wrapper {
            background: rgba(14, 165, 233, 0.2);
            box-shadow: 0 0 20px rgba(14, 165, 233, 0.4);
        }
        
        .grid-pattern {
            background-image: 
                linear-gradient(rgba(14, 165, 233, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(14, 165, 233, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #0EA5E9, #3B82F6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #0EA5E9, #3B82F6);
            mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cpath d='M20,50 L50,20 L80,50 L50,80 Z M35,50 L50,35 L65,50 L50,65 Z'/%3E%3C/svg%3E");
            mask-size: contain;
            mask-repeat: no-repeat;
            mask-position: center;
        }
    </style>
</head>
<body class="text-white">
    <!-- Grid Pattern Overlay -->
    <div class="fixed inset-0 grid-pattern pointer-events-none"></div>
    
    <!-- Header -->
    <header class="relative z-50 py-6">
        <div class="container mx-auto px-6">
            <nav class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="logo-icon"></div>
                    <span class="logo-text text-2xl text-white">Nexo</span>
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
                        Nexo es la plataforma modular diseñada para empresas que exigen profesionalismo y agilidad. Optimiza tu flujo de trabajo, cierra más tratos y gestiona cobros sin esfuerzo.
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
                                        <div class="logo-icon w-6 h-6"></div>
                                        <span class="font-semibold text-sm">Nexo</span>
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
    
    <script>
        // Trigger animations on load
        window.addEventListener('load', () => {
            document.querySelectorAll('.animate-fadeInUp').forEach(el => {
                el.style.opacity = '0';
                setTimeout(() => {
                    el.style.opacity = '1';
                }, 100);
            });
        });
        
        // Smooth scroll for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>