<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();

        return view('user_profile.edit', compact('user'));
    }
}
