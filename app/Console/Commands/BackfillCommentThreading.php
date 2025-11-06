<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillCommentThreading extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comments:backfill-threading {--dry : ドライラン（書き込みなし）}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'commentsテーブルの depth / path / root_id / を再計算して埋め直す';

    // 10桁ゼロ埋め
    private function seg($id)
    {
        return str_pad((string)$id, 10, '0', STR_PAD_LEFT);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dry = (bool)$this->option('dry');

        // 最小列だけ読む（メモリ節約）
        $rows = DB::table('comments')->select('id', 'parent_id')->orderBy('id')->get();
        $total = $rows->count();
        if ($total === 0) {
            $this->info('コメント0件。処理不要。');
            return self::SUCCESS;
        }

        $payload = [];

        foreach($rows as $r) {
            $isRoot = is_null($r->parent_id); // 親コメントかどうか？

            $depth = $isRoot ? 0 : 1;
            $root_id = $isRoot ? $r->id : $r->parent_id;
            $path = $isRoot ? $this->seg($r->id) : $this->seg($r->parent_id) . '/' . $this->seg($r->id);

            $payload[] = [
                'id' => (int)$r->id,
                'depth' => (int)$depth,
                'root_id' => (int)$root_id,
                'path' => $path,
            ];
        }

        $this->info("comments: {$total} 件。depth/root_id/path を再計算します…");

        if($dry) {
            $first = array_slice($payload, 0, 5);
            $last = array_slice($payload, -5);

            $this->line('---先頭5件---');
            $this->line(print_r($first, true));

            $this->line('---末尾5件---');
            $this->line(print_r($last, true));

            $this->info('上記はpayloadの先頭5件と末尾5件のみを表示しています。');
        }

        if (!$dry) {
            DB::beginTransaction(); // トランザクション開始
            try {
                foreach(array_chunk($payload, 100) as $chunk) {
                    foreach($chunk as $p) {
                        DB::table('comments')
                            ->where('id', $p['id'])
                            ->update([
                                'depth' => $p['depth'],
                                'root_id' => $p['root_id'],
                                'path' => $p['path'],
                            ]);
                    }
                }
                DB::commit();
                $this->info('バックフィル完了！(UPDATEのみ)');
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->error('更新に失敗: '.$e->getMessage());
                return self::FAILURE;
            }
        } else {
            $this->warn('ドライランのため書き込みは行いません。');
        }
        return self::SUCCESS;
    }
}
