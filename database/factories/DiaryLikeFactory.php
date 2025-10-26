<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DiaryLike;
use App\Models\Diary;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DiaryLike>
 */
class DiaryLikeFactory extends Factory
{
    // protected $model = DiaryLike::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'diary_id' => Diary::factory(),
            // 'user_id' => User::factory(),
        ];
    }

    // public function forPair(Diary $diary, User $user): static
    // {
    //     return $this->state(fn() => [
    //         'diary_id' => $diary->id,
    //         'user_id' => $user->id,
    //     ]);
    // }
}
