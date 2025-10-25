<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class MigrateDiaryLikesToLikes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-diary-likes-to-likes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old diary_likes data to new likes table';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = now();
        DB::table('diary_likes')->orderBy('id')->chunk(1000, function($rows) use($now) {
            $insert = [];
            foreach($rows as $r) {
                $insert[] = [
                    'user_id' => $r->user_id,
                    'likeable_type' => \App\Models\Diary::class,
                    'likeable_id' => $r->diary_id,
                    'created_at' => $r->created_at ?? $now(),
                    'updated_at' => $r->updated_at ?? $now(),
                ];
            }
            // 重複はunique製薬で弾きたい→insertOrIgnore
            DB::table('likes')->insertOrIgnore($insert);
        });
   
        $this->info('Migration Finished');
        return self::SUCCESS;
    }
}
