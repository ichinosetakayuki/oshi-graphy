<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // ユーザー
        \App\Models\User::factory()->count(5)->create();

        // アーティスト（固定リスト投入）
        $this->call(ArtistSeeder::class);

        // ダミー日記（上の固定リストを利用）
        \App\Models\Diary::factory()->count(30)->create();
    }
}
