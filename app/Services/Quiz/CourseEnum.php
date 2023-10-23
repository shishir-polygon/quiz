<?php

namespace App\Services\MasterMechanics;

use Polygontech\CommonHelpers\Enum\GetWithDisplayValue;
use Polygontech\CommonHelpers\Enum\ReverseEnum;
use Polygontech\CommonHelpers\Enum\EnumToArray;
use Polygontech\CommonHelpers\Enum\StudlyDisplayValue;

enum CourseEnum: string
{
    use ReverseEnum, GetWithDisplayValue, EnumToArray, StudlyDisplayValue;

    case BIKE = 'Bike';
    case BIKE_BN = 'বাইক';
    case CAR = 'Car';
    case CAR_BN = 'গাড়ী';
    case HEAVY_VEHICLE = 'Heavy Vehicle';
    case HEAVY_VEHICLE_BN = 'ভারী যানবাহন';

}
