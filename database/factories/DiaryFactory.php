<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Diary;
use App\Models\User;
use App\Models\Artist;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Diary>
 */
class DiaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Diary::class;

    public function definition(): array
    {
        $jpSentences = [
            '最高のセットリストで鳥肌が立った',
            '会場の一体感がすごくて泣きそうになった',
            'MCが面白くて何度も笑ってしまった',
            '照明演出が神がかっていた',
            '久しぶりの生バンドの音に痺れた',
            '推しの衣装が可愛すぎた',
            '隣の席の人とも自然に会話が弾んだ',
            'コール&レスポンスが完璧に決まった',
            'アンコールの選曲が意外で嬉しかった',
            '終演後もしばらく余韻が消えなかった',
        ];

        // 2〜5文をランダムに繋いで段落化
        $lines = collect($jpSentences)->shuffle()->take(rand(2, 5))->values()->all();
        $paragraph = implode('。', $lines) . '。';

        return [
            // ユーザーからランダムに割り当て
            'user_id'     => User::inRandomOrder()->value('id') ?? User::factory(),
            // アーティストはSeederで投入済みの固定リストからランダムに選ぶ
            'artist_id'   => Artist::inRandomOrder()->value('id'),
            'happened_on' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'body'        => $paragraph,
            'is_public'   => $this->faker->boolean(70), // 7割は公開
        ];
    }
}
