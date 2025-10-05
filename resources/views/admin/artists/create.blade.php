<x-app-layout>
    <x-slot name="title">Oshi Graphy | 管理者ページ（アーティスト登録）</x-slot>

    <x-slot name="header">
        <div class="max-w-3xl mx-auto flex items-center gap-10 justify-center">
            <h2 class="text-base underline text-gray-300 hover:text-gray-700 hover:font-semibold"><a href="{{ route('admin.artists.index') }}">アーティスト一覧</a></h2>
            <h2 class="text-base font-semibold text-brand-dark">新規登録</h2>
        </div>
    </x-slot>

    <div class="max-w-5xl w-full mx-auto px-4 py-4 sm:py-6">
        <div class="max-w-3xl mx-auto border rounded-2xl px-4 sm:px-8 py-6 shadow bg-white dark:bg-gray-800 space-y-4">
            <form method="POST" action="{{ route('admin.artists.store') }}" class="flex flex-col justify-center gap-4 w-fit">
                @csrf
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <x-form-label for="name" value="アーティスト名" class="w-32" />
                    <x-text-input name="name" id="name" :value="old('name')" class="w-72" />
                    <x-input-error :messages="$errors->get('name')" />
                </div>
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <x-form-label for="kana" value="よみがな" class="w-32" />
                    <x-text-input name="kana" id="kana" :value="old('kana')" class="w-72" />
                    <x-input-error :messages="$errors->get('kana')" />
                </div>
                <div class="flex items-center w-full justify-center">
                    <x-primary-button>登録</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>