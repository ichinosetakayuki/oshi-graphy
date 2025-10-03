<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;
use Illuminate\Support\Facades\Gate;


class ArtistController extends Controller
{
    public function __construct()
    {
        //
    }

    public function search(Request $request)
    {
        $q = trim((string)$request->query('q', ''));
        // $request->query('q', '')
        // URLのクエリパラメータ q を取得（例: /artists/search?q=森 なら "森"）
        // trim(...) 前後のスペースを削除 
        if($q === '') return response()->json(['items' => []]);

        $items = Artist::where(function($query) use ($q) {
            $query->where('name', 'LIKE', "%{$q}%")
                ->orWhere('kana', 'LIKE', "%{$q}%");
            })
            // artists テーブルから、name カラムが $q を含むレコードを検索。
            // % はワイルドカード → "%森%" なら「森」を含む全ての名前にマッチ。
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json(['items' => $items]);
        // 検索結果をjsonで返す

    }

    public function index()
    {
        Gate::authorize('viewAny', Artist::class);

        $artists = Artist::orderBy('kana')->paginate(20);
        return view('admin.artists.index', compact('artists'));
    }

    public function create()
    {
        Gate::authorize('create', Artist::class);

        return view('admin.artists.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Artist::class);

        $data = $request->validate([
            'name' => 'required|string|max:100|unique:artists,name',
            'kana' => 'required|string|max:100'
        ]);

        Artist::create($data);

        return redirect()
            ->route('admin.artists.index')
            ->with('success', 'アーティストを登録しました')
            ->with('status_type', 'success');
    }

    public function edit(Artist $artist)
    {
        Gate::authorize('update', $artist);

        return view('admin.artists.edit', compact('artist'));
    }

    public function update(Request $request, Artist $artist)
    {
        Gate::authorize('update', $artist);

        $data = $request->validate([
            'name' => 'required|string|max:100|unique:artists,name,' . $artist->id, // このidのデータは除く
            'kana' => 'required|string|max:100'
        ]);

        $artist->update($data);

        return redirect()
            ->route('admin.artists.index')
            ->with('status', 'アーティスト情報を更新しました。')
            ->with('status_type', 'success');
    }

    public function destroy(Artist $artist)
    {
        Gate::authorize('delete', $artist);

        $artist->delete();

        return redirect()
            ->route('admin.artists.index')
            ->with('success', 'アーティストを削除しました。')
            ->with('status_type', 'success');
    }
}
