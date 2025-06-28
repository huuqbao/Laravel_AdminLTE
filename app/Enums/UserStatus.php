<?php

namespace App\Enums;
//full hoa
enum UserStatus: int
{
    case PENDING = 0;
    case APPROVED = 1; 
    case REJECTED = 2; 
    case LOCKED = 3;
}
