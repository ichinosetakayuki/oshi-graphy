<x-app-layout>
    <x-slot name="title">Oshi Graphy | {{ $user->name }}プロフィール</x-slot>

    <x-slot name="header">
        <h2 class="text-2xl font-semibold">{{ $user->name --}}さんのプロフィール</h2>
    </x-slot>


</x-app-layout>