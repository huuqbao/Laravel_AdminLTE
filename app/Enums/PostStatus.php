<?php

namespace App\Enums;

enum PostStatus: int
{
    case DRAFT = 0;
    case PENDING = 1;
    case PUBLISHED = 2;

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Nháp',
            self::PENDING => 'Chờ duyệt',
            self::PUBLISHED => 'Đã xuất bản',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::DRAFT => 'badge bg-secondary',
            self::PENDING => 'badge bg-warning text-dark',
            self::PUBLISHED => 'badge bg-success',
        };
    }
}
