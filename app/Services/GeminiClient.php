<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

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
        // ① AIに渡す最初のルール文
        $system = "あなたは日本語のライティングアシスタントです。"
                . "ライブ参戦日記などの文案作成を手伝って欲しい。"
                . "キーワードを元に、臨場感や感動が伝わる日記の文章を提案してください。"
                . "日時、会場、アーティスト名、印象的な曲とエピソード、同行者、ライブ後の感想といった情報を後から入力できるよう質問形式で聞いて欲しい。"
                . "自然な文章になるように、それぞれの要素の組み方や表現のバリエーションも提案してください。"
                . "出力は日本語で、1〜3段落+必要に応じて箇条書き。";

        // ② APIに渡す「会話の流れ」を作る配列
        $contents = [
            ['role' => 'user', 'parts' => [['text' => $system]]],
        ];

        // ③ 過去の会話履歴を追加
        foreach($history as $turn) {
            $contents[] = [
                'role' => $turn['role'] === 'model' ? 'model' : 'user',
                'parts' => [['text' => $turn['text']]],
            ];
        }

        // ④ 今回ユーザーが入力した内容を追加
        $contents[] = ['role' => 'user', 'parts' => [['text' => $userPrompt]]];

        // ⑤ Gemini API の呼び出し先URLを作成
        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/"
                    . config('services.gemini.model')
                    . ":generateContent?key=" . urlencode(config('services.gemini.api_key'));

        // ⑥ Geminiに送るデータ
        $payload = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 800,
            ],
        ];

        // ⑦ APIを呼び出す（20秒でタイムアウト）
        $res = HTTP::timeout(20)->post($endpoint, $$payload);

        // ⑧ 失敗したらログに残してエラーにする
        if(!$res->successful()) {
            logger()->warning('Gemini API error', [
                'status' => $res->status(),
                'body' => $res->body(),
            ]);
            throw new \RuntimeException('AI回答の生成に失敗しました。しばらくして再度お試しください。');
        }

        // ⑨ 成功したらレスポンスのJSONを取り出す
        $data = $res->json();

        // ⑩ AIが生成したテキスト部分を抜き出す
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

        // ⑪ テキストがなかったらエラーにする
        if(!is_string($text) || $text === '') {
            throw new \RuntimeException('AIから有効なテキストが返りませんでした。');
        }

        // ⑫ 最後に文字列として返す
        return $text;
    }
}
