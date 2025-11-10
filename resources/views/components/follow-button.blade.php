@props([
'user',
'initialFollowing' => false,
'followersCount' => 0,
])

@php
$followUrl = route('users.follow.store', $user);
$unfollowUrl = route('users.follow.destroy', $user);
@endphp

<div
  x-data="{
    following: @js((bool)$initialFollowing),
    count: @js((int)$followersCount),
    busy: false,
    async toggle() {
      if(this.busy) return;
      this.busy = true;

      const url = this.following ? @js($unfollowUrl) : @js($followUrl);
      const method = this.following ? 'DELETE' : 'POST';

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
        this.following = !!json.following;
        this.count = Number(json.followers_count) ?? Number($this.count);
        window.dispatchEvent(new CustomEvent('toast',{
          detail: {
            message: json.message,
            type: json.status_type
          }  
        }));
      } catch(e) {
          console.error(e);
      } finally {
          this.busy = false;
      }
    }
  }"
  class="inline-flex items-center">
  <button type="button" @click.stop="toggle" :disabled="busy" class="font-semibold rounded-xl px-4 py-1 shadow-md focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed" :class="following ? 'bg-gray-200' : 'bg-brand hover:bg-brand-dark'">
    <template x-if="following"><span>フォロー中</span></template>
    <template x-if="!following"><span>フォロー</span></template>
  </button>

</div>