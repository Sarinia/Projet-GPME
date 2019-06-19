<?php

namespace App\DataFixtures;

use App\Entity\Academy;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AcademyFixtures extends Fixture
{
	public function load(ObjectManager $manager)
	{
		for ($i=1; $i < 6; $i++) { 
			$academy = new Academy();
			$academy->setName("academie$i")
			->setExist(1);

			$manager->persist($academy);
		}

		$manager->flush();
	}
}
