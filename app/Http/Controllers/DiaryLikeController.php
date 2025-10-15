<?php

namespace App\Http\Controllers;

use App\Models\DiaryLike;
use App\Models\Diary;
use Illuminate\Http\Request;


class DiaryLikeController extends Controller
{

    /**
     * いいねしたユーザーの一覧を生成
     */
    public function index(Diary $diary)
    {
        $likers = $diary->likers()
                        ->select('users.id', 'users.name', 'users.icon_path', 'users.profile')
                        ->latest('diary_likes.created_at')
                        ->paginate(10)
                        ->withQueryString();

        return view('diaries.likes.index', compact('diary', 'likers'));
    }

    /**
     * いいね情報を保存、通知を作成
     */
    public function store(Request $request ,Diary $diary)
    {
        DiaryLike::firstOrCreate([
            'diary_id' => $diary->id,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'ok' => true,
            'liked' => true,
            'count' => $diary->likes()->count(),
        ]);
    }


    public function destroy(Request $request, Diary $diary)
    {
        $like = DiaryLike::where('diary_id', $diary->id)
             ->where('user_id', $request->user()->id)
             ->first();
        
        if($like) {
            $like->delete(); // モデルのdeleteなのでdeleteイベントが発火
        }
        
        return response()->json([
            'ok' => true,
            'liked' => false,
            'count' => $diary->likes()->count(),
        ]);
    }
}
