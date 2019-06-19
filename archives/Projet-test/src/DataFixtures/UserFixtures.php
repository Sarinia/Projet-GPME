<?php

namespace App\DataFixtures;

use App\Entity\Establishment;
use App\Entity\Role;
use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder=$encoder;
    }

    public function load(ObjectManager $manager)
    {
// création d'un super-administrateu
        $sadminRole = new Role();
        $sadminRole->setTitle("ROLE_SADMIN");

        $manager->persist($sadminRole);

        $slugify = new Slugify();
        $lastName = "sadmin";
        $firstName = "sadmin";
        $sadminUser = new User();
        $sadminUser->setLastName($lastName)
        ->setFirstName($firstName)
        ->setEmail("sadmin@email.fr")
        ->setHash($this->encoder->encodePassword($sadminUser, "sadminpass"))
        ->setBirthDate(new \DateTime("2010/02/16"))
        ->setSlug($slugify->slugify("$lastName-$firstName"))
        ->setExist(1)
        ->addUserRole($sadminRole);

        $manager->persist($sadminUser);

//--------------------------------------------------------------------------------------

// création d'un administrateur
        $adminRole = new Role();
        $adminRole->setTitle("ROLE_ADMIN");

        $manager->persist($adminRole);

        $slugify = new Slugify();
        $lastName = "admin";
        $firstName = "admin";
        $adminUser = new User();
        $adminUser->setLastName($lastName)
        ->setFirstName($firstName)
        ->setEmail("admin@email.fr")
        ->setHash($this->encoder->encodePassword($adminUser, "adminpass"))
        ->setBirthDate(new \DateTime("2010/02/16"))
        ->setSlug($slugify->slugify("$lastName-$firstName"))
        ->setExist(1)
        ->addUserRole($adminRole);

        $manager->persist($adminUser);

//--------------------------------------------------------------------------------------

// création d'un enseignant
        $teacherRole = new Role();
        $teacherRole->setTitle("ROLE_TEACHER");

        $manager->persist($teacherRole);

        $slugify = new Slugify();
        $lastName = "teacher";
        $firstName = "teacher";
        $teacherUser = new User();
        $teacherUser->setLastName($lastName)
        ->setFirstName($firstName)
        ->setEmail("teacher@email.fr")
        ->setHash($this->encoder->encodePassword($teacherUser, "teacherpass"))
        ->setBirthDate(new \DateTime("2010/02/16"))
        ->setSlug($slugify->slugify("$lastName-$firstName"))
        ->setExist(1)
        ->addUserRole($teacherRole);

        $manager->persist($teacherUser);

//--------------------------------------------------------------------------------------

// création de 5 utilisateurs
        $userRole = new Role();
        $userRole->setTitle("ROLE_USER");

        $manager->persist($userRole);

        for ($i=1; $i < 6; $i++) { 
            $slugify = new Slugify();
            $lastName = "user";
            $firstName = "user";
            $user = new User();
            $user->setLastName("$lastName$i")
            ->setFirstName("$firstName$i")
            ->setEmail("user$i@email.fr")
            ->setHash($this->encoder->encodePassword($user, "userpass$i"))
            ->setBirthDate(new \DateTime("2010/02/16"))
            ->setSlug($slugify->slugify("$lastName-$firstName"))
            ->setExist(1)
            ->addUserRole($userRole);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
