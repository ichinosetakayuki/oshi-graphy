<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\Artist;
use Illuminate\Http\Request;

class DiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $year = $request->integer('year');
        $artist = $request->integer('artist');

        $diaries = $user->diaries()
            ->with(['artist', 'coverImage'])
            // when:$yearがあれば、関数を実行。if($year)と同じ
            ->when($year, fn($q) => $q->whereYear('happened_on', $year))
            ->when($artist, fn($q) => $q->where('artist_id', $artist))
            // ->with('comments') // コメント実装後に追加
            ->latest('happened_on')
            ->paginate(6) // ページネーション付きで取得
            ->withQueryString(); // 次のページにも検索条件を引き継ぐ

        $years = range(now()->year, now()->year - 5);
        // artist_tableからidがdiaries.artist_idに一致するものに絞り込む
        $artists = Artist::whereIn('id', function ($q)  use ($user) {
            $q->select('artist_id')
                ->from('diaries') // diariesからartist_idの一覧を取り出す
                ->where('user_id', $user->id) // そのユーザーが書いた日記に限定
                ->whereNotNull('artist_id');
        })->orderBy('name')
            ->get(['id', 'name']);

        return view('diaries.index', compact('diaries', 'years', 'artists', 'year', 'artist'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('diaries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'happened_on' => 'required|date',
            // exists:artists,id→存在しないartist_idが入らないようにする
            'artist_id' => 'required|integer|exists:artists,id',
            'body' => 'required|string',
            // images.*とすることで,複数ファイル（配列）をチェック可能
            // image:画像かどうか、mimes:許可する拡張子、max:5120 5MB
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,webP|max:5120',
            'is_public' => 'boolean'
        ]);

        $diary = $request->user()->diaries()->create([
            'happened_on' => $validated['happened_on'],
            'artist_id' => $validated['artist_id'],
            'body' => $validated['body'],
            'is_public' => $validated['is_public'],
        ]);

        if($request->hasFile('images')) {
            foreach($request->file('images') as $imageFile) {
                $path = $imageFile->store('diary_images', 'public');
                // storage/app/public/diary_imagesに保存
                // storeは毎回ユニークなファイル名（ハッシュ由来+拡張子）を自動生成
                $diary->images()->create(['path' => $path]);
            }
        }
        return redirect()
            ->route('diaries.index')
            ->with('status', '日記を保存しました');
            // セッションに一時的なデータ（フラッシュデータ）を保存するメソッド

    }

    /**
     * Display the specified resource.
     */
    public function show(Diary $diary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Diary $diary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Diary $diary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diary $diary)
    {
        //
    }
}
