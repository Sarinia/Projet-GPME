<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Classroom;
use App\Entity\Department;
use App\Entity\Establishment;
use App\Entity\Sadmin;
use App\Entity\Student;
use App\Entity\Teacher;
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
		// Super-Administrateur
		$user = new User();

		$slugify = new Slugify();
		$lastName = "sadmin";
		$firstName = "sadmin";
		$slug = $slugify->Slugify($lastName."-".$firstName);

		$encoded = $this->encoder->encodePassword($user, "pass");

		$user->setLastName($lastName)
		->setFirstName($firstName)
		->setEmail("sadmin@email.fr")
		->setHash($encoded)
		->setSlug($slug)
		->setTitle("ROLE_SADMIN")
		->setExist(1);
		$manager->persist($user);

		$sadmin = new Sadmin();
		$sadmin->setUser($user);
		$manager->persist($sadmin);

		// Département
		for ($i=1; $i < 4; $i++) { 
			$department = new Department();
			$department->setName("departement$i")
			->setExist(1);

			// Etablissement
			for ($j=1; $j < 3; $j++) { 
				$slugify = new Slugify();
				$name = "établissement$j";
				$slug = $slugify->slugify($name);

				$establishment = new Establishment();
				$establishment->setDepartment($department)
				->setName($name)
				->setAdress("adresse$j")
				->setPostalCode("06000")
				->setCity("ville$j")
				->setBackgroundUrl("https://via.placeholder.com/1920x1080.png")
				->setSlug($slug)
				->setExist(1);

				// Administrateur
				$user = new User();

				$slugify = new Slugify();
				$lastName = "admin";
				$firstName = "admin";
				$slug = $slugify->Slugify($lastName."-".$firstName);

				$encoded = $this->encoder->encodePassword($user, "pass");

				$user->setLastName($lastName)
				->setFirstName($firstName)
				->setEmail("admin@email.fr")
				->setHash($encoded)
				->setSlug($slug)
				->setTitle("ROLE_ADMIN")
				->setExist(1);
				$manager->persist($user);

				$admin = new Admin();
				$admin->setUser($user)
				->setEstablishment($establishment);
				$manager->persist($admin);

				// Teacher
				for ($k=1; $k < 6; $k++) { 
					$user = new User();

					$slugify = new Slugify();
					$lastName = "teacher$k";
					$firstName = "teacher$k";
					$slug = $slugify->Slugify($lastName."-".$firstName);

					$encoded = $this->encoder->encodePassword($user, "pass");

					$user->setLastName($lastName)
					->setFirstName($firstName)
					->setEmail("teacher@email.fr")
					->setHash($encoded)
					->setSlug($slug)
					->setTitle("ROLE_TEACHER")
					->setExist(1);
					$manager->persist($user);

					$teacher = new Teacher();
					$teacher->setUser($user)
					->setEstablishment($establishment);
					$manager->persist($teacher);
				}

				// Classe
				for ($k=1; $k < 6; $k++) {
					$slugify = new Slugify();
					$degree = "BTS GPME $k";
					$startDate = "2010";
					$endDate = "2012";
					$slug = $slugify->slugify($degree."-".$startDate."-".$endDate);

					$classroom = new Classroom();
					$classroom->setDegree($degree)
					->setStartDate($startDate)
					->setEndDate($endDate)
					->setSlug($slug)
					->setEstablishment($establishment)
					->setExist(1);

					// Student
					for ($l=1; $l < 6; $l++) { 
						$user = new User();

						$slugify = new Slugify();
						$lastName = "student$l";
						$firstName = "student$l";
						$slug = $slugify->Slugify($lastName."-".$firstName);

						$encoded = $this->encoder->encodePassword($user, "pass");

						$user->setLastName($lastName)
						->setFirstName($firstName)
						->setEmail("student@email.fr")
						->setHash($encoded)
						->setSlug($slug)
						->setTitle("ROLE_USER")
						->setExist(1);
						$manager->persist($user);

						$student = new Student();
						$student->setUser($user)
						->setCandidateNb("1000$l")
						->setBirthDate(new \DateTime("2010/02/16"));
						$manager->persist($student);
					}

					$manager->persist($classroom);
				}

				$manager->persist($establishment);
			}

			$manager->persist($department);
		}

		$manager->flush();
	}
}
