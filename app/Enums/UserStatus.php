<?php

namespace App\Enums;

enum UserStatus: int
{
    case Pending = 0;
    case Approved = 1;
    case Rejected = 2;
    case Locked = 3;
}
