@extends('layouts.app')
@section('title','Oshi-Graphy | マイページ（日記一覧）')

@section('content')
<h1 class="text-2xl font-semibold mb-4">{{ auth()->user()->name }}さんの日記</h1>

<form method="GET" class="flex flex-wrap items-center gap-3 mb-5">
    <label>年</label>
    <select name="year" class="border rounded px-3 py-1">
        <option value="">すべて</option>
        @foreach($years as $y)
        <option value="{{ $y }}" @selected($year==$y)>{{ $y }}年</option>
        @endforeach
    </select>

    <label class="ml-4">アーティスト</label>
    <select name="artist" class="border rounded px-3 py-1">
        <option value="">すべて</option>
        @foreach($artists as $a)
        <option value="{{ $a->id }}" @selected($artist==$a->id)>{{ $a->name }}</option>
        @endforeach
    </select>

    <button class="rounded px-4 py-1 bg-brand">絞り込み</button>
    @if($year || $artist)
    <a href="{{ route('diaries.index') }}" class="text-sm underline">条件クリア</a>
    @endif
</form>



@endsection