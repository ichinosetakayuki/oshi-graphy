<?php

use App\Models\Diary;
use App\Models\User;
use App\Models\Artist;
use App\Models\Comment;
use App\Models\DiaryLike;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\facades\Storage;

// 一覧取得のテスト
it('自分の一覧に必要情報が表示され、他人の日記は表示されない', function() {
  $me = User::factory()->create(['name' => '自分ユーザー']);
  $you = User::factory()->create(['name' => '他人ユーザー']);

  $artist = Artist::factory()->create(['name' => 'テストアーティスト']);

  $myDiary = Diary::factory()->for($me)->create([
    'artist_id' => $artist->id,
    'happened_on' => '2025-10-01',
    'body' => '自分の日記本文XYZ',
    'is_public' => false,
  ]);

  $yourDiary = Diary::factory()->for($you)->create([
    'artist_id' => $artist->id,
    'happened_on' => '2025-09-30',
    'body' => '他人の日記本文XYZ',
    'is_public' => true,
  ]);

  $response = $this->actingAs($me)->get(route('diaries.index'));

  $response->assertOk();

  // 本文
  $response->assertSee('自分の日記本文XYZ');
  $response->assertDontSee('他人の日記本文XYZ');

  $response->assertSee('テストアーティスト');
  // 日付表示→bladeの表示にあわせる
  $expectedDate = $myDiary->happened_on->format('Y年n月j日');
  $response->assertSee($expectedDate);

  $response->assertSee('非公開');


});

// index 年、アーティストによる絞り込み
it('一覧で年・アーティストの絞り込みができる', function() {
  $me = User::factory()->create();

  $artist = Artist::factory()->create(['name' => 'テストアーティスト']);

  $d2024 = Diary::factory()->create([
    'artist_id' => $artist->id,
    'happened_on' => '2024-5-10',
    'body' => '2024年の本文'
  ]);

  $anotherArtist = Artist::factory()->create(['name' => '別アーティスト']);

  $d2025AnotherArtist = Diary::factory()->create([
    'artist_id' => $anotherArtist->id,
    'happened_on' => '2025-01-20',
    'body' => '2025年別アーティスト',
  ]);

  // 年で絞り込み
  $resYear = $this->actingAs($me)->get(route('diaries.index', ['year' => '2024']));
  $resYear->assertOk();
  $resYear->assertSee('2024年の本文');
  $resYear->assertDontSee('2025年別アーティスト');

  // アーティストで絞り込み
  $resArtist = $this->actingAs($me)->get(route('diaries.index', ['artist' => $anotherArtist->id]));
  $resArtist->assertOk();
  $resArtist->assertSee('2025年別アーティスト');
  $resArtist->assertDontSee('2024年の本文');

});

// カバー画像表示
it('index: カバー画像があれば表示される', function() {
  Storage::fake('public');
  $user = User::factory()->create();
  $artist = Artist::factory()->create();
  $diary = Diary::factory()->for($user)->for($artist)->create();

  $path = UploadedFile::fake()->image('cover.jpg', 1200, 800)->store('diary_images', 'public');
  $diary->images()->create(['path' => $path]);

  $response = $this->actingAs($user)->get(route('diaries.index'));
  $response->assertOk();

  Storage::disk('public')->assertExists($path);

  $response->assertSee('<img', false);
  $response->assertSee(Storage::url($path));

});

it('index: カバー画像がなければプレースホルダを表示する', function() {
  $user = User::factory()->create();
  $artist = Artist::factory()->create();
  $diary = Diary::factory()->for($user)->for($artist)->create();

  $response = $this->actingAs($user)->get(route('diaries.index'));
  $response->assertOk();

  $response->assertSee(asset('/images/placeholder.png'));

});

// index コメント数、いいね数
it('一覧にコメント数といいね数が表示される', function() {
  $user = User::factory()->create();
  $artist = Artist::factory()->create();
  $diary = Diary::factory()
      ->for($user)
      ->for($artist)
      ->create([
        'happened_on' => '2025-01-20',
        'body' => 'コメント数といいね数テスト本文',
      ]);

  $likers = User::factory()->count(3)->create();
  // コメント2件
  Comment::factory()->count(2)->for($diary)->for($user)->create();
  // いいね3件（複合ユニーク対策でforPairを使用）
  foreach($likers as $liker) {
    DiaryLike::factory()->forPair($diary, $liker)->create();
  }

  $response = $this->actingAs($user)->get(route('diaries.index'));
  $response->assertOk();
  // 本文が表示されている
  $response->assertSee('コメント数といいね数テスト本文');
  // コメント数、いいね数の表示確認
  $response->assertSee('2');
  $response->assertSee('3');

});

// create
it('日記作成フォームの表示', function() {
  $user = User::factory()->create();
  $response = $this->actingAs($user)->get(route('diaries.create'));
  $response->assertOk();
});

