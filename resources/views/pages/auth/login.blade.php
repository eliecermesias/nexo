<x-layouts::app.sesion :title="$title ?? null">
    <h1 class="mb-3 text-center text-2xl font-bold text-white sm:text-3xl">{{ __('Log in to your account') }}</h1>
    <p class="mb-7 text-center text-sm text-slate-400 sm:mb-10">{{ __('Enter your email and password below to log in') }}</p>

    <form method="POST" action="{{ route('login.store') }}" class="space-y-5 sm:space-y-6">
        @csrf

        <div class="space-y-2">
            <label for="email" class="block text-sm font-medium text-slate-400">{{ __('Email address') }}</label>
            <div class="relative">
                <input type="email" id="email" name="email" class="block w-full rounded-lg border border-slate-700 bg-slate-800 p-4 text-slate-200 transition placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500" placeholder="email@example.com" required>
            </div>
        </div>

        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-slate-400">{{ __('Password') }}</label>
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-500 transition hover:text-blue-400">{{ __('Forgot your password?') }}</a>
            </div>
            <div class="relative">
                <input type="password" id="password" name="password" class="block w-full rounded-lg border border-slate-700 bg-slate-800 p-4 text-slate-200 transition placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500" placeholder="{{ __('Password') }}" required>
                <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-blue-500 hover:text-blue-400 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>
            </div>
        </div>

        <div class="flex items-center">
            <input type="checkbox" id="remember_me" name="remember_me" class="h-4 w-4 rounded border-slate-700 bg-slate-800 text-blue-600 transition focus:ring-blue-500">
            <label for="remember_me" class="ml-2 block text-sm text-slate-400">{{ __('Remember me') }}</label>
        </div>

        <button type="submit" class="flex w-full justify-center rounded-lg bg-blue-600 p-4 font-bold text-white shadow-md transition-all hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 active:scale-95">
            Log in
        </button>
    </form>

    <p class="mt-7 text-center text-sm text-slate-400 sm:mt-10">
        {{ __('Don\'t have an account?') }}
        <a href="{{ route('register') }}" class="font-medium text-blue-500 transition hover:text-blue-400">{{ __('Sign up') }}</a>
    </p>
</x-layouts::app.sesion>
