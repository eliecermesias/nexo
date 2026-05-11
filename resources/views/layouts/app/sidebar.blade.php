<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="relative min-h-screen overflow-x-hidden bg-[linear-gradient(135deg,#0B1D3A_0%,#08244D_55%,#06364B_100%)] text-white antialiased">
        <div class="pointer-events-none fixed inset-0 z-0 bg-[linear-gradient(rgba(14,165,233,0.04)_1px,transparent_1px),linear-gradient(90deg,rgba(0,194,199,0.035)_1px,transparent_1px)] bg-[size:50px_50px]"></div>
        <div class="pointer-events-none fixed -right-1/4 -top-1/4 h-1/2 w-1/2 rounded-full bg-[#00C2C7]/20 blur-[120px]"></div>
        <div class="pointer-events-none fixed -bottom-1/4 -left-1/4 h-1/2 w-1/2 rounded-full bg-[#0EA5E9]/20 blur-[120px]"></div>
        <div class="pointer-events-none fixed left-1/4 top-10 h-[80vh] w-px rotate-[30deg] bg-[#00C2C7] opacity-45"></div>
        <div class="pointer-events-none fixed right-10 top-1/4 h-[80vh] w-px rotate-[150deg] bg-[#0EA5E9] opacity-45"></div>
        <div class="pointer-events-none fixed bottom-1/4 left-10 h-[80vh] w-px rotate-[150deg] bg-[#00C2C7] opacity-40"></div>

        <flux:sidebar sticky collapsible="mobile" class="dark border-e border-white/10 bg-[#0B1D3A]/82 text-white shadow-[0_0_44px_-18px_rgba(0,194,199,0.7)] backdrop-blur-xl [&_[data-flux-sidebar-group-heading]]:text-sky-100/70 [&_[data-flux-sidebar-item]]:text-sky-100/80 [&_[data-flux-sidebar-item]:hover]:bg-[#00C2C7]/10 [&_[data-flux-sidebar-item]:hover]:text-white [&_[data-flux-sidebar-item][data-current]]:bg-[#0EA5E9]/16 [&_[data-flux-sidebar-item][data-current]]:text-white">
            <flux:sidebar.header class="border-b border-white/10">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-2 py-2 text-white" wire:navigate>
                    <span class="flex aspect-square size-10 items-center justify-center rounded-xl border border-[#00C2C7]/30 bg-white shadow-[0_0_28px_-8px_rgba(0,194,199,0.9)]">
                        <x-app-logo-icon class="size-8" />
                    </span>
                    <span class="text-sm font-semibold leading-none text-white">Nexalvia</span>
                </a>

                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            {{-- <div class="border-b border-white/10 px-2 py-3">
                <livewire:team-switcher />
            </div> --}}
{{--
            <flux:sidebar.nav class="px-2 py-3">
                <flux:sidebar.group :heading="__('Platform')" class="grid text-gray-400">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" class="text-gray-300 hover:bg-sky-500/10 hover:text-white data-current:bg-sky-500/15 data-current:text-white" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav> --}}

            <flux:navlist variant="outline" class="px-2 text-gray-300">
                @foreach ($menu as $item)
                    @if ($item['children']->isEmpty())
                        <flux:navlist.item :icon="$item['icon']" :href="$item['url']" :current="request()->routeIs($item['current'])" class="text-sky-100/80 hover:bg-[#00C2C7]/10 hover:text-white data-current:bg-[#0EA5E9]/16 data-current:text-white">
                            {{ $item['name'] }}
                        </flux:navlist.item>
                    @else
                        <flux:navlist.group expandable :expanded="false" :heading="$item['name']" class="lg:grid text-sky-100/80 [&_[data-flux-navlist-item]]:text-sky-100/80 [&_[data-flux-navlist-item]]:hover:bg-[#00C2C7]/10 [&_[data-flux-navlist-item]]:hover:text-white [&_[data-flux-navlist-item][data-current]]:bg-[#0EA5E9]/16 [&_[data-flux-navlist-item][data-current]]:text-white">
                            @foreach ($item['children'] as $child)
                                <flux:navlist.item :icon="$child['icon']" :href="$child['url']" class="text-sky-100/80 hover:bg-[#00C2C7]/10 hover:text-white data-current:bg-[#0EA5E9]/16 data-current:text-white">
                                    {{ $child['name'] }}
                                </flux:navlist.item>
                            @endforeach
                        </flux:navlist.group>
                    @endif
                @endforeach
            </flux:navlist>

            <flux:spacer />

            <flux:sidebar.nav class="border-t border-white/10 px-2 py-3">
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank" class="text-sky-100/60 hover:bg-[#00C2C7]/10 hover:text-white">
                    {{ __('Repository') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank" class="text-sky-100/60 hover:bg-[#00C2C7]/10 hover:text-white">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <div class="border-t border-white/10 p-2">
                <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
            </div>
        </flux:sidebar>

        <flux:header class="border-b border-white/10 bg-[#0B1D3A]/86 text-white shadow-[0_0_48px_-20px_rgba(0,194,199,0.7)] backdrop-blur-xl lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