// 保存(store)成功
it('日記が作成できる', function() {
  $user = User::factory()->create();
  $artist = Artist::factory()->create(['name' => 'テストアーティスト']);
  $payload = [
    'artist_id' => $artist->id,
    'happened_on' => '2025-01-20',
    'body' => '日記作成テスト',
    'is_public' => true,
  ]; 

  // POSTリクエストを送る→$responseにレスポンスが返る。
  $response = $this->actingAs($user)->post(route('diaries.store', $payload));
  // HTTPレスポンスを確認
  $response->assertRedirect(route('diaries.index')); // レスポンス(HTTP302)
  // DBに保存されたことの確認(TestCaseクラスsのメソッド)
  $this->assertDatabaseHas('diaries', [
    'user_id' => $user->id,
    'artist_id' => $artist->id,
    'happened_on' => '2025-01-20',
    'body' => '日記作成テスト',
    'is_public' => 1,
  ]);

});

// 保存(store)バリデーション
it('日記作成時にバリデーションエラーとなる', function() {
  $user = User::factory()->create();
  $response = $this->from(route('diaries.create'))
    ->actingAs($user)
    ->post(route('diaries.store'), [
      'artist_id' => 999999,
      'happened_on' => 'not-a-date',
      'body' => '',
      'is_public' => 'not-bool',
    ]);

  $response->assertRedirect(route('diaries.create'));
  $response->assertSessionHasErrors(['artist_id', 'happened_on', 'body', 'is_public']);
});

// 保存（store）画像付き
it('画像付きで作成でき、ファイルとDBが反映される', function() {
  Storage::fake('public'); // 仮想のファイルシステムを作成するメソッド
  $user = User::factory()->create();
  $artist = Artist::factory()->create(['name' => 'テストアーティスト']);

  // メモリー上のダミー画像ファイルを作成
  $file1 = UploadedFile::fake()->image('one.jpg', 1200, 800);
  $file2 = UploadedFile::fake()->image('two.png', 800, 800);

  $response = $this->actingAs($user)->post(route('diaries.store'), [
    'artist_id' => $artist->id,
    'happened_on' => '2025-01-20',
    'body' => '画像あり本文',
    'is_public' => false,
    'images' => [$file1, $file2],
  ]);

  $response->assertRedirect(route('diaries.index'));
  $response->assertSessionHasNoErrors(); // バリデーションエラーがないこと
  $diary = Diary::where('user_id', $user->id)->where('body', '画像あり本文')->first();
  expect($diary)->not()->toBeNull(); // $diaryが存在する(nullではない)ことを確認

  // 画像レコード(diary_images)の存在確認
  foreach($diary->images as $img) {
    Storage::disk('public')->assertExists($img->path);
  }

});


// 詳細
it('本人は詳細を確認でき、画像も表示される', function() {
  Storage::fake('public');

  $user = User::factory()->create();
  $artist = Artist::factory()->create();

  $file1 = UploadedFile::fake()->image('one.jpg', 1200, 800);
  $file2 = UploadedFile::fake()->image('two.png', 800, 800);

  $this->actingAs($user)->post(route('diaries.store'), [
    'artist_id' => $artist->id,
    'happened_on' => '2025-01-20',
    'body' => '詳細本文with images',
    'is_public' => false,
    'images' => [$file1, $file2],
  ])->assertRedirect(route('diaries.index'));

  $diary = Diary::where('user_id', $user->id)->where('body', '詳細本文with images')->firstOrFail();

  // show画面へ
  $response = $this->actingAs($user)->get(route('diaries.show', $diary));

  $response->assertOk();
  $response->assertSee($artist->name);
  $response->assertSee($diary->happened_on->format('Y年n月d日'));
  $response->assertSee($diary->body);
  $response->assertSee('非公開');

  // 画像：ファイルが保存されている＆HTMLにURLが出ている。
  foreach($diary->images as $img) {
    Storage::disk('public')->assertExists($img->path);

    $url = Storage::url($img->path); // ファイルの相対パスを取得
    $response->assertSee('<img', false);
    $response->assertSee($url);
  }
});

it('他人は詳細を表示できない (Policy view)', function() {
  $owner = User::factory()->create();
  $other = User::factory()->create();
  $artist = Artist::factory()->create();
  $diary = Diary::factory()->for($owner)->create(['artist_id' => $artist->id]);

  $response = $this->actingAs($other)->get(route('diaries.show', $diary));
  $response->assertForbidden();
});

it('本人は編集画面を表示できる', function() {
  $user = User::factory()->create();
  $artist = Artist::factory()->create();
  $diary = Diary::factory()->for($user)->create(['artist_id' => $artist->id]);

  $response = $this->actingAs($user)->get(route('diaries.edit', $diary));
  $response->assertOk();
  $response->assertSee($artist->name);
  $response->assertSee($diary->happened_on->format('Y-m-d'));
  $response->assertSee($diary->body);
  $response->assertSee($diary->is_public);
});

it('他人は編集画面を表示できない (Policy view)', function () {
  $owner = User::factory()->create();
  $other = User::factory()->create();
  $artist = Artist::factory()->create();
  $diary = Diary::factory()->for($owner)->create(['artist_id' => $artist->id]);

  $response = $this->actingAs($other)->get(route('diaries.edit', $diary));
  $response->assertForbidden();
});

// it('has diary page', function () {
//     $response = $this->get('/diary');

//     $response->assertStatus(200);
// });
