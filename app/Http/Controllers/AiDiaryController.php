<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiClient;
use Illuminate\Support\Facades\Log;


class AiDiaryController extends Controller
{
    public function suggest(Request $request, GeminiClient $gemini)
    {
        $data = $request->validate([
            'prompt' => 'required|string|max:2000'
        ]);

        $history = session()->get('ai_diary_history', []);
        $history = array_slice($history, -6); // 直近6件に絞る（開始位置負数で末尾から）

        try {
            $reply = $gemini->generate($history, $data['prompt']);

            $history[] = ['role' => 'user', 'text' => $data['prompt']];
            $history[] = ['role' => 'model', 'text' => $reply];
            session()->put('ai_diary_history', $history);

            return response()->json(['ok' => true, 'reply' => $reply], 200);

        } catch (\Throwable $e) {
            Log::warning('AI suggest failed', ['error' => $e->getMessage()]);
            return response()->json([
                'ok' => false,
                'message' => 'AI処理に失敗しました。短い入力で再実行してください。'
            ], 422);
        }
    }

    public function reset()
    {
        session()->forget('ai_diary_history');
        return response()->json(['ok' => true]);
    }
}
