<?php

namespace App\Services;

class LocationService
{
    public string $location;

    public function __construct($location)
    {
        $this->location = $location;
    }

    public function getCoordinates(): string
    {
        /**
         *  Could be some sort of request to some API but decided to just hard code the coordinates since i just want to highlight DI container & services.
         * 
         */

        $coordinates = [
            'bandar seri begawan' => '4.9403,114.9481',
            'phnom penh' => '11.562108,104.888535',
            'jakarta' => '-6.200000,106.816666',
            'vientiane' => '17.974855,102.630867',
            'kuala lumpur' => '3.139003,101.686855',
            'naypyidaw' => '19.7633057,96.0785104',
            'manila' => '14.5995124,120.9842195',
            'singapore' => '1.290270,103.851959',
            'bangkok' => '13.756331,100.501765',
            'hanoi' => '21.028511,105.804817'
        ];

        return $coordinates[strtolower($this->location)] ?? '';
    }
}
