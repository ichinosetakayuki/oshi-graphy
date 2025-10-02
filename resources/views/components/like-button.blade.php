@props([
'diary',
'liked' => null,
'count' => null,
])

@php
$liked = $liked ?? ($diary->getAttribute('liked_by_me') ?? $diary->likedBy(auth()->user()));
$count = $count ?? ($diary->likes_count ?? $diary->likes()->count());
$likedUrl = route('diaries.like.store', $diary);
$unlikeUrl= route('diaries.like.destroy', $diary);
@endphp

<div
  x-data="{
    liked: @js($liked),
    count: @js($count),
    busy: false,
    async toggle() {
      if(this.busy) return;
      this.busy = true;

      const url = this.liked ? @js($unlikeUrl) : @js($likedUrl);
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
  <button type="button" x-on:click.stop="toggle" :disabled="busy" class="focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed" title="いいね！">
    <x-icons.heart x-show="!liked" size="size-5" class="text-gray-400" />
    <x-icons.heart-filled x-show="liked" size="size-5" class="text-pink-400" />
  </button>
  @if($diary->user_id === auth()->user()->id)
  <a href="{{ route('diaries.likes.index', $diary) }}">
    <span x-text="count" class="text-sm hover:cursor-pointer" title="いいね数"></span>
  </a>
  @else
  <span x-text="count" class="text-sm hover:cursor-pointer" title="いいね数"></span>
  @endif

</div>