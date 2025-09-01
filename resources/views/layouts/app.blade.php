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
    <!-- ↓追加 -->
    @stack('styles')
</head>

<body class="font-sans antialiased min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col">
    <!-- <div class="min-h-screen bg-gray-100 dark:bg-gray-900"> -->
    @include('layouts.navigation')
    <!-- <header class="bg-brand">
        <div class="max-w-5xl mx-auto px-4 py-3 flex justify-between">
            <a href="{{ route('diaries.index') }}" class="font-bold text-xl">Oshi-Graphy</a>
            <nav class="flex gap-5">
                <a href="{{ route('diaries.index') }}">マイページ</a>
                <a href="{{ route('diaries.create') }}">日記作成</a>
            </nav>
        </div>
    </header> -->

    {{-- フラッシュメッセージ --}}
    @if(session('status'))
    <div class="bg-green-100 text-green-800">
        <div class="max-w-5xl mx-auto px-4 py-2">{{ session('status') }}</div>
    </div>
    @endif

    <!-- Page Heading -->
    @isset($header)
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            {{ $header }}
        </div>
    </header>
    @endisset

    <!-- Page Content -->
    <main class="max-w-5xl mx-auto px-4 py-6 flex-1">
        {{ $slot }}
    </main>
    <!-- footer -->
    <footer class="bg-brand">
        <div class="max-w-5xl mx-auto px-4 py-6 text-sm text-center">©️Oshi-Graphy</div>
    </footer>
    <!-- ↓追加 -->
     @stack('scripts')
</body>

</html>