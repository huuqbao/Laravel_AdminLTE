<?php

namespace App\Enums;

enum PostStatus: int
{
    case NEW = 0;
    case UPDATED = 1;
    case PUBLISHED = 2;

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'Bài viết mới',
            self::UPDATED => 'Được cập nhật',
            self::PUBLISHED => 'Đã xuất bản',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::NEW => 'badge bg-secondary',
            self::UPDATED => 'badge bg-warning text-dark',
            self::PUBLISHED => 'badge bg-success',
        };
    }
}
