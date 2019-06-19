<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Cardsp;
use App\Entity\Classroom;
use App\Entity\Department;
use App\Entity\Establishment;
use App\Entity\Role;
use App\Entity\Status;
use App\Entity\Task;
use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
	public function load(ObjectManager $manager)
	{
        //création du département Alpes-Maritimes
		$department = new Department();
		$department->setName("Alpes-Maritimes")
		->setExist(1);

		//création de 3 établissements pour les Alpes-Maritimes
		for ($i=1; $i < 4; $i++) {
			$slugify_establishment = new Slugify();
			$name = "Lycée$i";
			$slug_establishment = $slugify_establishment->slugify($name);

			$establishment = new Establishment();
			$establishment->setName($name)
			->setAdress("32-42 avenue d'Estienne d'Orves")
			->setPostalcode("06000")
			->setCity("NICE")
			->setLatitude("43°42'00.7\"N")
			->setLongitude("7°14'55.6\"E")
			->setBackgroundurl("https://via.placeholder.com/1920x1080.png")
			->setSlug($slug_establishment)
			->setExist(1)
			->setDepartment($department);

			//création d'un administrateur par établissement
			$slugify_user = new Slugify();
			$lastname = "admin$i";
			$firstname = "admin$i";
			$slug_user = $slugify_user->slugify("$lastname-$firstname");

			$user = new User();
			$user->setLastname($lastname)
			->setFirstname($firstname)
			->setEmail("$slug_user@email.com")
			->setPassword("pass$i")
			->setBirthdate(new \DateTime("2000-01-01"))
			->setSlug($slug_user)
			->setRole("ROLE_ADMIN")
			->setExist(1);

			$manager->persist($user);

			$status = new Status();
			$status->setUser($user);

			$manager->persist($status);

			//création de 3 classes par établissement
			for ($j=1; $j < 4; $j++) { 
				
				$slugify_classroom = new Slugify();
				$degree = "BTS GPME$j";
				$startdate = new \DateTime();
				$startdate = date_format($startdate, "Y");
				$enddate = new \DateTime("+ 2 years");
				$enddate = date_format($enddate, "Y");
				$slug_classeroom = $slugify_classroom->slugify("$degree-$startdate-$enddate");

				$classroom = new Classroom();
				$classroom->setDegree($degree)
				->setStartdate(new \DateTime())
				->setEnddate(new \DateTime("+ 2 years"))
				->setSlug($slug_classeroom)
				->setExist(1)
				->setEstablishment($establishment);

				// création de 30 étudiants par classe
				for ($k=1; $k < 31; $k++) {
					$slugify_user = new Slugify();
					$lastname = "lastname$k";
					$firstname = "firstname$k";
					$slug_user = $slugify_user->slugify("$lastname-$firstname");

					$user = new User();
					$user->setCandidatenb("1000$k")
					->setLastname($lastname)
					->setFirstname($firstname)
					->setEmail("$slug_user$k@email.com")
					->setPassword("pass$k")
					->setBirthdate(new \DateTime("2000-01-01"))
					->setSlug($slug_user)
					->setRole("ROLE_USER")
					->setStatus("student")
					->setExist(1);

					$manager->persist($user);

					// création d'une activité
					for ($act=1; $act < 2; $act++) {
						$activity = new Activity();
						$activity->setNumber("1.$act")
						->setName("nom de la compétence")
						->setCompetency("compétence")
						->setExist(1);

						// création de 3 taches par activité
						for ($tas=1; $tas < 4; $tas++) { 
							$task = new Task();
							$task->setNumber("1.$act.$tas")
							->setName("tache")
							->setExist(1)
							->setActivity($activity);

							$manager->persist($task);
						}

						// création de 2 ficheSP par étudiant
						for ($car=1; $car < 3; $car++) {
							$cardsp = new Cardsp();
							$cardsp->setNumbersp("$car")
							->setUser($user)
							->setActivity($activity)
							->setExist(1);
							
							$manager->persist($cardsp);
						}

						$manager->persist($activity);
					}
					
					$status = new Status();
					$status->setClassroom($classroom)
					->setUser($user);

					$manager->persist($status);

				}

				//création de 5 enseignants par classe
				for ($l=1; $l < 6; $l++) { 
					$slugify_user = new Slugify();
					$lastname = "lastname$l";
					$firstname = "firstname$l";
					$slug_user = $slugify_user->slugify("$lastname-$firstname");

					$user = new User();
					$user->setLastname($lastname)
					->setFirstname($firstname)
					->setEmail("$slug_user$l@email.com")
					->setPassword("pass$l")
					->setBirthdate(new \DateTime("2000-01-01"))
					->setSlug($slug_user)
					->setRole("ROLE_USER")
					->setStatus("teacher")
					->setExist(1);

					$manager->persist($user);

					$status = new Status();
					$status->setClassroom($classroom)
					->setUser($user);

					$manager->persist($status);
				}

				$manager->persist($classroom);

			}

			$manager->persist($establishment);
		}

		$manager->persist($department);

		//--------------------------------------------------------------------------------------------------------------

		//création du département Var
		$department = new Department();
		$department->setName("Var")
		->setExist(1);

        //création de 3 Etablissements par Département
		for ($m=1; $m < 4; $m++) {
			$slugify_establishment = new Slugify();
			$name = "Lycée$m";
			$slug_establishment = $slugify_establishment->slugify($name);

			$establishment = new Establishment();
			$establishment->setName($name)
			->setAdress("2 avenue Paul Arène")
			->setPostalcode("83000")
			->setCity("TOULON")
			->setLatitude("43°42'00.7\"N")
			->setLongitude("7°14'55.6\"E")
			->setBackgroundurl("https://via.placeholder.com/1920x1080.png")
			->setSlug($slug_establishment)
			->setExist(1)
			->setDepartment($department);

			//création d'un administrateur par établissement
			$slugify_user = new Slugify();
			$lastname = "admin$m";
			$firstname = "admin$m";
			$slug_user = $slugify_user->slugify("$lastname-$firstname");

			$user = new User();
			$user->setLastname($lastname)
			->setFirstname($firstname)
			->setEmail("$slug_user@email.com")
			->setPassword("pass$m")
			->setBirthdate(new \DateTime("2000-01-01"))
			->setSlug($slug_user)
			->setRole("ROLE_ADMIN")
			->setExist(1);

			$manager->persist($user);

			$status = new Status();
			$status->setUser($user);

			$manager->persist($status);

			//création de 3 classes par établissement
			for ($n=1; $n < 4; $n++) {
				
				$slugify_classroom = new Slugify();
				$degree = "BTS GPME$n";
				$startdate = new \DateTime();
				$startdate = date_format($startdate, "Y");
				$enddate = new \DateTime("+ 2 years");
				$enddate = date_format($enddate, "Y");
				$slug_classeroom = $slugify_classroom->slugify("$degree-$startdate-$enddate");

				$classroom = new Classroom();
				$classroom->setDegree($degree)
				->setStartdate(new \DateTime())
				->setEnddate(new \DateTime("+ 2 years"))
				->setSlug($slug_classeroom)
				->setExist(1)
				->setEstablishment($establishment);

				// création de 30 étudiants par classe
				for ($p=1; $p < 31; $p++) {
					$slugify_user = new Slugify();
					$lastname = "lastname$p";
					$firstname = "firstname$p";
					$slug_user = $slugify_user->slugify("$lastname-$firstname");

					$user = new User();
					$user->setCandidatenb("1000$p")
					->setLastname($lastname)
					->setFirstname($firstname)
					->setEmail("$slug_user$p@email.com")
					->setPassword("pass$p")
					->setBirthdate(new \DateTime("2000-01-01"))
					->setSlug($slug_user)
					->setRole("ROLE_USER")
					->setStatus("student")
					->setExist(1);

					// création d'une activité
					for ($act=1; $act < 2; $act++) {
						$activity = new Activity();
						$activity->setNumber("1.$act")
						->setName("nom de la compétence")
						->setCompetency("compétence")
						->setExist(1);

						// création de 3 taches par activité
						for ($tas=1; $tas < 4; $tas++) { 
							$task = new Task();
							$task->setNumber("1.$act.$tas")
							->setName("tache")
							->setExist(1)
							->setActivity($activity);

							$manager->persist($task);
						}

						// création de 2 ficheSP par étudiant
						for ($car=1; $car < 3; $car++) {
							$cardsp = new Cardsp();
							$cardsp->setNumbersp("$car")
							->setUser($user)
							->setActivity($activity)
							->setExist(1);

							$manager->persist($cardsp);
						}

						$manager->persist($activity);
					}

					$manager->persist($user);

					$status = new Status();
					$status->setClassroom($classroom)
					->setUser($user);

					$manager->persist($status);

				}

				//création de 5 enseignants par classe
				for ($q=1; $q < 6; $q++) { 
					$slugify_user = new Slugify();
					$lastname = "lastname$q";
					$firstname = "firstname$q";
					$slug_user = $slugify_user->slugify("$lastname-$firstname");

					$user = new User();
					$user->setLastname($lastname)
					->setFirstname($firstname)
					->setEmail("$slug_user$q@email.com")
					->setPassword("pass$q")
					->setBirthdate(new \DateTime("2000-01-01"))
					->setSlug($slug_user)
					->setRole("ROLE_USER")
					->setStatus("teacher")
					->setExist(1);

					$manager->persist($user);

					$status = new Status();
					$status->setClassroom($classroom)
					->setUser($user);

					$manager->persist($status);
				}

				$manager->persist($classroom);

			}

			$manager->persist($establishment);
		}

		$manager->persist($department);

//-----------------------------------------------------------------------------------

		//création de 2 super administrateur
		for ($z=1; $z < 3; $z++) { 
			$slugify_user = new Slugify();
			$lastname = "sadmin$z";
			$firstname = "sadmin$z";
			$slug_user = $slugify_user->slugify("$lastname-$firstname");

			$user = new User();
			$user->setLastname($lastname)
			->setFirstname($firstname)
			->setEmail("$slug_user@email.com")
			->setPassword("pass$z")
			->setBirthdate(new \DateTime("2000-01-01"))
			->setSlug($slug_user)
			->setRole("ROLE_GADMIN")
			->setExist(1);

			$manager->persist($user);

			$status = new Status();
			$status->setUser($user);

			$manager->persist($status);
		}

		$manager->flush();
	}
}