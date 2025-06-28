<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable
        class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 lg:dark:bg-zinc-900/50">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="#" class="mr-5 flex items-center space-x-2">
            <x-app-logo class="h-10 w-20"></x-app-logo>
            {{-- <span class="font-bold">ERMS</span> --}}
        </a>

        <flux:navlist variant="outline">
            <!-- Dashboard -->
            <flux:navlist.group heading="Platform" class="grid">
                @if(auth()->user()->hasRole(['Manager','Admin']))
                    <flux:navlist.item icon="home" href="{{ route('home') }}" :current="request()->routeIs('home')">Dashboard</flux:navlist.item>
                @else
                    <flux:navlist.item icon="home" href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')">Dashboard</flux:navlist.item>
                @endif
            </flux:navlist.group>

            <!-- Employees & Branches -->
            <flux:navlist.group heading="Organization" class="grid">
                @can('View Branches')
                    <flux:navlist.item icon="building-office" href="{{ route('branches') }}" :current="request()->routeIs('branches')">Branches</flux:navlist.item>
                @endcan

                @can('View Employees')
                    <flux:navlist.item icon="user-group" href="{{ route('employees.list') }}" :current="request()->routeIs('employees.*')">Employees</flux:navlist.item>
                    <flux:navlist.item icon="users" href="{{ route('emp.marketers') }}" :current="request()->routeIs('emp.marketers')">Digital Marketers</flux:navlist.item>
                    <flux:navlist.item icon="magnifying-glass" href="{{ route('emp.seo') }}" :current="request()->routeIs('emp.seo')">SEO Specialists</flux:navlist.item>
                    <flux:navlist.item icon="cake" href="{{ route('emp.customersupport') }}" :current="request()->routeIs('emp.customersupport')">Customer Support</flux:navlist.item>
                @endcan
            </flux:navlist.group>

            <!-- Web Projects -->
            @can('View Websites')
                <flux:navlist.group heading="Web Projects" class="grid">
                    <flux:navlist.item icon="code-bracket" href="{{ route('web.active') }}" :current="request()->routeIs('web.active')">In Progress</flux:navlist.item>
                    <flux:navlist.item icon="eye" href="{{ route('web.cancelled') }}" :current="request()->routeIs('web.cancelled')">In Review</flux:navlist.item>
                    <flux:navlist.item icon="check-circle" href="{{ route('web.completed') }}" :current="request()->routeIs('web.completed')">Delivered</flux:navlist.item>
                    <flux:navlist.item icon="clock" href="{{ route('web.paused') }}" :current="request()->routeIs('web.paused')">Delayed</flux:navlist.item>
                </flux:navlist.group>
            @endcan

            <!-- Google Ads -->
            @can('View Ads')
                <flux:navlist.group heading="Google Ads" class="grid">
                    <flux:navlist.item icon="rocket-launch" href="{{ route('ads') }}" :current="request()->routeIs('ads')">Active Ads</flux:navlist.item>
                    <flux:navlist.item icon="pause-circle" href="{{ route('ads.paused') }}" :current="request()->routeIs('ads.paused')">Paused Ads</flux:navlist.item>
                    <flux:navlist.item icon="stop-circle" href="{{ route('ads.inActive') }}" :current="request()->routeIs('ads.inActive')">Overdue Ads</flux:navlist.item>
                    <flux:navlist.item icon="chart-bar" href="{{ route('ads.clientleft') }}" :current="request()->routeIs('ads.clientleft')">Clients Left</flux:navlist.item>
                </flux:navlist.group>
@can('can see ads pyment status')
                <flux:navlist.group heading="Ads Payments" class="grid">
                    <flux:navlist.item icon="check-circle" href="{{ route('ads.pymtclrd') }}" :current="request()->routeIs('ads.pymtclrd')">Full Clear</flux:navlist.item>
                    <flux:navlist.item icon="adjustments-vertical" href="{{ route('ads.pymthalfclrd') }}" :current="request()->routeIs('ads.pymthalfclrd')">Half Clear</flux:navlist.item>
                    <flux:navlist.item icon="x-circle" href="{{ route('ads.pymtuncleared') }}" :current="request()->routeIs('ads.pymtuncleared')">Uncleared</flux:navlist.item>
                </flux:navlist.group>
