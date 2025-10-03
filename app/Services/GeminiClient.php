<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiClient
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function generate(array $history, string $userPrompt): string
    {
        //  AIに渡す最初のルール文
        $system = <<<TXT
        あなたは日本語のライティングアシスタントです。
        ユーザーが入力した情報（日時、場所、アーティスト名、曲、印象に残ったことなど）を理解し、
        必ず最初から推し活日記の文案を作成してください。
        - 出力は本文のみ（見出しや箇条書きは不要）。
        - 2段落以内、各段落は2文まで。
        - 情報が不足して文案が自然に作れない場合のみ、必要な要素を一つだけ質問してください。
        - ユーザーからの情報をもとに、アーティストの関連情報をWebで調べて文案作成に役立ててください。
        - 情報が揃ったら、必ず文案を生成して返してください。
        - 文案を作る時は、自然でバリエーションのある日本語表現を心がけてください。
        TXT;

        // 過去の会話履歴を追加
        $contents = [];
        foreach ($history as $turn) {
            $contents[] = [
                'role' => $turn['role'] === 'model' ? 'model' : 'user',
                'parts' => [['text' => $turn['text']]],
            ];
        }
        //  今回ユーザーが入力した内容を追加
        $contents[] = ['role' => 'user', 'parts' => [['text' => $userPrompt]]];

        //  Gemini API の呼び出し先URLを作成
        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/"
            . config('services.gemini.model') . ":generateContent";

        //  Geminiに送るデータ
        $payload = [
            'systemInstruction' => [
                'parts' => [['text' => $system]],
            ],
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 256,
                'topP' => 0.9,
                'topK' => 40,
                'thinkingConfig' => ['thinkingBudget' => 0],
            ],
        ];
        //  APIを呼び出す（45秒でタイムアウト）
        $res = Http::withHeaders([
            'x-goog-api-key' => config('services.gemini.api_key'),
            ])
            ->connectTimeout(5)
            ->timeout(45)
            ->retry(2, 800, throw: false)
            ->post($endpoint, $payload);


        //  失敗したらログに残してエラーにする
        if (!$res->successful()) {
            Log::warning('Gemini API error', [
                'status' => $res->status(),
                'body' => $res->body(),
            ]);
            throw new \RuntimeException('AI回答の生成に失敗しました。しばらくして再度お試しください。');
        }

        //  成功したらレスポンスのJSONを取り出す
        $data = $res->json();

        //  AIが生成したテキスト部分を抜き出す
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

        // ⑪テキストがなかったらエラーにする
        if (!is_string($text) || $text === '') {
            throw new \RuntimeException('AIから有効なテキストが返りません');

        }

        // ⑫ 最後に文字列として返す
        return $text;
    }
}
