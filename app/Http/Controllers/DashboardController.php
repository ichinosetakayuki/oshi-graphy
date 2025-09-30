<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()
                        ->latest()
                        ->take(10)
                        ->get();

        return view('dashboard', compact('notifications'));
    }
}
