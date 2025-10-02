<x-app-layout>
  <x-slot name="title">Oshi Graphy | {{ $user->name }}さんの日記一覧</x-slot>

  <x-slot name="header">
    <div class="flex flex-col sm:flex-row items-center sm:gap-3">
      <div class="flex items-center gap-1">
        <img src="{{ $user->icon_url }}" alt="アイコン画像" class="inline-block w-8 h-8 rounded-full object-cover border">
        <h2 class="text-lg sm:text-2xl font-semibold">{{ $user->name }}さんの日記一覧</h2>
      </div>
      <div>
        <a href="{{ route('user.profile.show', $user) }}" class="underline">プロフィールを見る</a>
      </div>
    </div>
  </x-slot>

  {{-- パンくず --}}
  <nav class="max-w-5xl mx-auto flex items-center text-xs text-gray-600 sm:text-base px-4 sm:px-6 lg:px-8 my-3 sm:my-5">
    <a href="{{ route('public.diaries.index') }}" class="hover:underline">みんなの日記</a>
    <span class="mx-1">/</span>
    <span>{{ $user->name }}さん</span>
  </nav>

  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-4 sm:pb-6">

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

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 motion-safe:animate-fade-up">
      @forelse($diaries as $diary)
      <article x-data @click="window.location='{{ route('public.diaries.show', $diary) }}'" class="bg-lime-50/30 border border-lime-400 rounded-2xl shadow-md overflow-hidden transform transition-transform duration-200 hover:scale-105 hover:shadow-xl">
        <img src="{{ $diary->coverImage ? Storage::url($diary->coverImage->path) : asset('images/placeholder.png')}}" class="w-full h-48 object-cover" alt="日記サムネイル画像">
        <div class="flex flex-col justify-between h-32 p-3">
          <div>
            <div class="flex justify-between text-xs text-gray-600 mb-1">
              <span>{{ $diary->happened_on?->format('Y年n月j日') }}</span>
              <span class="text-red-500">{{ $diary->artist->name ?? '-' }}</span>
            </div>
            <p class="text-sm line-clamp-2 lg:line-clamp-3 mb-2">{{ $diary->body }}</p>
          </div>
          <div class="flex justify-between items-center">
            <div class="flex items-center">
              <span class="text-[11px] px-2 py-0.5 rounded bg-green-500 text-white">{{ $diary->user->name }}</span>
            </div>
            <div class="flex items-center gap-1">
              <x-like-button :diary="$diary" :liked="$diary->liked_by_me" :count="$diary->liked_count" />
              <span class="text-sm">⭐️コメント({{ $diary->comments_count }})</span>
            </div>
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

  </div>

</x-app-layout>