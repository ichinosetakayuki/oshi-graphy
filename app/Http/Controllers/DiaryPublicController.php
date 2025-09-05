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
}
