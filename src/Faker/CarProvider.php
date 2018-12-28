<?php


namespace App\Faker;

use Faker\Provider\Base;

class CarProvider extends Base
{
    const CARBURANT = [
        'electrique',
        'diesel',
        'essence',
    ];

    const COLOR = [
        'noir',
        'blanc',
        'vert',
        'rouge',
        'bleu',
    ];

    public function carCarburant()
    {
        return self::randomElement(self::CARBURANT);
    }

    public function carColor()
    {
        return self::randomElement(self::COLOR);
    }

    public function carPrice()
    {
        return rand(100, 6000);
    }
}