@endcan
            @endcan

            <!-- Notices -->
            <flux:navlist.group heading="Notices" class="grid">
                @can('View notice')
                    <flux:navlist.item icon="megaphone" href="{{ route('notices.all') }}" :current="request()->routeIs('notices.all')">Notice for All</flux:navlist.item>
                    <flux:navlist.item icon="speaker-wave" href="{{ route('notices.specific') }}" :current="request()->routeIs('notices.specific')">Notice for @if(auth()->user()->hasRole(['Manager','Admin'])) Specific @else Me @endif</flux:navlist.item>
                @endcan
                @can('Send Notice')
              
                <flux:navlist.item icon="list-bullet" href="{{ route('notices.list') }}" :current="request()->routeIs('notices.list')">Notice List</flux:navlist.item>
                      
                @endcan
            </flux:navlist.group>

            <!-- Roles & Permissions -->
            @can('Manage Roles')
                <flux:navlist.group heading="Access Control" class="grid">
                    @can('Manage Permissions')
                        <flux:navlist.item icon="key" href="{{ route('permissions.index') }}" :current="request()->routeIs('permissions.index')">Permissions</flux:navlist.item>
                    @endcan
                    @can('Manage Roles')
                        <flux:navlist.item icon="shield-check" href="{{ route('roles.index') }}" :current="request()->routeIs('roles.index')">Roles</flux:navlist.item>
                    @endcan
                    @can('Assign Roles')
                        <flux:navlist.item icon="user-plus" href="{{ route('assignRole') }}" :current="request()->routeIs('assignRole')">Assign Role</flux:navlist.item>
                    @endcan
                </flux:navlist.group>
            @endcan
        </flux:navlist>

        <flux:spacer />
        @auth
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:navlist.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-left">
                    {{ __('global.log_out') }}
                </flux:navlist.item>
            </form>
        @endauth

        <!-- Impersonation Notice -->
        @if (Session::has('admin_user_id'))
            <div
                class="py-2 flex items-center justify-center bg-zinc-100 dark:bg-zinc-600 dark:text-white mb-6 rounded">
                <form id="stop-impersonating" class="flex flex-col items-center gap-3"
                    action="{{ route('impersonate.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <p class="text-xs">
                        {{ __('users.you_are_impersonating') }}:
                        <strong>{{ auth()->user()->name }}</strong>
                    </p>
                    <flux:button type="submit" size="sm" variant="danger" form="stop-impersonating"
                        class="!w-full !flex !flex-row">
                        <div>
                            {{ __('users.stop_impersonating') }}
                        </div>
                    </flux:button>
                </form>
            </div>
        @endif

        <!-- User Profile Dropdown -->
        @auth
            <flux:dropdown position="bottom" align="start">
                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>
                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                    @if(auth()->user()->roles->count())
                                        <span class="badge badge-primary badge-sm mt-1">{{ auth()->user()->roles->first()->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog">
                            {{ __('global.settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('global.log_out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        @endauth
    </flux:sidebar>

    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        @auth
            <flux:dropdown position="top" align="end">
                {{-- <flux:profile :initials="auth() - > user() - > initials()" icon-trailing="chevron-down" /> --}}

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                    @if(auth()->user()->roles->count())
                                        <span class="badge badge-primary badge-sm mt-1">{{ auth()->user()->roles->first()->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog">
                            {{ __('global.settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                            class="w-full">
                            {{ __('global.log_out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        @endauth
    </flux:header>

    {{ $slot }}

    @fluxScripts
    <x-livewire-alert::scripts />
    <x-livewire-alert::flash />

</body>

</html>
