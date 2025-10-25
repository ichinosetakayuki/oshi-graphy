<?php

namespace App\Http\Controllers;

use App\Models\DiaryLike;
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
        $likers = User::select('users.*')
                ->join('likes', 'likes.user_id', '=', 'users.id')
                ->where('likes.likeable_type', Diary::class)
                ->where('likes.likeable_id', $diary->id)
                ->orderByDesc('likes.created_at')
                ->paginate(10)
                ->withQueryString();


        return view('diaries.likes.index', compact('diary', 'likers'));
    }
    // public function index(Diary $diary)
    // {
    //     $likers = $diary->likers()
    //                     ->select('users.id', 'users.name', 'users.icon_path', 'users.profile')
    //                     ->latest('likes.created_at')
    //                     ->paginate(10)
    //                     ->withQueryString();

    //     return view('diaries.likes.index', compact('diary', 'likers'));
    // }

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
