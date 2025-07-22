<?php

namespace App\Services\Admin;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\Admin\UserResource;

class UserService
{
    public function getDatatableUsers(array $filters): array
{
    $query = User::query();

    if (!empty($filters['search'])) {
        $search = $filters['search'];
        $query->where(function ($q) use ($search) {
            $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        });
    }

    $total = $query->count();

    $start = $filters['start'] ?? 0;
    $length = $filters['length'] ?? 10;

    $users = $query->orderBy('id', 'desc')
        ->skip($start)
        ->take($length)
        ->get()
        ->each(function ($user, $index) use ($start) {
            $user->row_index = $start + $index + 1;
        });

    return [
        'draw' => $filters['draw'] ?? 1,
        'recordsTotal' => $total,
        'recordsFiltered' => $total,
        'totalUsers' => User::count(),
        'data' => UserResource::collection($users)->toArray(request()),
    ];
}


    public function updateUser(User $user, array $validatedData): User
    {
        try {
            // Logic chuẩn bị dữ liệu được chuyển từ Controller vào đây
            $dataToUpdate = $validatedData;
            
            // Chuyển đổi status từ value (0,1,2,3) về đối tượng Enum
            if (isset($dataToUpdate['status'])) {
                $dataToUpdate['status'] = UserStatus::from((int)$dataToUpdate['status']);
            }
            
            // Thực hiện cập nhật
            $user->update($dataToUpdate);

            return $user;

        } catch (\Throwable $e) {
            Log::error('User update failed: ' . $e->getMessage());
            // Ném lại exception để Controller có thể bắt và xử lý
            throw $e;
        }
    }
}