<?php


namespace App\Tests\Faker;


use App\Faker\CarProvider;
use PHPUnit\Framework\TestCase;

class CarProviderTest extends TestCase
{
    /**
     * @dataProvider carburantProvider
     */
    public function testCarburantContains($carburant)
    {
        $this->assertContains($carburant, CarProvider::CARBURANT);
    }

    /**
     * @dataProvider colorProvider
     */
    public function testColorContains($color)
    {
        $this->assertContains($color, CarProvider::COLOR);
    }

    public function testCarburantNotContains()
    {
        $this->assertNotContains('ethanol', CarProvider::CARBURANT);
    }

    public function carburantProvider()
    {
        return [
            ['electrique'],
            ['diesel'],
            ['essence'],
        ];
    }


    public function colorProvider()
    {
        return [
            ['rouge'],
            ['noir'],
            ['vert'],
        ];
    }
}
