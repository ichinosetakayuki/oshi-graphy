<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;

class ArtistController extends Controller
{
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
}
