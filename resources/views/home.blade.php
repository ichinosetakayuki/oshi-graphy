<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Oshi Graphy' }}</title>

    <!-- Favicon -->
    <!-- asset()→public/以下のファイルへのURLを作るヘルパー関数 -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased min-h-screen bg-white dark:bg-gray-900 flex flex-col">

    <nav x-data="{ open: false }" class="bg-brand border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                {{-- 左側：ロゴ＋マイページ --}}
                <div class="flex items-center space-x-8">
                    <!-- Logo -->
                    <div class="shrink-0">
                        <a href="{{ route('dashboard') }}">
                            <x-application-logo class="block h-10 w-auto fill-current" />
                        </a>
                    </div>
                    <!-- Left Navigation (auth only) -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        @auth
                        <x-nav-link :href="route('diaries.index')" :active="request()->routeIs('diaries.index')">
                            {{ __('マイページ') }}
                        </x-nav-link>
                        @endauth
                    </div>
                </div>

                {{-- 右側:ログイン／新規登録（未ログイン時のみ） --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @guest
                    <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
                        {{ __('ログイン') }}
                    </x-nav-link>
                    <x-nav-link :href="route('register')" :active="request()->routeIs('register')">
                        {{ __('新規登録') }}
                    </x-nav-link>
                    @endguest
                </div>

                <!-- Settings Dropdown -->

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    @auth

                    <x-responsive-nav-link :href="route('diaries.index')" :active="request()->routeIs('diaries.index')">
                        {{ __('マイページ') }}
                    </x-responsive-nav-link>

                    @else
                    <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                        {{ __('ログイン') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                        {{ __('新規登録') }}
                    </x-responsive-nav-link>
                    @endauth

                </div>

                <!-- Responsive Settings Options -->

            </div>

    </nav>

    <!-- Page Content -->
    <main class="max-w-5xl w-full mx-auto px-6 sm:px-10 py-10 flex-1">
        <div class="grid grid-rows-2 lg:grid-rows-1 lg:grid-cols-2 gap-8">
            
            {{-- 左カラム：ロゴ --}}
            <div class="flex items-center justify-center">
                <img src="{{ asset('images/Oshi_Graphy_logo.png') }}" alt="アプリロゴ" class="w-40 sm:w-52 lg:w-64 max-w-full h-auto">
            </div>

            {{-- 右カラム：キャッチコピー --}}
            <div class="flex items-center justify-center text-center lg:text-left">
                <h1 class="hero-copy text-balance">
                    <span class="block">おとなの</span>
                    <span class="block">
                        <span class="em-highlight">推し活</span>を記憶し
                    </span>
                    <span class="block">感動を共有しよう</span>
                </h1>
            </div>
        </div>

    </main>
    <!-- footer -->
    <footer class="bg-brand">
        <div class="max-w-5xl mx-auto px-4 py-6 text-sm text-center">©️Oshi-Graphy</div>
    </footer>

</body>

</html>