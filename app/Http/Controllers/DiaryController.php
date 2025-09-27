<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Throwable;

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
            ->withCount(['comments', 'likes']) // コメント数、いいね数
            ->withExists(['likes as liked_by_me' => fn($q) => $q->where('user_id', auth()->id())])
            ->orderBy('happened_on', 'desc') // まず日付の新しい順
            ->orderBy('updated_at', 'desc') // 同じ日付の中で更新の新しい順
            ->paginate(6) // ページネーション付きで取得
            ->withQueryString(); // 次のページにも検索条件を引き継ぐ

        $minDate = Diary::min('happened_on');
        // 日付を扱う時はCarbonが便利
        $minYear = $minDate ? Carbon::parse($minDate)->year : 2021;
        $years = range(now()->year, $minYear);
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
            'images' => 'nullable|array',
            // images.*とすることで,複数ファイル（配列）をチェック可能
            // image:画像かどうか、mimes:許可する拡張子、max:5120 5MB
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:5120',
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
                $ext = strtolower($imageFile->getClientOriginalExtension());
                $filename = $diary->id . '_' . now()->format('YmdHis') . '_' . uniqid() . '.' . $ext;
                $path = $imageFile->storeAs('diary_images', $filename, 'public');
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
        $diary->load(['user', 'comments.user'])
            ->loadCount(['comments','likes'])
            ->loadExists([
                'likes as liked_by_me' => fn($q) => $q->where('user_id', auth()->id())
            ]);

        return view('diaries.show', compact('diary'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Diary $diary)
    {
        return view('diaries.edit', compact('diary'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Diary $diary)
    {
        $validated = $request->validate([
            'happened_on' => 'required|date',
            // exists:artists,id→存在しないartist_idが入らないようにする
            'artist_id' => 'required|integer|exists:artists,id',
            'body' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:5120',
            'is_public' => 'boolean',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer|distinct|exists:diary_images,id',
        ]);

        $diary->update([
            'happened_on' => $validated['happened_on'],
            'artist_id' => $validated['artist_id'],
            'body' => $validated['body'],
            'is_public' => $validated['is_public'],
        ]);

        // 画像の物理削除とDBの削除
        $deleteIds = $request->input('delete_images', []);
        if(!empty($deleteIds)) {
            $images = $diary->images()->whereIn('id', $deleteIds)->get();
            foreach($images as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $ext = strtolower($imageFile->getClientOriginalExtension());
                $filename = $diary->id . '_' . now()->format('YmdHis') . '_' . uniqid() . '.' . $ext;
                $path = $imageFile->storeAs('diary_images', $filename, 'public');
                // storage/app/public/diary_imagesに保存
                // storeは毎回ユニークなファイル名（ハッシュ由来+拡張子）を自動生成
                $diary->images()->create(['path' => $path]);
            }
        }
        

        return redirect()
            ->route('diaries.show', $diary)
            ->with('status', '日記を更新しました');
        // セッションに一時的なデータ（フラッシュデータ）を保存するメソッド
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diary $diary)
    {
        // 画像のパスだけを取り出す。all()は中身を素の配列に変換
        $paths = $diary->images()->pluck('path')->all();
        // 親を削除、画像はCASCADEで自動削除     
        $diary->delete();

        try {
            Storage::disk('public')->delete($paths); // ファイルの物理削除
        } catch (Throwable $e) { // 何かしらのエラーが起きた時だけ実行、例外＆エラーを開発車向けに表示
            Log::warning('Failed deleting diary image files', [
                'paths' => $paths,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->route('diaries.index')->with('status', '日記を削除しました。');
    }
}
