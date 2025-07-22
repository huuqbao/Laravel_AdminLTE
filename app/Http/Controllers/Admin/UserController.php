<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;
use App\Enums\UserStatus;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $filters = [
                'search' => $request->input('search.value'),
                'start' => (int) $request->input('start', 0),
                'length' => (int) $request->input('length', 10),
                'draw' => (int) $request->input('draw'),
            ];

            return response()->json($this->userService->getDatatableUsers($filters));
        }

        return view('admin.users.index');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            // Lấy dữ liệu đã được validate từ Form Request
            $validatedData = $request->validated();
            
            // Gọi service để xử lý toàn bộ nghiệp vụ
            $this->userService->updateUser($user, $validatedData);

            // Trả về response thành công
            return to_route('admin.users.index')->with('success', 'Cập nhật tài khoản thành công!');

        } catch (\Throwable $th) {
            // Bắt lỗi nếu service ném ra exception
            return back()->with('error', 'Cập nhật thất bại. Vui lòng thử lại.');
        }
    }
}
