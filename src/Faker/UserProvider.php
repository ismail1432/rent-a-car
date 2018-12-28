<?php


namespace App\Faker;

use App\Entity\User;
use Faker\Generator;
use Faker\Provider\Base;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserProvider extends Base
{
    private $passwordEncoder;

    public function __construct(Generator $generator, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($generator);

        $this->passwordEncoder = $passwordEncoder;
    }

    public function encodePassword($password)
    {
        return $this->passwordEncoder->encodePassword((new User()), $password);
    }
}
