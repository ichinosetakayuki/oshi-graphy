<x-app-layout>
  <x-slot name="title">Oshi Graphy | {{ $user->name }}さんの日記一覧</x-slot>

  {{-- プロフィールモーダルの呼び出し --}}
  {{-- <x-profile-modal :user="$user" :editable="false" name="profileModalUser" /> --}}

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 motion-safe:animate-fade-up">
    <x-slot name="header">
      <h2 class="text-2xl font-semibold">{{ $user->name }}さんの日記一覧</h2>
      <a href="{{ route('user.profile.show', $user) }}" class="underline">プロフィールを見る</a>
      {{-- <a href="#" class="underline" x-data x-on:click.prevent="window.dispatchEvent(new CustomEvent('open-modal', {detail: 'profileModalUser'}))">プロフィールを見る</a> --}}
    </x-slot>

    {{-- パンくず／戻るリンク --}}
    <nav class="text-sm text-gray-600 mb-3">
      <a href="{{ route('public.diaries.index') }}" class="hover:underline">みんなの日記</a>
      <span class="mx-1">/</span>
      <span>{{ $user->name }}さん</span>
    </nav>

    <form method="GET" class="flex flex-wrap items-center gap-3 mb-5 md:gap-5">
      <div class="flex gap-3 items-center">
        <label class="font-semibold">年</label>
        <select name="year" class="border rounded px-3 py-1 w-24">
          <option value="">すべて</option>
          @foreach($years as $y)
          <option value="{{ $y }}" @selected($year==$y)>{{ $y }}</option>
          @endforeach
        </select>
      </div>
      <div class="flex gap-3 items-center">
        <label class="font-semibold">アーティスト</label>
        <select name="artist" class="border rounded px-3 py-1">
          <option value="">すべて</option>
          @foreach($artists as $a)
          <option value="{{ $a->id }}" @selected($artist==$a->id)>{{ $a->name }}</option>
          @endforeach
        </select>
      </div>


      <button class="rounded px-4 py-1 bg-brand">絞り込み</button>
      @if($year || $artist)
      <a href="{{ route('public.diaries.user', $user) }}" class="text-sm underline">条件クリア</a>
      @endif
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      @forelse($diaries as $diary)
      <article onclick="window.location='{{ route('public.diaries.show', $diary) }}'" class="bg-white rounded-2xl shadow overflow-hidden hover:shadow-lg transition">
        <img src="{{ $diary->coverImage ? Storage::url($diary->coverImage->path) : asset('images/placeholder.png')}}" class="w-full h-48 object-cover" alt="日記サムネイル画像">
        <div class="p-3">
          <div class="flex justify-between text-xs text-gray-600 mb-1">
            <span>{{ $diary->happened_on?->format('Y年n月j日') }}</span>
            <span class="text-red-500">{{ $diary->artist->name ?? '-' }}</span>
          </div>
          <div class="flex items-center"><span class="text-[11px] px-2 py-0.5 rounded bg-green-500 text-white">{{ $diary->user->name }}</span>
          </div>
          <p class="text-sm line-clamp-2 mb-2">{{ $diary->body }}</p>
          <div class="flex justify-between items-center">
            <span class="text-sm">⭐️コメント({{ $diary->comments_count }})</span>
          </div>
        </div>
      </article>

      @empty
      <p class="text-gray-500">まだ日記はありません</p>
      @endforelse
    </div>

    <div class="mt-6">
      {{ $diaries->links() }} {{-- ページネーション --}}
    </div>

    {{-- 明確な「全体へ戻る」ボタン --}}
    <div class="mt-6">
      <a href="{{ route('public.diaries.index') }}"
        class="inline-block px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">みんなの日記に戻る</a>
    </div>
  </div>

</x-app-layout>