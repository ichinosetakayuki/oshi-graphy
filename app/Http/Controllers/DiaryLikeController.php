<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Http\Request;


class DiaryLikeController extends Controller
{

    /**
     * いいねしたユーザーの一覧を生成
     */
    public function index(Diary $diary)
    {
        $likers = $diary->likers()
                ->orderByPivot('created_at', 'desc') // 中間テーブルlikesのcreated_atで並べる。
                ->paginate(10)
                ->withQueryString();

        return view('diaries.likes.index', compact('diary', 'likers'));
    }

    /**
     * いいね情報を保存、通知を作成
     */
    public function store(Request $request ,Diary $diary)
    {
        $like = $diary->likes()->firstOrCreate([
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
        $like = $diary->likes()
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
