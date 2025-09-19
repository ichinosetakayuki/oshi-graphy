<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\User;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DiaryPublicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $year = $request->integer('year');
        $month = $request->integer('month');
        $artistId = $request->integer('artist_id');

        $diaries =  Diary::query()
            ->where('is_public', true)
            ->when($year, fn($q) => $q->whereYear('happened_on', $year))
            ->when($month, fn($q) => $q->whereMonth('happened_on', $month)) 
            ->when($artistId, fn($q) => $q->where('artist_id', $artistId))
            ->with(['artist', 'coverImage', 'user'])
            ->withCount('comments')
            ->orderBy('happened_on', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        $minDate = Diary::where('is_public', true)->min('happened_on');
        $minYear = $minDate ? Carbon::parse($minDate)->year : 2021;
        $years = range(now()->year, $minYear);
        $months = range(1, 12);

        $artistName = $artistId ? Artist::find($artistId)->name : null;

        return view('public_diaries.index', compact('diaries', 'years', 'months','year', 'month', 'artistId', 'artistName'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Diary $diary)
    {
        $diary->load(['comments', 'user']);
        return view('public_diaries.show', compact('diary'));
    }

    /**
     * Display a listing of the public diaries for the specified user.
     */
    public function user(User $user, Request $request)
    {
        $year = $request->integer('year');
        $artist = $request->integer('artist');

        $diaries = Diary::query()
            ->where('is_public', true)
            ->where('user_id', $user->id)
            ->when($year, fn($q) => $q->whereYear('happened_on', $year))
            ->when($artist, fn($q) => $q->where('artist_id', $artist))
            ->with(['artist', 'coverImage', 'user'])
            ->withCount('comments')
            ->orderBy('happened_on', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(6)
            ->withQueryString();

        $minDate = Diary::min('happened_on');
        // 日付を扱う時はCarbonが便利
        $minYear = $minDate ? Carbon::parse($minDate)->year : 2021;
        $years = range(now()->year, $minYear);

        $artists = Artist::whereIn('id', function ($q)  use ($user) {
            $q->select('artist_id')
                ->from('diaries') // diariesからartist_idの一覧を取り出す
                ->where('user_id', $user->id) // そのユーザーが書いた日記に限定
                ->whereNotNull('artist_id');
        })->orderBy('name')
            ->get(['id', 'name']);

        return view('public_diaries.user', compact('diaries', 'years', 'year', 'artists', 'artist', 'user'));

    }
}
