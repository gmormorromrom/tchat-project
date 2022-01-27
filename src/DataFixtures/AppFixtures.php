<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('user1');
        $user->setRoles(["ROLE_USER"]);
        $password = $this->encoder->encodePassword($user, 'testtest');
        $user->setPassword($password);
        $manager->persist($user);

        $user1 = new User();
        $user1->setUsername('user2');
        $user1->setRoles(["ROLE_USER"]);
        $password1 = $this->encoder->encodePassword($user1, 'testtest');
        $user1->setPassword($password1);
        $manager->persist($user1);

        $manager->flush();
    }
}
