<?php

use App\Models\User;


it('ユーザーをフォロー、アンフォローできる', function () {
    $actor = User::factory()->create();
    $target = User::factory()->create();

    $response = $this->actingAs($actor)
        ->post(route('users.follow.store', $target));

    $response->assertOk()->assertJson([
        'ok' => true,
        'following' => true,
    ]);
    $response->assertJsonStructure([
        'ok',
        'following',
        'followings_count',
        'followers_count',
        'message',
        'status_type'
    ]);

    $this->assertDatabaseHas('follows', [
        'follower_id' => $actor->id,
        'followed_id' => $target->id,
    ]);

    $response = $this->actingAs($actor)
        ->delete(route('users.follow.destroy', $target));

    $response->assertOk()->assertJson([
        'ok' => true,
        'following' => false,
    ]);
    $response->assertJsonStructure([
        'ok',
        'following',
        'followings_count',
        'followers_count',
        'message',
        'status_type'
    ]);

    $this->assertDatabaseMissing('follows', [
        'follower_id' => $actor->id,
        'followed_id' => $target->id,
    ]);
});

it('自分自身はフォローできない', function(){
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('users.follow.store', $user));

    $response->assertForbidden();
});


