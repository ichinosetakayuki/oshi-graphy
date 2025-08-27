<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\Artist;
use Illuminate\Http\Request;

class DiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $year = $request->integer('year');
        $artist = $request->integer('artist');

        $diaries = $user->diaries()
            ->with('artist')
            // when:$yearがあれば、関数を実行。if($year)と同じ
            ->when($year, fn($q)=>$q->where('happened_on', $year))
            ->when($artist, fn($q) => $q->where('artist_id', $artist))
            ->latest('happened_on')
            ->get();

        $years = range(now()->year, now()->year - 5);
        $artists = Artist::orderBy('name')->get(['id', 'name']);

        return view('diaries.index', compact('diaries', 'years', 'artists', 'year', 'artist'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Diary $diary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Diary $diary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Diary $diary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diary $diary)
    {
        //
    }
}
