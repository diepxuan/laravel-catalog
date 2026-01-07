<nav x-data="{ open: false }" class="border-b border-gray-100 bg-white print:hidden">
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
                    @foreach (json_decode(json_encode($menus), false) as $menuId => $menu)
                        <div class="group relative md:flex" x-data="{ open: false }">
                            <x-nav-link href="#" :active="$this->isActive($menu->route)" class="w-full" @click="open = !open">
                                {{ __($menu->name) }}
                            </x-nav-link>
                            <div x-show="open" @click.outside="open = false" x-transition
                                class="mt-0 bg-white md:absolute md:left-0 md:top-full md:w-48 md:space-y-2 md:rounded-lg md:border md:shadow-lg md:group-hover:block">
                                @foreach ($menu->items as $title => $route)
                                    @if ($route == 'space')
                                        @isset($title)
                                            <div class="block ps-4 pt-2 text-xs text-gray-400 md:px-2">
                                                {{ __($title) }}
                                            </div>
                                        @else
                                            <div class="w-full border-t border-gray-200"></div>
                                        @endisset
                                    @else
                                        <x-nav-link :href="route($route)" :active="$this->isActive($route)"
                                            class="w-full border-transparent ps-4 hover:border-transparent focus:border-transparent md:px-2">
                                            {{ __($title) }}
                                        </x-nav-link>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="hidden min-h-16 md:ms-6 md:flex md:items-center"
                    :class="{ 'block': open, 'hidden': !open }">
                    <!-- Teams Dropdown -->
                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <div class="relative md:ms-3">
                            <x-dropdown align="right" width="60">
                                <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:bg-gray-50 focus:outline-none active:bg-gray-50">
                                            {{ Auth::user()->currentTeam->name }}

                                            <svg class="-me-0.5 ms-2 size-4" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                            </svg>
                                        </button>
                                    </span>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="w-60">
                                        <!-- Team Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Manage Team') }}
                                        </div>

                                        <!-- Team Settings -->
                                        <x-dropdown-link
                                            href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                            {{ __('Team Settings') }}
                                        </x-dropdown-link>

                                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                            <x-dropdown-link href="{{ route('teams.create') }}">
                                                {{ __('Create New Team') }}
                                            </x-dropdown-link>
                                        @endcan

                                        <!-- Team Switcher -->
                                        @if (Auth::user()->allTeams()->count() > 1)
                                            <div class="border-t border-gray-200"></div>

                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                {{ __('Switch Teams') }}
                                            </div>

                                            @foreach (Auth::user()->allTeams() as $team)
                                                <x-switchable-team :team="$team" />
                                            @endforeach
                                        @endif
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    <!-- Settings Dropdown -->
                    <div class="relative md:ms-3">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <button
                                        class="flex rounded-full border-2 border-transparent text-sm transition focus:border-gray-300 focus:outline-none">
                                        <img class="size-8 rounded-full object-cover"
                                            src="{{ Auth::user()->profile_photo_url }}"
                                            alt="{{ Auth::user()->name }}" />
                                    </button>
                                @else
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center rounded-md border-0 border-b-2 border-transparent bg-white px-1 pt-1 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                            {{ Auth::user()->name }}

                                            {{-- <svg class="-me-0.5 ms-2 size-4" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg> --}}
                                        </button>
                                    </span>
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Manage Account') }}
                                </div>

                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                        {{ __('API Tokens') }}
                                    </x-dropdown-link>
                                @endif

                                <div class="border-t border-gray-200"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf

                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex min-h-16 w-auto shrink-0 items-center md:hidden">
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
