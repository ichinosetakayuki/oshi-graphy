<x-app-layout>
    <x-slot name="title">Oshi Graphy | 日記作成</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-slot name="header">
            <h1 class="text-2xl font-semibold mb-4">日記作成</h1>
        </x-slot>


        <form method="post" action="{{ route('diaries.store') }}" class="flex-col flex-wrap items-center gap-3 mb-5">
            @csrf
            <div class="flex">
                <label for="happened_on">日付</label>
                <input type="date" name="happened_on" id="happened_on">
                <label for="artist">アーティスト</label>
                <input type="text" name="artist" id="artist">
            </div>

            <label for="body">本文</label>
            <textarea name="body" id="body"></textarea>

            <label for="images">写真</label>
            <input type="file" name="images" multiple>

            <button type="submit">保存</button>

        </form>


    </div>
</x-app-layout>