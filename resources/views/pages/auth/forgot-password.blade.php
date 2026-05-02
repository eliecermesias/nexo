<x-layouts::app.sesion :title="__('Forgot password')">
    <div class="flex flex-col gap-6">
        <h1 class="text-3xl font-bold text-white text-center mb-3">{{ __('Forgot password') }}</h1>
        <p class="text-slate-400 text-center text-sm mb-10">{{ __('Enter your email to receive a password reset link') }}</p>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-slate-400">{{ __('Email address') }}</label>
                    <div class="relative">
                        <input type="email" id="email" name="email" class="block w-full bg-slate-800 border border-slate-700 rounded-lg p-4 text-slate-200 placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="email@example.com" required>
                    </div>
                </div>

            <button type="submit" class="w-full flex justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold p-4 rounded-lg shadow-md transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900">
                {{ __('Email password reset link') }}
            </button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
            <span>{{ __('Or, return to') }}</span>
            <a href="{{ route('login') }}" class="font-medium text-blue-500 hover:text-blue-400 transition">{{ __('log in') }}</a>
        </div>
    </div>
</x-layouts::app.sesion>
