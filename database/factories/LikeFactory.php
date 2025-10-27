<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Like;
use App\Models\User;
use App\Models\Diary;
use App\Models\Comment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Like>
 */
class LikeFactory extends Factory
{
    protected $model = Like::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if($this->faker->boolean) {
            $likeable = Diary::factory()->create();
        } else {
            $likeable = Comment::factory()->create();
        }

        return [
            'user_id' => User::factory(),
            'likeable_type' => get_class($likeable),
            'likeable_id' => $likeable->id,
        ];
    }

    public function forDiary(Diary $diary, User $user): static
    {
        return $this->state(fn() => [
            'user_id' => $user->id,
            'likeable_type' => Diary::class,
            'likeable_id' => $diary->id,
        ]);
    }

    public function forComment(Comment $comment, User $user): static
    {
        return $this->state(fn() => [
            'user_id' => $user->id,
            'likeable_type' => Comment::class,
            'likeable_id' => $comment->id,
        ]);
    }

}
