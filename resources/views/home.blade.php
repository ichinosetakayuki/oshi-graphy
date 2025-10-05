<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Oshi Graphy' }}</title>

    {{-- Favicon --}}
    {{-- asset()→public/以下のファイルへのURLを作るヘルパー関数 --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased min-h-screen bg-white dark:bg-gray-900 dark:text-gray-100 flex flex-col">

    <nav x-data="{ open: false }" class="bg-brand dark:bg-brand-dark border-b border-gray-100">
        {{-- Primary Navigation Menu --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                {{-- 左側：ロゴ＋マイページ --}}
                <div class="flex items-center space-x-8">
                    {{-- Logo --}}
                    <div class="shrink-0">
                        <a href="{{ route('dashboard') }}">
                            <x-application-logo class="block h-10 w-auto fill-current" />
                        </a>
                    </div>
                    {{-- Left Navigation (auth only) --}}
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


                {{-- Hamburger --}}
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Responsive Navigation Menu --}}
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
            </div>

    </nav>

    {{-- Page Content --}}
    <main class="max-w-5xl w-full mx-auto px-6 sm:px-10 py-6 flex-1 flex items-center justify-center">
        <div class="grid grid-cols-1 xl:grid-cols-2 lg:gap-4 motion-safe:animate-fade-up">

            {{-- 左カラム：ロゴ --}}
            <div class="flex items-center justify-center">
                <img src="{{ asset('images/Oshi_Graphy_logo.png') }}" alt="アプリロゴ" class="w-full h-auto">
            </div>

            {{-- 右カラム：キャッチコピー --}}
            <div class="flex flex-col items-center justify-center py-6 lg:text-left">
                <p class="text-gray-800 dark:text-gray-300 mb-2 sm:mb-4 tracking-wider text-lg sm:text-2xl">推しグラフィー</p>
                <h1 class="font-extrabold text-xl sm:text-3xl lg:text-5xl tracking-wide">
                    <span class="block py-2 md:py-4 lg:py-5">おとなの</span>
                    <span class="block py-2 md:py-4 lg:py-5 pl-2 sm:pl-6">
                        <span class="text-brand text-2xl sm:text-3xl lg:text-6xl" style="text-shadow: 2px 2px 2px gray;">推し活</span> を記憶し
                    </span>
                    <span class="block py-2 md:py-4 lg:py-5 pl-4 sm:pl-12">感動を共有しよう</span>
                </h1>
                <p class="text-xs lg:text-base text-gray-800 dark:text-gray-300 mt-4 md:mt-6">
                    <span class="block">ライブ参戦・写真・メモをまとめて管理。</span>
                    <span class="block">思い出を美しく残そう。</span>
                </p>
                <p class="text-xs text-gray-800 dark:text-gray-300 mt-4 md:mt-8 hover:cursor-pointer lg:hidden">新規登録は<a href="{{ route('register') }}">こちら</a></p>
            </div>
        </div>

    </main>
    {{-- footer --}}
    <footer class="bg-brand dark:bg-brand-dark">
        <div class="max-w-5xl mx-auto px-4 py-6 text-sm text-center">©️Oshi-Graphy</div>
    </footer>

</body>

</html>