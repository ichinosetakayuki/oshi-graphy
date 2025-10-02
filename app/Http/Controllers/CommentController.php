<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diary;
use App\Models\Comment;
use App\Notifications\CommentPostedOnYourDiary;

class CommentController extends Controller
{
    public function store(Request $request, Diary $diary)
    {
        if(!$diary->is_public && auth()->id() !== $diary->user_id) {
            return abort(403);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:2000'
        ]);

        $comment = $diary->comments()->create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        // 通知を作成
        $owner = $diary->user;
        // 文字を丸める。文字列、開始位置、文字幅バイト数、省略記号
        $excerpt = mb_strimwidth($diary->body, 0, 40, '...');

        if($owner->id !== auth()->id()) {
            $owner->notify(new CommentPostedOnYourDiary(
                diaryId: $diary->id,
                commentId: $comment->id,
                actorUserId: auth()->id(),
                actorName: auth()->user()->name,
                excerpt: $excerpt
            ));
        }

        return back()->with('status', 'コメントを投稿しました。');
    }

    public function destroy(Comment $comment)
    {
        if(auth()->id() !== $comment->user_id) {
            abort(403);
        }

        $comment->delete();

        return back()->with('status', 'コメントを削除しました。');
    }
}
