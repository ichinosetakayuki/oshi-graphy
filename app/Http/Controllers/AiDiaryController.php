<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiClient;

class AiDiaryController extends Controller
{
    public function suggest(Request $request, GeminiClient $gemini)
    {
        $request->validate([
            'prompt' => 'required|string|max:2000'
        ]);

        $history = session()->get('ai_diary_history', []);

        try {
            $reply = $gemini->generate($history, $request->string('prompt'));
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
        }

        $history[] = ['role' => 'user', 'text' => $request->string('prompt')];
        $history[] = ['role' => 'model', 'text' => $reply];
        session()->put('ai_diary_history', $history);

        return response()->json(['ok' => true, 'reply' => $reply]);
    }

    public function reset()
    {
        session()->forget('ai_diary_history');
        return response()->json(['ok' => true]);
    }
}
