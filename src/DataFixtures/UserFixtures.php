<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Admin;
use App\Entity\Card;
use App\Entity\Classroom;
use App\Entity\Department;
use App\Entity\Establishment;
use App\Entity\Modality;
use App\Entity\Passport;
use App\Entity\Problem;
use App\Entity\Sadmin;
use App\Entity\Student;
use App\Entity\Task;
use App\Entity\Teacher;
use App\Entity\Term;
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

		$activity1 = new Activity();
		$activity1->setNumber("Activité 1.1")
		->setName("Recherche de clientèle et contact")
		->setSkill("Rechercher des clients par prospection ou pour répondre à un appel d'offres");
		$manager->persist($activity1);

		$task1_1_1 = new Task();
		$task1_1_1->setNumber("Tache 1.1.1")
		->setName("Organisation de la prospection et prospection clientèle")
		->setActivity($activity1);
		$manager->persist($task1_1_1);

		$task1_1_2 = new Task();
		$task1_1_2->setNumber("Tache 1.1.2")
		->setName("Détection, analyse et suivi des appels d'offres")
		->setActivity($activity1);
		$manager->persist($task1_1_2);

		$task1_1_3 = new Task();
		$task1_1_3->setNumber("Tache 1.1.3")
		->setName("Communication avec des acteurs internes, avec les prospects, les clients et les pouvoirs adjudicateurs")
		->setActivity($activity1);
		$manager->persist($task1_1_3);

		$activity2 = new Activity();
		$activity2->setNumber("Activité 1.2")
		->setName("Administration des ventes de la PME")
		->setSkill("Traiter la demande du client (de la demande de devis jusqu'à la relance des éventuels impayés)");
		$manager->persist($activity2);

		$task1_2_1 = new Task();
		$task1_2_1->setNumber("Tache 1.2.1")
		->setName("Préparation de propositions commerciales")
		->setActivity($activity2);
		$manager->persist($task1_2_1);

		$task1_2_2 = new Task();
		$task1_2_2->setNumber("Tache 1.2.2")
		->setName("Préparation des contrats commerciaux(commandes, contrats de maintenance, garanties complémentaires, contrats de sous-traitance, etc.)")
		->setActivity($activity2);
		$manager->persist($task1_2_2);

		$task1_2_3 = new Task();
		$task1_2_3->setNumber("Tache 1.2.3")
		->setName("Suivi des ventes et des livraisons")
		->setActivity($activity2);
		$manager->persist($task1_2_3);

		$task1_2_4 = new Task();
		$task1_2_4->setNumber("Tache 1.2.4")
		->setName("Facturation, suivi des règlements et des relances \"clients\"")
		->setActivity($activity2);
		$manager->persist($task1_2_4);

		$task1_2_5 = new Task();
		$task1_2_5->setNumber("Tache 1.2.5")
		->setName("Evaluation du risque client")
		->setActivity($activity2);
		$manager->persist($task1_2_5);

		$task1_2_6 = new Task();
		$task1_2_6->setNumber("Tache 1.2.6")
		->setName("Mise à jour du système d'information \"clients\"")
		->setActivity($activity2);
		$manager->persist($task1_2_6);

		$task1_2_7 = new Task();
		$task1_2_7->setNumber("Tache 1.2.7")
		->setName("Communication avec des acteurs internes, les fournisseurs et les clients")
		->setActivity($activity2);
		$manager->persist($task1_2_7);

		$activity3 = new Activity();
		$activity3->setNumber("Activité 1.3")
		->setName("Maintien et développement de la relation avec les clients de la PME")
		->setSkill("Informer, conseiller, orienter et traiter les réclamations");
		$manager->persist($activity3);

		$task1_3_1 = new Task();
		$task1_3_1->setNumber("Tache 1.3.1")
		->setName("Accueil, information et conseils")
		->setActivity($activity3);
		$manager->persist($task1_3_1);

		$task1_3_2 = new Task();
		$task1_3_2->setNumber("Tache 1.3.2")
		->setName("Traitement et suivi des reclamations")
		->setActivity($activity3);
		$manager->persist($task1_3_2);

		$task1_3_3 = new Task();
		$task1_3_3->setNumber("Tache 1.3.3")
		->setName("Communication pour développer la relation client")
		->setActivity($activity3);
		$manager->persist($task1_3_3);

		$activity4 = new Activity();
		$activity4->setNumber("Activité 1.4")
		->setName("Recherche et choix des fournisseurs de la PME")
		->setSkill("Rechercher et sélectionner les fournisseurs");
		$manager->persist($activity4);

		$task1_4_1 = new Task();
		$task1_4_1->setNumber("Tache 1.4.1")
		->setName("Etude des projets d'achats et des investissements")
		->setActivity($activity4);
		$manager->persist($task1_4_1);

		$task1_4_2 = new Task();
		$task1_4_2->setNumber("Tache 1.4.2")
		->setName("Recherche des fournisseurs")
		->setActivity($activity4);
		$manager->persist($task1_4_2);

		$task1_4_3 = new Task();
		$task1_4_3->setNumber("Tache 1.4.3")
		->setName("Comparaison des offres, selection et qualification des fournisseurs")
		->setActivity($activity4);
		$manager->persist($task1_4_3);

		$task1_4_4 = new Task();
		$task1_4_4->setNumber("Tache 1.4.4")
		->setName("Mise à jour du système d'information fournisseurs")
		->setActivity($activity4);
		$manager->persist($task1_4_4);

		$task1_4_5 = new Task();
		$task1_4_5->setNumber("Tache 1.4.5")
		->setName("Communication avec des acteurs internes et avec les fournisseurs")
		->setActivity($activity4);
		$manager->persist($task1_4_5);

		$activity5 = new Activity();
		$activity5->setNumber("Activité 1.5")
		->setName("Suivi et contrôle des opérations d’achats et d'investissement de la PME")
		->setSkill("Passer les commandes d'achat et d'immobilisation et les contrôler, valider le règlement, évaluer les fournisseurs");
		$manager->persist($activity5);

		$task1_5_1 = new Task();
		$task1_5_1->setNumber("Tache 1.5.1")
		->setName("Préparation de la négociation des contrats")
		->setActivity($activity5);
		$manager->persist($task1_5_1);

		$task1_5_2 = new Task();
		$task1_5_2->setNumber("Tache 1.5.2")
		->setName("Passation et suivi des commandes")
		->setActivity($activity5);
		$manager->persist($task1_5_2);

		$task1_5_3 = new Task();
		$task1_5_3->setNumber("Tache 1.5.3")
		->setName("Acquisition et suivi des immobilisations")
		->setActivity($activity5);
		$manager->persist($task1_5_3);

		$task1_5_4 = new Task();
		$task1_5_4->setNumber("Tache 1.5.4")
		->setName("Contrôle des achats et des règlements")
		->setActivity($activity5);
		$manager->persist($task1_5_4);

		$task1_5_5 = new Task();
		$task1_5_5->setNumber("Tache 1.5.5")
		->setName("Evaluation des fournisseurs")
		->setActivity($activity5);
		$manager->persist($task1_5_5);

		$task1_5_6 = new Task();
		$task1_5_6->setNumber("Tache 1.5.6")
		->setName("Communication écrite et orale avec des acteurs internes, les fournisseurs et les partenaires financiers")
		->setActivity($activity5);
		$manager->persist($task1_5_6);

		$activity6 = new Activity();
		$activity6->setNumber("Activité 1.6")
		->setName("Suivi comptable des opérations avec les clients et les fournisseurs de la PME")
		->setSkill("Assurer le suivi comptable des opérations commerciales");
		$manager->persist($activity6);

		$task1_6_1 = new Task();
		$task1_6_1->setNumber("Tache 1.6.1")
		->setName("Contrôle de l'enregistrement comptable des opérations d'achats, de ventes et de règlements")
		->setActivity($activity6);
		$manager->persist($task1_6_1);

		$task1_6_2 = new Task();
		$task1_6_2->setNumber("Tache 1.6.2")
		->setName("Suivi des relations avec les banques")
		->setActivity($activity6);
		$manager->persist($task1_6_2);

		$task1_6_3 = new Task();
		$task1_6_3->setNumber("Tache 1.6.3")
		->setName("Suivi de la tresorerie des comptes de tiers, des encaissements et des decaissements")
		->setActivity($activity6);
		$manager->persist($task1_6_3);

		$task1_6_4 = new Task();
		$task1_6_4->setNumber("Tache 1.6.4")
		->setName("Préparation et contrôle de la déclaration de TVA")
		->setActivity($activity6);
		$manager->persist($task1_6_4);

		$task1_6_5 = new Task();
		$task1_6_5->setNumber("Tache 1.6.5")
		->setName("Evaluation et suivi des risques liés aux échanges internationaux")
		->setActivity($activity6);
		$manager->persist($task1_6_5);

		$modality1 = new Modality();
		$modality1->setNumber(1)
		->setTitle("Avant la formation");
		$manager->persist($modality1);

		$modality2 = new Modality();
		$modality2->setNumber(2)
		->setTitle("Pendant la formation en établissement");
		$manager->persist($modality2);

		$modality3 = new Modality();
		$modality3->setNumber(3)
		->setTitle("Pendant la formation dans une PME");
		$manager->persist($modality3);

		$modality4 = new Modality();
		$modality4->setNumber(4)
		->setTitle("Pendant la formation dans une organisation");
		$manager->persist($modality4);

		$problem1 = new Problem();
		$problem1->setNumber(1)
		->setTitle("Problème de GRCF");
		$manager->persist($problem1);

		$problem2 = new Problem();
		$problem2->setNumber(2)
		->setTitle("Problème de communication orale");
		$manager->persist($problem2);

		$problem3 = new Problem();
		$problem3->setNumber(3)
		->setTitle("Problème de communication écrite");
		$manager->persist($problem3);

		$term1 = new Term();
		$term1->setNumber(1)
		->setTitle("En autonomie");
		$manager->persist($term1);

		$term2 = new Term();
		$term2->setNumber(2)
		->setTitle("Accompagnée");
		$manager->persist($term2);

		$term3 = new Term();
		$term3->setNumber(3)
		->setTitle("En observation");
		$manager->persist($term3);

		$manager->flush();
	}
}
