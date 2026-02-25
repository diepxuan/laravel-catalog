<nav x-data="{ open: false }" class="border-b border-gray-100 bg-white print:hidden">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <div class="mx-auto max-w-7xl px-4 md:px-6 lg:px-8">
        <div class="flex w-full items-start justify-between md:h-16">
            <div class="flex-1 md:flex md:items-center">
                <!-- Logo -->
                <div class="flex min-h-16 shrink-0 items-center">
                    <a href="{{ route('home') }}">
                        <x-images.logo class="block h-9 w-auto" />
                    </a>
                </div>

                <div class="hidden min-h-16 w-full md:-my-px md:ms-10 md:flex md:space-x-8"
                    :class="{ 'block': open, 'hidden': !open }" x-transition>
                    @foreach ($menus as $menuId => $menu)
                        <div class="group relative md:flex" x-data="{ open: false }">
                            <x-nav-link href="#" :active="$this->isActive($menu->route)" class="w-full" @click="open = !open">
                                {{ __($menu->name) }}
                            </x-nav-link>
                            @if ($menu->children && count($menu->children) > 0)
                                <div x-cloak x-show="open" @click.outside="open = false" x-transition
                                    class="mt-0 bg-white md:absolute md:left-0 md:top-full md:w-48 md:space-y-2 md:rounded-lg md:border md:shadow-lg md:group-hover:block">
                                    @foreach ($menu->children as $children)
                                        @if ($children->route == 'space')
                                            @isset($children->name)
                                                <div class="block ps-4 pt-2 text-xs text-gray-400 md:px-2">
                                                    {{ __($children->name) }}
                                                </div>
                                            @else
                                                <div class="w-full border-t border-gray-200"></div>
                                            @endisset
                                        @else
                                            <x-nav-link :href="route($children->route)" :active="$this->isActive($children->route)"
                                                class="w-full border-transparent ps-4 hover:border-transparent focus:border-transparent md:px-2">
                                                {{ __($children->name) }}
                                            </x-nav-link>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="hidden min-h-16 md:ms-6 md:flex" :class="{ 'block': open, 'hidden': !open }">
                    <!-- Settings Dropdown -->
                    <div class="group relative md:flex" x-data="{ open: false }">
                        <x-nav-link href="#" class="w-full shrink-0 whitespace-nowrap" @click="open = !open">
                            {{ Auth::user()->name }}
                        </x-nav-link>
                        <div x-cloak x-show="open" @click.outside="open = false" x-transition
                            class="mt-0 bg-white md:absolute md:right-0 md:top-full md:w-48 md:space-y-2 md:rounded-lg md:border md:shadow-lg md:group-hover:block">
                            <div class="block ps-4 pt-2 text-xs text-gray-400 md:px-2">
                                {{ __('Manage Account') }}
                            </div>
                            <x-nav-link :href="route('profile.show')" :active="$this->isActive('profile.show')"
                                class="w-full border-transparent ps-4 hover:border-transparent focus:border-transparent md:px-2">
                                {{ __('Profile') }}
                            </x-nav-link>
                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-nav-link href="{{ route('api-tokens.index') }}" :active="$this->isActive('api-tokens.index')"
                                    class="w-full border-transparent ps-4 hover:border-transparent focus:border-transparent md:px-2">
                                    {{ __('API Tokens') }}
                                </x-nav-link>
                            @endif
                            <div class="border-t border-gray-200"></div>
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();"
                                    class="w-full border-transparent ps-4 hover:border-transparent focus:border-transparent md:px-2">
                                    {{ __('Log Out') }}
                                </x-nav-link>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="flex h-16 items-center md:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>
