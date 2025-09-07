<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Artist;

class ArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $artists = [
            ['name' => '森高千里', 'kana' => 'もりたかちさと'],
            ['name' => '松任谷由実', 'kana' => 'まつとうやゆみ'],
            ['name' => '中島みゆき', 'kana' => 'なかじまみゆき'],
            ['name' => '安室奈美恵', 'kana' => 'あむろなみえ'],
            ['name' => 'Perfume', 'kana' => 'ぱふゅーむ'],
            ['name' => 'サザンオールスターズ', 'kana' => 'さざんおーるすたーず'],
            ['name' => 'スピッツ', 'kana' => 'すぴっつ'],
            ['name' => 'B’z', 'kana' => 'びーず'],
            ['name' => 'Mr.Children', 'kana' => 'みすたーちるどれん'],
            ['name' => 'DREAMS COME TRUE', 'kana' => 'どりーむずかむとぅるー'],
        ];

        foreach ($artists as $artist) {
            Artist::create($artist);
        }
    }
}
