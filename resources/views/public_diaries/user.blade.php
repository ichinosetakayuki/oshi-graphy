<x-app-layout>
  <x-slot name="title">Oshi Graphy | {{ $user->name }}さんの日記一覧</x-slot>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-slot name="header">
      <h2 class="text-2xl font-semibold">{{ $user->name }}さんの日記一覧</h2>
    </x-slot>

    {{-- パンくず／戻るリンク --}}
    <nav class="text-sm text-gray-600 mb-3">
      <a href="{{ route('public.diaries.index') }}" class="hover:underline">みんなの日記</a>
      <span class="mx-1">/</span>
      <span>{{ $user->name }}さん</span>
    </nav>

  </div>



</x-app-layout>