<?php

namespace App\DataFixtures;

use App\Entity\Plan;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encodePassword;

    public function __construct(UserPasswordEncoderInterface $encodePassword)
    {
        $this->encodePassword = $encodePassword;
    }

    public function load(ObjectManager $manager)
    {
        foreach($this->getPlans() as [$name, $description, $eventCapacity, $userCapacity, $price]):
            $plan = new Plan();
            $plan->setName($name);
            $plan->setDescription($description);
            $plan->setEventCapacity($eventCapacity);
            $plan->setUserCapacity($userCapacity);
            $plan->setPrice((float)$price);
            $manager->persist($plan);
        endforeach;
        $manager->flush();

        foreach($this->getUsers() as [$planId, $firstname, $lastname, $email, $plainPassword, $roles]):
            $plan = $manager->getRepository(Plan::class)->find($planId);
            $user = new User();
            $user->setPlan($plan);
            $user->setEmail($email);
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setIsActive(true);
            $user->setPassword($this->encodePassword->encodePassword($user, $plainPassword));
            $user->setRoles($roles);
            $manager->persist($user);
        endforeach;
        $manager->flush();
    }

    public function getPlans()
    {
        return [
            ['Free plan', 'Free plan for all beginners', 3, 30, 0],
            ['Basic plan', 'Basic plan for begin business', 100, 100, 6.99],
            ['Business plan', 'Best Plan for Business man', 10, 1000, 10.99]
        ] ;
    }

    public function getUsers()
    {
        return [
            [3, 'John', 'Doe (Admin)', 'admin@mail.fr', 'devpassword', ['ROLE_ADMIN']],
            [1, 'John', 'Doe (Customer)', 'customer@mail.fr', 'devpassword', ['ROLE_USER']],
        ] ;
    }

}
