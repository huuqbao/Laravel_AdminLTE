<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminUserService
{
    public function getDatatableUsers(): array
    {
        $request = request();
        
        $query = User::query();

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $total = $query->count();

        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);

        $posts = $query->orderBy('id', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $posts->map(function ($user, $index) use ($start) {
            return [
                'DT_RowIndex' => $start + $index + 1,
                'name' => $user->name,
                'email' => $user->email,
                'address' => $user->address ?? 'N/A',
                'status' => "<span class='{$user->status_class}'>{$user->status_label}</span>",
                'id' => $user->id,
            ];
        });

        return [
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data->toArray(),
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