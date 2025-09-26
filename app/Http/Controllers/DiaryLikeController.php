<?php

namespace App\Http\Controllers;

use App\Models\DiaryLike;
use App\Models\Diary;
use App\Models\User;
use Illuminate\Http\Request;

class DiaryLikeController extends Controller
{
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
