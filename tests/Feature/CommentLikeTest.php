<?php

use App\Models\Comment;
use App\Models\Diary;
use App\Models\User;
use App\Models\Artist;
use App\Models\Like;

beforeEach(function() {
    $this->owner = User::factory()->create();
    $this->user = User::factory()->create();
    $this->artist = Artist::factory()->create();
});

it('コメントにいいねが作成できる', function() {
    $diary = Diary::factory()->for($this->owner)->for($this->artist)->create(['is_public' => true]);
    $comment = Comment::factory()->for($diary)->create();

    $response = $this->actingAs($this->user)->post(route('comments.like.store', $comment), ['user_id' => $this->user]);
    $response->assertOk();
    $response->assertJson([
        'ok' => true,
        'liked' => true,
    ]);
    $response->assertJsonStructure(['ok', 'liked', 'count']);
    $this->assertDatabaseHas('likes', [
        'user_id' => $this->user->id,
        'likeable_type' => Comment::class,
        'likeable_id' => $comment->id,
    ]);
});

it('コメントのいいねを取り消す', function() {
    $diary = Diary::factory()->for($this->owner)->for($this->artist)->create(['is_public' => true]);
    $comment = Comment::factory()->for($diary)->create();
    Like::factory()->forComment($comment, $this->user)->create();

    $response = $this->actingAs($this->user)->delete(route('comments.like.destroy', $comment));
    $response->assertOk();
    $response->assertJson([
        'ok' => true,
        'liked' => false,
    ]);
    $response->assertJsonStructure(['ok', 'liked', 'count']);

    $this->assertDatabaseMissing('likes', [
        'user_id' => $this->user->id,
        'likeable_type' => Comment::class,
        'likeable_id' => $comment->id,
    ]);
});


it('日記詳細画面で各コメントにいいね数が表示される', function() {
    $diary = Diary::factory()->for($this->owner)->for($this->artist)->create(['is_public' => true]);
    $comment = Comment::factory()->for($diary)->create();
    $likers = User::factory()->count(3)->create();
    foreach($likers as $liker) {
        Like::factory()->forComment($comment, $liker)->create();
    }
    $response = $this->actingAs($this->owner)->get(route('diaries.show', $diary));
    $response->assertOk();
    $response->assertSee(3);
});

it('公開日記の詳細画面で各コメントにいいね数が表示される', function () {
    $diary = Diary::factory()->for($this->owner)->for($this->artist)->create(['is_public' => true]);
    $comment = Comment::factory()->for($diary)->create();
    $likers = User::factory()->count(3)->create();
    foreach ($likers as $liker) {
        Like::factory()->forComment($comment, $liker)->create();
    }
    $response = $this->actingAs($this->user)->get(route('public.diaries.show', $diary));
    $response->assertOk();
    $response->assertSee(3);
});