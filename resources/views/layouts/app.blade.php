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

    {{-- Vendor CSS を先に --}}
    @stack('vendor-styles')
    {{-- Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- ↓追加 --}}
    @stack('styles')
</head>

<body class="font-sans antialiased min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 dark:text-gray-100">
    @include('layouts.navigation')

    <!-- Page Heading -->
    @isset($header)
    <header class="bg-white dark:bg-gray-700 shadow">
        <div class="max-w-5xl mx-auto py-2 px-2 sm:px-6 lg:px-8">
            {{ $header }}
        </div>
    </header>
    @endisset

    <!-- Page Content -->
    {{-- <main class="max-w-5xl w-full mx-auto px-4 py-4 sm:py-6 flex-1"> --}}
    <main class="flex-1">
        {{ $slot }}
    </main>
    <!-- footer -->
    <footer class="bg-brand dark:bg-brand-dark">
        <div class="max-w-5xl mx-auto px-4 py-6 text-sm text-center">©️Oshi-Graphy</div>
    </footer>

    {{-- sessionメッセージのトースト表示 --}}
    <x-toast :message="session('status')" :type="session('status_type', 'success')" />
    {{-- alertモーダル --}}
    <x-alert-modal name="alert" title="お知らせ" />

    {{-- ↓追加 --}}
    @stack('scripts')
</body>

</html>