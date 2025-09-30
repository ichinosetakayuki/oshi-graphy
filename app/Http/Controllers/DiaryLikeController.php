<?php

namespace App\Http\Controllers;

use App\Models\DiaryLike;
use App\Models\Diary;
use Illuminate\Http\Request;
use App\Notifications\DiaryLiked;


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

        // 同じ人が同じ日記に「いいね」を連打／つけ外した時に未読通知が何個も
        // 溜まらないように事前に重複を掃除してから新しい通知を作る設計
        if($diary->user_id !== auth()->id()) {
            // UserモデルにuseされているNotifiableトレイトが持ち通知(DatabeseNotification)へのリレーション
            $diary->user->notifications()
                        ->whereNull('read_at') //未読通知だけに限定
                        ->where('type', DiaryLiked::class) // いいね通知だけに限定
                        ->where('data->diary_id', $diary->id) // notifications.dataはJSONカラム。JSONパスで同じ日記の通知に限定
                        ->where('data->actor_user_id', auth()->id()) // 誰が押したか（actor）でさらに限定
                        ->delete(); // 以上の条件に合う重複通知を丸ごと削除。結果、新規通知だけが１件残る。

            // 新しい通知を作成
            $diary->user->notify(new DiaryLiked(
                diaryId: $diary->id,
                actorUserId: auth()->id(),
                actorName: auth()->user()->name,
            ));
        }

        return response()->json([
            'ok' => true,
            'liked' => true,
            'count' => $diary->likes()->count(),
        ]);
    }


    public function destroy(Request $request, Diary $diary)
    {
        DiaryLike::where('diary_id', $diary->id)
                ->where('user_id', $request->user()->id)
                ->delete();
        
        return response()->json([
            'ok' => true,
            'liked' => false,
            'count' => $diary->likes()->count(),
        ]);
    }
}
