<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function edit()
    {
        $user = Auth::user();
        return view('profile.profile-edit', compact('user')); 
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            $this->userService->updateProfile($request->validated());

            return to_route('profile.edit')->with('success', 'Cập nhật hồ sơ thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật hồ sơ: ' . $e->getMessage());

            return to_route('profile.edit')
                ->withErrors(['error' => 'Có lỗi xảy ra khi cập nhật hồ sơ. Vui lòng thử lại sau.'])
                ->withInput();
        }
    }
}
