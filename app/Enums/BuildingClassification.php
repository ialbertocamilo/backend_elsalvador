<?php

namespace App\Enums;

enum BuildingClassification:int{
    case households=0;
    case offices=1;
    case tertiary=2;
}
