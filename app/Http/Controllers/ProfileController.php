<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * update the user's profile.
     */
    public function updateProfileInfo(Request $request)
    {
        $user = $request->user();

        $deleteIcon = $request->boolean('delete_icon');

        $data = $request->validateWithBag('profile', [
            'icon' => 'nullable|image|mimes:jpg,jpeg,png.webp|max:2048',
            'profile' => 'nullable|string|max:1000'
        ]);

        if($deleteIcon) {
            if($user->icon_path) {
                Storage::disk('public')->delete($user->icon_path);
            }
            $user->icon_path = null;
        } elseif($request->hasFile('icon')) {
            // 古いファイルを削除
            if ($user->icon_path) {
                Storage::disk('public')->delete($user->icon_path);
            }
            // 新しいファイル名を生成
            $ext = strtolower($request->file('icon')->getClientOriginalExtension());
            $filename = $user->id . '_' . now()->format('YmdHis') . '.' . $ext;
            // 新しいファイルを保存
            $path = $request->file('icon')->storeAs('profile_icons', $filename, 'public');
            // DBにパスを保存
            $user->icon_path = $path;
        }

        // プロフィール文（空文字→nullでクリア）
        if($request->has('profile')){
            $user->profile = ($data['profile'] === '') ? null : $data['profile'];
        }

        $user->save();

        return back()
            ->with('status', $deleteIcon ? 'アイコンが削除されました。' : 'プロフィールが更新されました。');
    }
}
