<?php

use App\Models\Diary;
use App\Models\User;
use App\Models\Artist;
use App\Models\Comment;
// use App\Models\DiaryLike;
use App\Models\Like;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\facades\Storage;

beforeEach(function() {
    $this->owner = User::factory()->create();
    $this->viewer = User::factory()->create();
    $this->artist = Artist::factory()->create();
});

it('みんなの日記に公開設定の日記だけが表示され、必要情報が見える', function() {

    $public = Diary::factory()->for($this->owner)->for($this->artist)->create(['is_public' => true]);
    $private = Diary::factory()->for($this->owner)->for($this->artist)->create(['is_public' => false]);

    $response = $this->actingAs($this->viewer)->get(route('public.diaries.index'));
    $response->assertOk();

    // 公開日記の情報
    $response->assertSee($this->artist->name);
    $response->assertSee($public->happened_on->format('Y年n月j日'));
    $response->assertSee($public->body);
    $response->assertSee($this->owner->name);

    // 非公開日記は見えない
    $response->assertDontSee($private->body);

});

// index 年月、アーティストによる絞り込み
it('一覧で年月・アーティストの絞り込みができる', function () {

    $anotherArtist = Artist::factory()->create(['name' => '別アーティスト']);

    $d2024 = Diary::factory()->for($this->owner)->create([
        'artist_id' => $this->artist->id,
        'happened_on' => '2024-5-10',
        'body' => '2024年5月の本文',
        'is_public' => true
    ]);


    $d2025Another = Diary::factory()->for($this->owner)->create([
        'artist_id' => $anotherArtist->id,
        'happened_on' => '2025-01-20',
        'body' => '2025年1月別アーティスト',
        'is_public' => true
    ]);

    $this->actingAs($this->viewer);
    // 年月で絞り込み
    $resYearMonth = $this->get(route('public.diaries.index', [
                            'year' => '2024',
                            'month' => '5'
                        ]));
    $resYearMonth->assertOk();
    $resYearMonth->assertViewIs('public_diaries.index');
    $resYearMonth->assertSee('2024年5月の本文');
    $resYearMonth->assertDontSee('2025年1月別アーティスト');

    // アーティストで絞り込み
    $resArtist = $this->get(route('public.diaries.index', ['artist_id' => $anotherArtist->id]));
    $resArtist->assertOk();
    $resArtist->assertViewIs('public_diaries.index');
    $resArtist->assertSee('2025年1月別アーティスト');
    $resArtist->assertDontSee('2024年5月の本文');
});

// カバー画像表示
it('index: カバー画像があれば表示される', function () {
    Storage::fake('public');

    $diary = Diary::factory()->for($this->owner)->for($this->artist)->create(['is_public' => true]);

    $path = UploadedFile::fake()->image('cover.jpg', 1200, 800)->store('diary_images', 'public');
    $diary->images()->create(['path' => $path]);

    $response = $this->actingAs($this->viewer)->get(route('public.diaries.index'));
    $response->assertOk();
    $response->assertViewIs('public_diaries.index');

    Storage::disk('public')->assertExists($path);

    $response->assertSee('<img', false);
    $response->assertSee(Storage::url($path));
});

it('index: カバー画像がなければプレースホルダを表示する', function () {

    $diary = Diary::factory()->for($this->owner)->for($this->artist)->create(['is_public' => true]);

    $response = $this->actingAs($this->viewer)->get(route('public.diaries.index'));
    $response->assertOk();
    $response->assertViewIs('public_diaries.index');

    $response->assertSee(asset('/images/placeholder.png'));
});

// index コメント数、いいね数
it('一覧にコメント数といいね数が表示される', function () {

    $diary = Diary::factory()
        ->for($this->owner)
        ->for($this->artist)
        ->create([
            'happened_on' => '2025-01-20',
            'body' => 'コメント数といいね数テスト本文',
            'is_public' => true,
        ]);

    $likers = User::factory()->count(3)->create();
    // コメント2件
    Comment::factory()->count(2)->for($diary)->for($this->owner)->create();
    // いいね3件（複合ユニーク対策でforPairを使用）
    foreach ($likers as $liker) {
        Like::factory()->forDiary($diary, $liker)->create();
    }

    $this->actingAs($this->viewer);
    $response = $this->get(route('public.diaries.index'));
    $response->assertOk();
    $response->assertViewIs('public_diaries.index');
    // 本文が表示されている
    $response->assertSee('コメント数といいね数テスト本文');
    // コメント数、いいね数の表示確認
    $response->assertSee('2');
    $response->assertSee('3');
});

// 詳細
it('公開日記の詳細を確認でき、画像も表示される', function () {
    Storage::fake('public');

    $file1 = UploadedFile::fake()->image('one.jpg', 1200, 800);
    $file2 = UploadedFile::fake()->image('two.png', 800, 800);

    $this->actingAs($this->owner)->post(route('diaries.store'), [
        'artist_id' => $this->artist->id,
        'happened_on' => '2025-01-20',
        'body' => '詳細本文with images',
        'is_public' => true,
        'images' => [$file1, $file2],
    ])->assertRedirect(route('diaries.index'));

    $diary = Diary::where('user_id', $this->owner->id)->where('body', '詳細本文with images')->firstOrFail();

    // show画面へ
    $this->actingAs($this->viewer);
    $response = $this->get(route('public.diaries.show', $diary));

    $response->assertOk();
    $response->assertViewIs('public_diaries.show');
    $response->assertSee($this->artist->name);
    $response->assertSee($diary->happened_on->format('Y年n月j日'));
    $response->assertSee($diary->body);
    // $response->assertSee('非公開');

    // 画像：ファイルが保存されている＆HTMLにURLが出ている。
    foreach ($diary->images as $img) {
        Storage::disk('public')->assertExists($img->path);

        $url = Storage::url($img->path); // ファイルの相対パスを取得
        $response->assertSee('<img', false);
        $response->assertSee($url);
    }
});

