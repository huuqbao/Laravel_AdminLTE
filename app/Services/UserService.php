<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class UserService
{
    public function updateProfile(array $data): void
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();
            $user->update($data);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
