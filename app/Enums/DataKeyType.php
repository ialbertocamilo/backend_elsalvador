<?php

namespace App\Enums;

enum DataKeyType: string
{
    case PackageConfiguration='package-configuration';
    case Transmittance='transmittance';
    case Window='window';
    case Package='package';
    case Proportion='proportion';
    case Shading='shading';
    case Roofs='roofs';
    case OriginList='origin-list';
    case DepartmentList='department-list';
}
