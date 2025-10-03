<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class AddMoreArtistsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $artists = [
            ['name' => '松田聖子', 'kana' => 'まつだせいこ'],
            ['name' => '中森明菜', 'kana' => 'なかもりあきな'],
            ['name' => '工藤静香', 'kana' => 'くどうしずか'],
            ['name' => '小泉今日子', 'kana' => 'こいずみきょうこ'],
            ['name' => '南野陽子', 'kana' => 'みなみのようこ'],
            ['name' => '斉藤由貴', 'kana' => 'さいとうゆき'],
            ['name' => '渡辺美里', 'kana' => 'わたなべみさと'],
            ['name' => '広瀬香美', 'kana' => 'ひろせこうみ'],
            ['name' => '大黒摩季', 'kana' => 'おおぐろまき'],
            ['name' => '杏里', 'kana' => 'あんり'],
            ['name' => '荻野目洋子', 'kana' => 'おぎのめようこ'],
            ['name' => '今井美樹', 'kana' => 'いまいみき'],
            ['name' => '竹内まりや', 'kana' => 'たけうちまりや'],
            ['name' => '坂本冬美', 'kana' => 'さかもとふゆみ'],
            ['name' => '森口博子', 'kana' => 'もりぐちひろこ'],
            ['name' => 'NOKKO', 'kana' => 'のっこ'],
            ['name' => '華原朋美', 'kana' => 'かはらともみ'],
            ['name' => 'hitomi', 'kana' => 'ひとみ'],
            ['name' => '八神純子', 'kana' => 'やがみじゅんこ'],
            ['name' => '岩崎宏美', 'kana' => 'いわさきひろみ'],
            ['name' => '岩崎良美', 'kana' => 'いわさきよしみ'],
            ['name' => '小比類巻かほる', 'kana' => 'こひるいまきかほる'],
            ['name' => '渡辺美奈代', 'kana' => 'わたなべみなよ'],
            ['name' => '永井真理子', 'kana' => 'ながいまりこ'],
            ['name' => '酒井法子', 'kana' => 'さかいのりこ'],
            ['name' => '渡辺満里奈', 'kana' => 'わたなべまりな'],
            ['name' => '観月ありさ', 'kana' => 'みづきありさ'],
            ['name' => '高橋洋子', 'kana' => 'たかはしようこ'],
            ['name' => '遊佐未森', 'kana' => 'ゆさみもり'],
            ['name' => '川本真琴', 'kana' => 'かわもとまこと'],
            ['name' => '手嶌葵', 'kana' => 'てしまあおい'],
            ['name' => '松たか子', 'kana' => 'まつたかこ'],
            ['name' => 'May J.', 'kana' => 'めいじぇい'],
            ['name' => '吉川晃司', 'kana' => 'きっかわこうじ'],
            ['name' => '藤井フミヤ', 'kana' => 'ふじいふみや'],
            ['name' => '久保田利伸', 'kana' => 'くぼたとしのぶ'],
            ['name' => '玉置浩二', 'kana' => 'たまきこうじ'],
            ['name' => '槇原敬之', 'kana' => 'まきはらのりゆき'],
            ['name' => '矢沢永吉', 'kana' => 'やざわえいきち'],
            ['name' => '山下達郎', 'kana' => 'やましたたつろう'],
            ['name' => '小田和正', 'kana' => 'おだかずまさ'],
            ['name' => '佐野元春', 'kana' => 'さのもとはる'],
            ['name' => '桑田佳祐', 'kana' => 'くわたけいすけ'],
            ['name' => '福山雅治', 'kana' => 'ふくやままさはる'],
            ['name' => '布袋寅泰', 'kana' => 'ほていともやす'],
            ['name' => '長渕剛', 'kana' => 'ながぶちつよし'],
            ['name' => '浜田省吾', 'kana' => 'はまだしょうご'],
            ['name' => '井上陽水', 'kana' => 'いのうえようすい'],
            ['name' => '世良公則', 'kana' => 'せらまさのり'],
            ['name' => 'Char', 'kana' => 'ちゃー'],
            ['name' => '鈴木雅之', 'kana' => 'すずきまさゆき'],
            ['name' => '郷ひろみ', 'kana' => 'ごうひろみ'],
            ['name' => '大澤誉志幸', 'kana' => 'おおさわよしゆき'],
            ['name' => '稲葉浩志', 'kana' => 'いなばこうし'],
            ['name' => 'GACKT', 'kana' => 'がくと'],
            ['name' => '河村隆一', 'kana' => 'かわむらりゅういち'],
            ['name' => '佐藤竹善', 'kana' => 'さとうちくぜん'],
            ['name' => 'TM NETWORK', 'kana' => 'てぃーえむねっとわーく'],
            ['name' => 'TUBE', 'kana' => 'ちゅーぶ'],
            ['name' => 'LUNA SEA', 'kana' => 'るなしー'],
            ['name' => 'X JAPAN', 'kana' => 'えっくすじゃぱん'],
            ['name' => 'GLAY', 'kana' => 'ぐれい'],
            ['name' => 'BARBEE BOYS', 'kana' => 'ばーびーぼーいず'],
            ['name' => 'PRINCESS PRINCESS', 'kana' => 'ぷりんせすぷりんせす'],
            ['name' => '米米CLUB', 'kana' => 'こめこめくらぶ'],
            ['name' => 'スターダスト☆レビュー', 'kana' => 'すたーだすとれびゅー'],
            ['name' => 'ゴスペラーズ', 'kana' => 'ごすぺらーず'],
            ['name' => 'THE ALFEE', 'kana' => 'じあるふぃー'],
            ['name' => '安全地帯', 'kana' => 'あんぜんちたい'],
            ['name' => 'チューリップ', 'kana' => 'ちゅーりっぷ'],
            ['name' => 'REBECCA', 'kana' => 'れべっか'],
            ['name' => 'SHOW-YA', 'kana' => 'しょうや'],
            ['name' => 'PERSONZ', 'kana' => 'ぱーずんず'],
            ['name' => 'ZIGGY', 'kana' => 'じぎー'],
            ['name' => 'TRF', 'kana' => 'てぃーあーるえふ'],
            ['name' => 'WANDS', 'kana' => 'わんず'],
            ['name' => 'DEEN', 'kana' => 'でぃーん'],
            ['name' => 'L’Arc〜en〜Ciel', 'kana' => 'らるくあんしえる'],
            ['name' => 'UNICORN', 'kana' => 'ゆにこーん'],
            ['name' => 'エレファントカシマシ', 'kana' => 'えれふぁんとかしまし'],
            ['name' => 'JUN SKY WALKER(S)', 'kana' => 'じゅんすかいうぉーかーず'],
            ['name' => 'THE YELLOW MONKEY', 'kana' => 'ざいえろーもんきー'],
            ['name' => 'BUCK-TICK', 'kana' => 'ばくちく'],
            ['name' => 'SIAM SHADE', 'kana' => 'しゃむしぇいど'],
            ['name' => 'FIELD OF VIEW', 'kana' => 'ふぃーるどおぶびゅー'],
            ['name' => 'access', 'kana' => 'あくせす'],
            ['name' => 'PUFFY', 'kana' => 'ぱふぃー'],
            ['name' => 'Every Little Thing', 'kana' => 'えぶりりとるしんぐ'],
            ['name' => 'ORIGINAL LOVE', 'kana' => 'おりじなるらぶ'],
            ['name' => 'スチャダラパー', 'kana' => 'すちゃだらぱー'],
            ['name' => '大事MANブラザーズバンド', 'kana' => 'だいじまんぶらざーずばんど'],
            ['name' => 'シャ乱Q', 'kana' => 'しゃらんきゅー'],
            ['name' => '小沢健二', 'kana' => 'おざわけんじ'],
            ['name' => '稲垣潤一', 'kana' => 'いながきじゅんいち'],
            ['name' => '杉山清貴', 'kana' => 'すぎやまきよたか'],
            ['name' => 'さだまさし', 'kana' => 'さだまさし'],
            ['name' => '徳永英明', 'kana' => 'とくながひであき'],
            ['name' => 'T-BOLAN', 'kana' => 'てぃーぼらん'],
            ['name' => 'LINDBERG', 'kana' => 'りんどばーぐ'],
            ['name' => 'Sing Like Talking', 'kana' => 'しんぐらいくとーきんぐ'],
            ['name' => '松崎しげる', 'kana' => 'まつざきしげる'],
            ['name' => '葉加瀬太郎', 'kana' => 'はかせたろう'],
            ['name' => '角松敏生', 'kana' => 'かどまつとしき'],
            ['name' => 'サラ・オレイン', 'kana' => 'さらおれいん'],
            ['name' => '岸谷香', 'kana' => 'きしたにかおり'],
            ['name' => '松本伊代', 'kana' => 'まつもといよ'],
        ];

        $now = now();
        foreach (array_chunk($artists, 500) as $chunk) {
            $chunk = array_map(fn($a) => $a + [
                'created_at' => $now,
                'updated_at' => $now,
            ], $chunk);
            
            DB::table('artists')->insert($chunk);
        }
    }
}