// show コメント数、いいね数
it('詳細画面にもコメント数といいね数が表示される', function () {

    $diary = Diary::factory()
        ->for($this->owner)
        ->for($this->artist)
        ->create([
            'happened_on' => '2025-01-20',
            'body' => 'コメント数といいね数テスト本文',
            'is_public' => true,
        ]);

    $likers = User::factory()->count(3)->create();
    // コメント2件
    Comment::factory()->count(2)->for($diary)->for($this->owner)->create();
    // いいね3件（複合ユニーク対策でforPairを使用）
    foreach ($likers as $liker) {
        Like::factory()->forDiary($diary, $liker)->create();
    }

    $this->actingAs($this->viewer);
    $response = $this->get(route('public.diaries.show', $diary));
    $response->assertOk();
    $response->assertViewIs('public_diaries.show');
    // 本文が表示されている
    $response->assertSee('コメント数といいね数テスト本文');
    // コメント数、いいね数の表示確認
    $response->assertSee('2');
    $response->assertSee('3');
});

// ユーザー別日記一覧
it('ユーザー別日記一覧が表示され、必要情報が見える', function() {
    $other = User::factory()->create();
    $diary = Diary::factory()->for($this->owner)->for($this->artist)->create(['is_public' => true]);
    $otherDiary = Diary::factory()->for($other)->for($this->artist)->create(['is_public' => true]);

    $this->actingAs($this->viewer);
    $response = $this->get(route('public.diaries.user', $this->owner));
    $response->assertOk();
    $response->assertViewIs('public_diaries.user');

    // 公開日記の情報
    $response->assertSee($this->artist->name);
    $response->assertSee($diary->happened_on->format('Y年n月j日'));
    $response->assertSee($diary->body);
    $response->assertSee($this->owner->name);

    // 他ユーザーの公開日記は見えない
    $response->assertDontSee($otherDiary->body);
});

// user 年、アーティストによる絞り込み
it('ユーザ別日記一覧で年・アーティストの絞り込みができる', function () {

    $anotherArtist = Artist::factory()->create(['name' => '別アーティスト']);

    $d2024 = Diary::factory()->for($this->owner)->create([
        'artist_id' => $this->artist->id,
        'happened_on' => '2024-5-10',
        'body' => '2024年の本文',
        'is_public' => true
    ]);


    $d2025Another = Diary::factory()->for($this->owner)->create([
        'artist_id' => $anotherArtist->id,
        'happened_on' => '2025-01-20',
        'body' => '2025年別アーティスト',
        'is_public' => true
    ]);

    $this->actingAs($this->viewer);
    // 年で絞り込み
    $resYear = $this->get(route('public.diaries.user', [
        'user' => $this->owner,
        'year' => '2024',
    ]));
    $resYear->assertOk();
    $resYear->assertViewIs('public_diaries.user');
    $resYear->assertSee('2024年の本文');
    $resYear->assertDontSee('2025年別アーティスト');

    // アーティストで絞り込み
    $resArtist = $this->get(route('public.diaries.user', [
        'user' => $this->owner,
        'artist' => $anotherArtist->id,
    ]));
    $resArtist->assertOk();
    $resArtist->assertViewIs('public_diaries.user');
    $resArtist->assertSee('2025年別アーティスト');
    $resArtist->assertDontSee('2024年の本文');
});

// カバー画像表示
it('user: カバー画像があれば表示される', function () {
    Storage::fake('public');

    $diary = Diary::factory()->for($this->owner)->for($this->artist)->create(['is_public' => true]);

    $path = UploadedFile::fake()->image('cover.jpg', 1200, 800)->store('diary_images', 'public');
    $diary->images()->create(['path' => $path]);

    $this->actingAs($this->viewer);
    $response = $this->get(route('public.diaries.user', $this->owner));
    $response->assertOk();
    $response->assertViewIs('public_diaries.user');

    Storage::disk('public')->assertExists($path);

    $response->assertSee('<img', false);
    $response->assertSee(Storage::url($path));
});

it('user: カバー画像がなければプレースホルダを表示する', function () {

    $diary = Diary::factory()->for($this->owner)->for($this->artist)->create(['is_public' => true]);

    $this->actingAs($this->viewer);
    $response = $this->get(route('public.diaries.user', $this->owner));
    $response->assertOk();
    $response->assertViewIs('public_diaries.user');

    $response->assertSee(asset('/images/placeholder.png'));
});

// index コメント数、いいね数
it('user: 一覧にコメント数といいね数が表示される', function () {

    $diary = Diary::factory()
        ->for($this->owner)
        ->for($this->artist)
        ->create([
            'happened_on' => '2025-01-20',
            'body' => 'コメント数といいね数テスト本文',
            'is_public' => true,
        ]);

    $likers = User::factory()->count(3)->create();
    // コメント2件
    Comment::factory()->count(2)->for($diary)->for($this->owner)->create();
    // いいね3件（複合ユニーク対策でforPairを使用）
    foreach ($likers as $liker) {
        Like::factory()->forDiary($diary, $liker)->create();
    }

    $this->actingAs($this->viewer);
    $response = $this->get(route('public.diaries.user', $this->owner));
    $response->assertOk();
    $response->assertViewIs('public_diaries.user');
    // 本文が表示されている
    $response->assertSee('コメント数といいね数テスト本文');
    // コメント数、いいね数の表示確認
    $response->assertSee('2');
    $response->assertSee('3');
});

