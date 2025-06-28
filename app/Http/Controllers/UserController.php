<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateProfileRequest;

class UserController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.profile-edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        $user->update($validated);

        return back()->with('success', 'Cập nhật hồ sơ thành công');
    }
}
