<?php
use App\Models\User;
use App\Models\Diary;
use App\Models\Artist;
use App\Models\DiaryLike;

beforeEach(function() {
    $this->artist = Artist::factory()->create();
    $this->owner = User::factory()->create();
    $this->user = User::factory()->create();
});

it('いいねが作成される', function() {
    $diary = Diary::factory()->for($this->owner)->for($this->artist)->create(['is_public' => true]);

    $response = $this->actingAs($this->user)->post(route('diaries.like.store', $diary), [
        'user_id' => $this->user->id,
        'diary_id' => $diary->id,
    ]);
    $response->assertOk();
    $response->assertJson([
        'ok' => true,
        'liked' => true,
    ]);
    $response->assertJsonStructure(['ok', 'liked', 'count']);
    $this->assertDatabaseHas('diary_likes', [
        'user_id' => $this->user->id,
        'diary_id' => $diary->id,
    ]);
});

it('いいねを取り消す', function() {
    $diary = Diary::factory()->for($this->owner)->for($this->artist)->create(['is_public' => true]);
    DiaryLike::factory()->for($diary)->for($this->user)->create([]);
    
    $response = $this->actingAs($this->user)->delete(route('diaries.like.destroy', $diary));
    $response->assertOk();
    $response->assertJson([
        'ok' => true,
        'liked' => false,
    ]);
    $response->assertJsonStructure(['ok', 'liked', 'count']);
    $this->assertDatabaseMissing('diary_likes', [
        'user_id' => $this->user->id,
        'diary_id' => $diary->id,
    ]);
});

// it('has diarylike page', function () {
//     $response = $this->get('/diarylike');

//     $response->assertStatus(200);
// });
