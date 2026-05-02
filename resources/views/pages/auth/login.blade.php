<x-layouts::app.sesion :title="$title ?? null">
      <!-- Título y Subtítulo -->
            <h1 class="text-3xl font-bold text-white text-center mb-3">{{ __('Log in to your account') }}</h1>
            <p class="text-slate-400 text-center text-sm mb-10">{{ __('Enter your email and password below to log in') }}</p>
            
            <!-- Formulario -->
            
            <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
                @csrf    
                <!-- Campo de Email -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-slate-400">{{ __('Email address') }}</label>
                    <div class="relative">
                        <input type="email" id="email" name="email" class="block w-full bg-slate-800 border border-slate-700 rounded-lg p-4 text-slate-200 placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="email@example.com" required>
                    </div>
                </div>
                
                <!-- Campo de Contraseña -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium text-slate-400">{{ __('Password') }}</label>
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-500 hover:text-blue-400 transition">{{ __('Forgot your password?') }}</a>
                    </div>
                    <div class="relative">
                        <input type="password" id="password" name="password" class="block w-full bg-slate-800 border border-slate-700 rounded-lg p-4 text-slate-200 placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="{{ __('Password') }}" required>
                        <!-- Icono de Visibilidad (Ojo de Neón) -->
                        <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-blue-500 hover:text-blue-400 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                </div>
                
                <!-- Checkbox "Remember me" -->
                <div class="flex items-center">
                    <input type="checkbox" id="remember_me" name="remember_me" class="h-4 w-4 rounded border-slate-700 bg-slate-800 text-blue-600 focus:ring-blue-500 transition">
                    <label for="remember_me" class="ml-2 block text-sm text-slate-400">{{ __('Remember me') }}</label>
                </div>
                
                <!-- Botón de Inicio de Sesión (Azul Neón Sólido) -->
                <button type="submit" class="w-full flex justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold p-4 rounded-lg shadow-md transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900">
                    Log in
                </button>
            </form>
            
            <!-- Pie de página con enlace de registro -->
            <p class="mt-10 text-center text-sm text-slate-400">
                {{ __('Don\'t have an account?') }}
                <a href="{{ route('register') }}" class="font-medium text-blue-500 hover:text-blue-400 transition">{{ __('Sign up') }}</a>
            </p>
            
</x-layouts::app.sesion>

            
          