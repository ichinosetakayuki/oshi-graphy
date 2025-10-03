<x-app-layout>
    <x-slot name="title">Oshi Graphy | 管理者ページ（アーティスト一覧）</x-slot>

    <x-slot name="header">
        <div class="max-w-3xl mx-auto flex items-center gap-10 justify-center">
            <h2 class="text-base font-semibold text-brand-dark">アーティスト一覧</h2>
            <h2 class="text-base underline text-gray-300 hover:text-gray-700 hover:font-semibold"><a href="{{ route('admin.artists.create') }}">新規登録</a></h2>
        </div>
    </x-slot>

    <div class="max-w-5xl w-full mx-auto px-4 py-4 sm:py-6">
        <div class="max-w-3xl mx-auto border rounded-2xl px-4 sm:px-8 py-6 shadow bg-white space-y-4 flex justify-center">
            <table class="w-full">
                <thead class="border-b-2 border-b-gray-800">
                    <tr>
                        <th class="text-left">No.</th>
                        <th class="text-left">アーティスト名</th>
                        <th class="text-left">よみがな</th>
                        <th class="text-center">編集</th>
                        <th class="text-center">削除</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2">
                    @foreach($artists as $artist)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $artist->name }}</td>
                        <td>{{ $artist->kana }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.artists.edit', $artist) }}" class="inline-block">
                                <x-icons.pencil-square size="w-4 h-4" />
                            </a>
                        </td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('admin.artists.destroy', $artist) }}" onsubmit="return confirm('削除してよろしいですか？')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"><x-icons.trash size="w-4 h-4" /></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-6">{{ $artists->links() }}</div>
        </div>
    </div>

</x-app-layout>