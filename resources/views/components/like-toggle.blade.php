@props([
{{-- 対象モデル（Diary,CommentなどEloquentモデル） --}}
'likeable',
'liked' => null,
'count' => null,
{{-- エンドポイント必須 --}}
'likeUrl',
'unlikeUrl',
{{-- オーナーID（自分の一覧リンク等に使う。省略可） --}}
'ownerId' => null,
{{-- いいね一覧へのリンク（なければnullでOK） --}}
'indexUrl' => null,
{{-- コメントが返信かどうか --}}
'isReply' => false,
])

@php
$user = auth()->user();
$liked = $liked ?? ($likeable->getAttribute('liked_by_me') ?? ($user ? $likeable->likes()->where('user_id', $user->id)->exists() : false));
$count = $count ?? ($likeable->likes_count ?? $likeable->likes()->count());
@endphp

<div
  x-data="{
    liked: @js((bool)$liked),
    count: @js((int)$count),
    busy: false,
    async toggle() {
      if(this.busy) return;
      this.busy = true;

      const url = this.liked ? @js($unlikeUrl) : @js($likeUrl);
      const method = this.liked ? 'DELETE' : 'POST';

      try {
        const res = await fetch(url,{
          method,
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
          },
          credentials: 'same-origin',
        });
        if(!res.ok) throw new Error('Request failed');
        const json = await res.json();
        this.liked = !!json.liked;
        this.count = Number(json.count);
      } catch(e) {
          console.error(e);
      } finally {
          this.busy = false;
      }
    }
  }"
  class="inline-flex items-center">
  <button type="button" x-on:click.stop="toggle" :disabled="busy" class="focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed" title="いいね！" aria-pressed="{{ $liked ? 'true' : 'false' }}">
    <x-icons.heart x-show="!liked" size="{{ $isReply ? 'size-4' : 'size-5' }}" class="text-gray-400" />
    <x-icons.heart-filled x-show="liked" size="{{ $isReply ? 'size-4' : 'size-5' }}" class="text-pink-400" />
  </button>
  @if($indexUrl && $user && $ownerId && $ownerId === $user->id)
  <a href="{{ $indexUrl }}">
    <span x-text="count" class="text-sm hover:cursor-pointer" title="いいね数"></span>
  </a>
  @else
  <span x-text="count" class="{{ $isReply ? 'text-xs' : 'text-sm' }}" title="いいね数"></span>
  @endif

</div>