<?php

namespace App\DataFixtures;

use App\Entity\Department;
use App\Entity\Establishment;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class EstabFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $department = new Department();
        $department->setName("Alpes-Maritimes")
        ->setExist(1);

        for ($i=1; $i < 6; $i++) {
        	$slugify = new Slugify();
        	$name = "lycée$i(A-M)";
        	$slug = $slugify->slugify($name);

        	$establishment = new Establishment();
        	$establishment->setName($name)
        	->setAdress("32-42 avenue d'Estienne d'Orves")
        	->setPostalcode("06000")
        	->setCity("NICE")
        	->setBackgroundurl("https://via.placeholder.com/1920x1080.png")
        	->setSlug($slug)
        	->setExist(1)
        	->setDepartment($department);

        	$manager->persist($establishment);
        }

        $manager->persist($department);

        $manager->flush();
    }
}
