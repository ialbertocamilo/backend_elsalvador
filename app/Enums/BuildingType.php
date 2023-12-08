<?php

namespace App\Enums;

enum BuildingType: int
{
    case single=0;
    case duplex=1;
    case department=2;
    case public=3;
    case private=4;
}
