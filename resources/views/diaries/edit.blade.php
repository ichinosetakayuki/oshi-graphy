<x-app-layout>
    <x-slot name="title">Oshi Graphy | 日記編集</x-slot>

    <x-slot name="header">
        <h2 class="text-lg sm:text-2xl font-semibold">日記編集</h2>
    </x-slot>

    <div class="max-w-5xl w-full mx-auto px-4 py-4 sm:py-6">
        <div class=" max-w-3xl mx-auto border rounded-2xl p-3 md:p-4 lg:p-8 lg:shadow bg-white dark:bg-gray-800">

            <form method="post" action="{{ route('diaries.update', $diary) }}" enctype="multipart/form-data" class="flex-col flex-wrap items-center gap-3 mb-5 w-full">
                @csrf
                @method('PUT')
                <div class="flex flex-col gap-3 md:flex-row md:gap-6">
                    {{-- 日付 --}}
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-col md:flex-row gap-2">
                            <x-form-label for="happened_on" value="日付" />
                            <x-text-input type="date" name="happened_on" id="happened_on" :value="old('happened_on', $diary->happened_on->format('Y-m-d'))" />
                        </div>
                        <x-input-error :messages="$errors->get('happened_on')" class="md:text-center" />
                    </div>

                    {{-- アーティスト --}}
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-col md:flex-row gap-2">
                            <x-form-label for="artist_id" value="アーティスト" width="w-32" class="shrink-0" />
                            <div class="w-full md:w-64">
                                <select
                                    name="artist_id"
                                    id="artist_id"
                                    class="js-artist-select focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    data-search-url="{{ route('artists.search') }}"
                                    data-placeholder="アーティストを選択..."
                                    data-min-input-length="1"
                                    data-artist-name-target="#artist_name_old"
                                    data-old-id="{{ old('artist_id', $diary->artist_id) }}"
                                    data-old-name="{{ old('artist_name', optional($diary->artist)->name) }}">
                                    @if($diary->artist)
                                    {{-- 編集画面初期表示用（oldがない通常状態で表示される） --}}
                                    <option value="{{ $diary->artist->id }}" selected>{{ $diary->artist->name }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <input type="hidden" id="artist_name_old" name="artist_name" value="{{ old('artist_name', optional($diary->artist)->name) }}">
                        <x-input-error :messages="$errors->get('artist_id')" class="md:text-center" />
                    </div>


                </div>

                {{-- 本文 --}}
                <div class="flex flex-col gap-2 mt-3">
                    <div class="flex flex-col md:flex-row gap-2 mt-3">
                        <x-form-label for="body" value="本文" class="shrink-0" />
                        <x-textarea name="body" id="body" rows="6">{{ $diary->body }}</x-textarea>
                    </div>
                    <x-input-error :messages="$errors->get('body')" class="md:text-center" />
                </div>

                {{-- 写真の登録＆プレビュー --}}
                <x-image-upload name="images" id="edit-images" previewId="edit-preview" />


                {{-- 公開設定 --}}
                <div class="mb-6 mt-3">
                    <input type="hidden" name="is_public" value="0">
                    <div class="flex gap-2">
                        <x-form-label>公開設定</x-form-label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_public" value="1" class="rounded border-gray-300 text-indigo-600" @checked(old('is_public', $diary->is_public))>
                            <span class="ml-2 font-bold text-lg">公開する</span>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('is_public')" class="mt-2" />
                </div>

                {{-- 既存写真の表示 --}}
                <div class="gap-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 mb-6">
                    @forelse($diary->images as $i => $image)
                    <div>
                        <img src="{{ Storage::url($image->path) }}" alt="日記写真">
                        <input type="checkbox" name="delete_images[]" id="delete{{ $i }}" value="{{ $image->id}}">
                        <label for="delete{{ $i }}">削除する</label>
                    </div>
                    @empty
                    <p class="text-gray-500">この日記に写真はありません</p>
                    @endforelse
                </div>

                <div class="flex items-center justify-center gap-3">
                    <x-primary-button type="submit">保存</x-primary-button>
                    <x-secondary-button x-data @click="window.location='{{ route('diaries.show', $diary)}}'">キャンセル</x-secondary-button>
                </div>

            </form>
        </div>
    </div>

    {{-- Select2によるアーティスト検索のスクリプト読み込み --}}
    <x-artist-select2-script />

</x-app-layout>