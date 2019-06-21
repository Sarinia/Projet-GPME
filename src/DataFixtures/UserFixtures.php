<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Card;
use App\Entity\Classroom;
use App\Entity\Department;
use App\Entity\Establishment;
use App\Entity\Passport;
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
		$sadmin->setUser($user)
		->setCreatedAt(new \DateTime());
		$manager->persist($sadmin);

		// Département
		for ($i=1; $i < 3; $i++) {
			$department = new Department();
			$department->setName("departement$i")
			->setCreatedAt(new \DateTime())
			->setExist(1);

			// Etablissement
			for ($e=1; $e < 3; $e++) {
				$slugify = new Slugify();
				$name = "établissement$e";
				$slug = $slugify->slugify($name);

				$establishment = new Establishment();
				$establishment->setDepartment($department)
				->setName($name)
				->setAdress("adresse$e")
				->setPostalCode("06000")
				->setCity("ville$e")
				->setBackgroundUrl("https://via.placeholder.com/1920x1080.png")
				->setSlug($slug)
				->setCreatedAt(new \DateTime())
				->setExist(1);

				// Administrateur
				$user = new User();

				$slugify = new Slugify();
				$lastName = "admin$e";
				$firstName = "admin$e";
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
				->setEstablishment($establishment)
				->setCreatedAt(new \DateTime());
				$manager->persist($admin);

				// Classe
				for ($c=1; $c < 3; $c++) {
					$slugify = new Slugify();
					$degree = "BTS GPME $c";
					$startDate = "2010";
					$endDate = "2012";
					$slug = $slugify->slugify($degree."-".$startDate."-".$endDate);

					$classroom = new Classroom();
					$classroom->setDegree($degree)
					->setStartDate($startDate)
					->setEndDate($endDate)
					->setSlug($slug)
					->setEstablishment($establishment)
					->setCreatedAt(new \DateTime())
					->setExist(1);

					// Student
					for ($s=1; $s < 6; $s++) {
						$user = new User();

						$slugify = new Slugify();
						$lastName = "student$s";
						$firstName = "student$s";
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
						->setCandidateNb("1000$s")
						->setBirthDate(new \DateTime("2010/02/16"))
						->setEstablishment($establishment)
						->setClassroom($classroom)
						->setCreatedAt(new \DateTime());

						// passeport
						$passport = new Passport();
						$passport->setStudent($student)
						->setCreatedAt(new \DateTime());

		// 				for ($t=1; $t < 3; $t++) {
		// 					$card = new Card();
		// 					$card->setProb1(0)
		// 					->setProb2(0)
		// 					->setProb3(1)
		// 					->setNumbersp(1)
		// 					->setMod1(0)
		// 					->setMod2(0)
		// 					->setMod3(1)
		// 					->setCond1(0)
		// 					->setCond2(0)
		// 					->setCond3(1)
		// 					->setEntitledsp("")
		// 					->setInfossp("")
		// 					->setFramesp("")
		// 					->setProblemmanagsp("")
		// 					->setProblemcomosp("")
		// 					->setProblemcomwsp("")
		// 					->setActorssp("")
		// 					->setTargetsp("")
		// 					->setConditionssp("")
		// 					->setResourcessp("")
		// 					->setAnswerssp("")
		// 					->setProductionssp("")
		// 					->setWrittensp("")
		// 					->setOralsp("")
		// 					->setContributionsp("")
		// 					->setAnalysissp("")
		// 					->setStudent($student)
		// 					->setExist(1);

		// 			        $manager->persist($card);
		// 				}

						$manager->persist($passport);
						$manager->persist($student);
					}

					// Teacher
					for ($t=1; $t < 4; $t++) {
						$user = new User();

						$slugify = new Slugify();
						$lastName = "teacher$t";
						$firstName = "teacher$t";
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
						->setEstablishment($establishment)
						->addClassroom($classroom)
						->setCreatedAt(new \DateTime());
						$manager->persist($teacher);
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
