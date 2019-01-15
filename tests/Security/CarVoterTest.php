<?php


namespace App\Tests\Security;


use App\Entity\Car;
use App\Entity\User;
use App\Security\Voter\CarVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class CarVoterTest extends TestCase
{
    /**
     * @dataProvider voterProvider
     *
     */
    public function testCarVoter($user, $expected)
    {
        $voter = new CarVoter();
        $car = new Car();

        $token = new AnonymousToken('secret', 'anonymous');

        if($user) {
            $token = new UsernamePasswordToken($user, 'credentials', 'memory');
            $car->setUser($user);
        }

        $this->assertSame($expected, $voter->vote($token, $car, ['EDIT']));
    }

    public function voterProvider()
    {
        $userOne = $this->createMock(User::class);
        $userOne->method('getId')->willReturn(1);

        return [
            [$userOne, 1],
            [null, -1]
        ];
    }
}
