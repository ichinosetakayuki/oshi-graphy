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
        あなたは日本語のライティングアシスタントです。ライブ参戦日記などの文案作成を簡潔に提案してください。
        - 日時、会場、アーティスト名、印象的な曲とエピソード、同行者、ライブ後の感想といった情報を後から入力できるよう質問形式で聞いて欲しい。ただし1回に質問はひとつだけ。
        - 自然な文章になるように、それぞれの要素の組み方や表現のバリエーションも提案してください
        - 出力は本文のみ、見出し、箇条書きは不要、2段落以内、各段落は2文まで
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
