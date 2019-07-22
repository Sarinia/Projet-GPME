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
		$lastName = "nom sadmin";
		$firstName = "prenom sadmin";
		$slug = $slugify->Slugify($lastName."-".$firstName);

		$encoded = $this->encoder->encodePassword($user, "pass");

		$user->setLastName($lastName)
		->setFirstName($firstName)
		->setEmail("sadmin@email.fr")
		->setHash($encoded)
		->setSlug($slug)
		->setTitle("ROLE_SADMIN")
		->setCreatedAt(new \DateTime())
		->setExist(1);
		$manager->persist($user);

		$sadmin = new Sadmin();
		$sadmin->setUser($user);
		$manager->persist($sadmin);

		// Département
		$department = new Department();
		$department->setName("Alpes-Maritimes")
		->setCreatedAt(new \DateTime())
		->setExist(1);
		$manager->persist($department);

		// Etablissement
		$establishment = new Establishment();
		$slugify = new Slugify();
		$name = "Le Parc Impérial";
		$slug = $slugify->slugify($name);

		$establishment->setDepartment($department)
		->setName($name)
		->setAdress("adresse")
		->setPostalCode("06000")
		->setCity("NICE")
		->setBackgroundUrl("https://via.placeholder.com/1920x1080.png")
		->setSlug($slug)
		->setCreatedAt(new \DateTime())
		->setExist(1);
		$manager->persist($establishment);

		// Administrateur
		$user = new User();
		$slugify = new Slugify();
		$lastName = "nom admin";
		$firstName = "prenom admin";
		$slug = $slugify->Slugify($lastName."-".$firstName);

		$encoded = $this->encoder->encodePassword($user, "pass");

		$user->setLastName($lastName)
		->setFirstName($firstName)
		->setEmail("admin@email.fr")
		->setHash($encoded)
		->setSlug($slug)
		->setTitle("ROLE_ADMIN")
		->setCreatedAt(new \DateTime())
		->setExist(1);
		$manager->persist($user);

		$admin = new Admin();
		$admin->setUser($user)
		->setEstablishment($establishment);
		$manager->persist($admin);

		// classe
		$classroom = new Classroom();
		$slugify = new Slugify();
		$degree = "BTS GPME";
		$startDate = "2019";
		$endDate = "2020";
		$slug = $slugify->slugify($degree."-".$startDate."-".$endDate);

		$classroom->setDegree($degree)
		->setStartDate($startDate)
		->setEndDate($endDate)
		->setSlug($slug)
		->setEstablishment($establishment)
		->setCreatedAt(new \DateTime())
		->setExist(1);
		$manager->persist($classroom);

		// enseignant
		$user = new User();
		$slugify = new Slugify();
		$lastName = "nom enseignant";
		$firstName = "prenom enseignant";
		$slug = $slugify->Slugify($lastName."-".$firstName);

		$encoded = $this->encoder->encodePassword($user, "pass");

		$user->setLastName($lastName)
		->setFirstName($firstName)
		->setEmail("teacher@email.fr")
		->setHash($encoded)
		->setSlug($slug)
		->setTitle("ROLE_TEACHER")
		->setCreatedAt(new \DateTime())
		->setExist(1);
		$manager->persist($user);

		$teacher = new Teacher();
		$teacher->setUser($user)
		->setEstablishment($establishment)
		->addClassroom($classroom);
		$manager->persist($teacher);

		// étudiant
		$user = new User();
		$slugify = new Slugify();
		$lastName = "nom étudiant";
		$firstName = "prenom étudiant";
		$slug = $slugify->Slugify($lastName."-".$firstName);

		$encoded = $this->encoder->encodePassword($user, "pass");

		$user->setLastName($lastName)
		->setFirstName($firstName)
		->setEmail("student@email.fr")
		->setHash($encoded)
		->setSlug($slug)
		->setTitle("ROLE_USER")
		->setCreatedAt(new \DateTime())
		->setExist(1);
		$manager->persist($user);

		$student = new Student();
		$student->setUser($user)
		->setEstablishment($establishment)
		->addClassroom($classroom);
		$manager->persist($student);

		// activitées
		$activity1 = new Activity();
		$activity1->setEntitle(1)
		->setName("Activité 1.1 - Recherche de clientèle et contact")
		->setSkill("Rechercher des clients par prospection ou pour répondre à un appel d'offres");
		$manager->persist($activity1);

		$task1_1_1 = new Task();
		$task1_1_1->setEntitle(1)
		->setName("Tache 1.1.1 - Organisation de la prospection et prospection clientèle")
		->setActivity($activity1);
		$manager->persist($task1_1_1);

		$task1_1_2 = new Task();
		$task1_1_2->setEntitle(2)
		->setName("Tache 1.1.2 - Détection, analyse et suivi des appels d'offres")
		->setActivity($activity1);
		$manager->persist($task1_1_2);

		$task1_1_3 = new Task();
		$task1_1_3->setEntitle(3)
		->setName("Tache 1.1.3 - Communication avec des acteurs internes, avec les prospects, les clients et les pouvoirs adjudicateurs")
		->setActivity($activity1);
		$manager->persist($task1_1_3);

		$activity2 = new Activity();
		$activity2->setEntitle(2)
		->setName("Activité 1.2 - Administration des ventes de la PME")
		->setSkill("Traiter la demande du client (de la demande de devis jusqu'à la relance des éventuels impayés)");
		$manager->persist($activity2);

		$task1_2_1 = new Task();
		$task1_2_1->setEntitle(4)
		->setName("Tache 1.2.1 - Préparation de propositions commerciales")
		->setActivity($activity2);
		$manager->persist($task1_2_1);

		$task1_2_2 = new Task();
		$task1_2_2->setEntitle(5)
		->setName("Tache 1.2.2 - Préparation des contrats commerciaux.")
		->setActivity($activity2);
		$manager->persist($task1_2_2);

		$task1_2_3 = new Task();
		$task1_2_3->setEntitle(6)
		->setName("Tache 1.2.3 - Suivi des ventes et des livraisons")
		->setActivity($activity2);
		$manager->persist($task1_2_3);

		$task1_2_4 = new Task();
		$task1_2_4->setEntitle(7)
		->setName("Tache 1.2.4 - Facturation, suivi des règlements et des relances \"clients\"")
		->setActivity($activity2);
		$manager->persist($task1_2_4);

		$task1_2_5 = new Task();
		$task1_2_5->setEntitle(8)
		->setName("Tache 1.2.5 - Evaluation du risque client")
		->setActivity($activity2);
		$manager->persist($task1_2_5);

		$task1_2_6 = new Task();
		$task1_2_6->setEntitle(9)
		->setName("Tache 1.2.6 - Mise à jour du système d'information \"clients\"")
		->setActivity($activity2);
		$manager->persist($task1_2_6);

		$task1_2_7 = new Task();
		$task1_2_7->setEntitle(10)
		->setName("Tache 1.2.7 - Communication avec des acteurs internes, les fournisseurs et les clients")
		->setActivity($activity2);
		$manager->persist($task1_2_7);

		$activity3 = new Activity();
		$activity3->setEntitle(3)
		->setName("Activité 1.3 - Maintien et développement de la relation avec les clients de la PME")
		->setSkill("Informer, conseiller, orienter et traiter les réclamations");
		$manager->persist($activity3);

		$task1_3_1 = new Task();
		$task1_3_1->setEntitle(11)
		->setName("Tache 1.3.1 - Accueil, information et conseils")
		->setActivity($activity3);
		$manager->persist($task1_3_1);

		$task1_3_2 = new Task();
		$task1_3_2->setEntitle(12)
		->setName("Tache 1.3.2 - Traitement et suivi des reclamations")
		->setActivity($activity3);
		$manager->persist($task1_3_2);

		$task1_3_3 = new Task();
		$task1_3_3->setEntitle(13)
		->setName("Tache 1.3.3 - Communication pour développer la relation client")
		->setActivity($activity3);
		$manager->persist($task1_3_3);

		$activity4 = new Activity();
		$activity4->setEntitle(4)
		->setName("Activité 1.4 - Recherche et choix des fournisseurs de la PME")
		->setSkill("Rechercher et sélectionner les fournisseurs");
		$manager->persist($activity4);

		$task1_4_1 = new Task();
		$task1_4_1->setEntitle(14)
		->setName("Tache 1.4.1 - Etude des projets d'achats et des investissements")
		->setActivity($activity4);
		$manager->persist($task1_4_1);

		$task1_4_2 = new Task();
		$task1_4_2->setEntitle(15)
		->setName("Tache 1.4.2 - Recherche des fournisseurs")
		->setActivity($activity4);
		$manager->persist($task1_4_2);

		$task1_4_3 = new Task();
		$task1_4_3->setEntitle(16)
		->setName("Tache 1.4.3 - Comparaison des offres, selection et qualification des fournisseurs")
		->setActivity($activity4);
		$manager->persist($task1_4_3);

		$task1_4_4 = new Task();
		$task1_4_4->setEntitle(17)
		->setName("Tache 1.4.4 - Mise à jour du système d'information fournisseurs")
		->setActivity($activity4);
		$manager->persist($task1_4_4);

		$task1_4_5 = new Task();
		$task1_4_5->setEntitle(18)
		->setName("Tache 1.4.5 - Communication avec des acteurs internes et avec les fournisseurs")
		->setActivity($activity4);
		$manager->persist($task1_4_5);

		$activity5 = new Activity();
		$activity5->setEntitle(5)
		->setName("Activité 1.5 - Suivi et contrôle des opérations d’achats et d'investissement de la PME")
		->setSkill("Passer les commandes d'achat et d'immobilisation et les contrôler, valider le règlement, évaluer les fournisseurs");
		$manager->persist($activity5);

		$task1_5_1 = new Task();
		$task1_5_1->setEntitle(19)
		->setName("Tache 1.5.1 - Préparation de la négociation des contrats")
		->setActivity($activity5);
		$manager->persist($task1_5_1);

		$task1_5_2 = new Task();
		$task1_5_2->setEntitle(20)
		->setName("Tache 1.5.2 - Passation et suivi des commandes")
		->setActivity($activity5);
		$manager->persist($task1_5_2);

		$task1_5_3 = new Task();
		$task1_5_3->setEntitle(21)
		->setName("Tache 1.5.3 - Acquisition et suivi des immobilisations")
		->setActivity($activity5);
		$manager->persist($task1_5_3);

		$task1_5_4 = new Task();
		$task1_5_4->setEntitle(22)
		->setName("Tache 1.5.4 - Contrôle des achats et des règlements")
		->setActivity($activity5);
		$manager->persist($task1_5_4);

		$task1_5_5 = new Task();
		$task1_5_5->setEntitle(23)
		->setName("Tache 1.5.5 - Evaluation des fournisseurs")
		->setActivity($activity5);
		$manager->persist($task1_5_5);

		$task1_5_6 = new Task();
		$task1_5_6->setEntitle(24)
		->setName("Tache 1.5.6 - Communication écrite et orale avec des acteurs internes, les fournisseurs et les partenaires financiers")
		->setActivity($activity5);
		$manager->persist($task1_5_6);

		$activity6 = new Activity();
		$activity6->setEntitle(6)
		->setName("Activité 1.6 - Suivi comptable des opérations avec les clients et les fournisseurs de la PME")
		->setSkill("Assurer le suivi comptable des opérations commerciales");
		$manager->persist($activity6);

		$task1_6_1 = new Task();
		$task1_6_1->setEntitle(25)
		->setName("Tache 1.6.1 - Contrôle de l'enregistrement comptable des opérations d'achats, de ventes et de règlements")
		->setActivity($activity6);
		$manager->persist($task1_6_1);

		$task1_6_2 = new Task();
		$task1_6_2->setEntitle(26)
		->setName("Tache 1.6.2 - Suivi des relations avec les banques")
		->setActivity($activity6);
		$manager->persist($task1_6_2);

		$task1_6_3 = new Task();
		$task1_6_3->setEntitle(27)
		->setName("Tache 1.6.3 - Suivi de la tresorerie des comptes de tiers, des encaissements et des decaissements")
		->setActivity($activity6);
		$manager->persist($task1_6_3);

		$task1_6_4 = new Task();
		$task1_6_4->setEntitle(28)
		->setName("Tache 1.6.4 - Préparation et contrôle de la déclaration de TVA")
		->setActivity($activity6);
		$manager->persist($task1_6_4);

		$task1_6_5 = new Task();
		$task1_6_5->setEntitle(29)
		->setName("Tache 1.6.5 - Evaluation et suivi des risques liés aux échanges internationaux")
		->setActivity($activity6);
		$manager->persist($task1_6_5);

		$modality1 = new Modality();
		$modality1->setEntitle(1)
		->setName("Avant la formation");
		$manager->persist($modality1);

		$modality2 = new Modality();
		$modality2->setEntitle(2)
		->setName("Pendant la formation en établissement");
		$manager->persist($modality2);

		$modality3 = new Modality();
		$modality3->setEntitle(3)
		->setName("Pendant la formation dans une PME");
		$manager->persist($modality3);

		$modality4 = new Modality();
		$modality4->setEntitle(4)
		->setName("Pendant la formation dans une organisation");
		$manager->persist($modality4);

		$problem1 = new Problem();
		$problem1->setEntitle(1)
		->setName("Problème de GRCF");
		$manager->persist($problem1);

		$problem2 = new Problem();
		$problem2->setEntitle(2)
		->setName("Problème de communication orale");
		$manager->persist($problem2);

		$problem3 = new Problem();
		$problem3->setEntitle(3)
		->setName("Problème de communication écrite");
		$manager->persist($problem3);

		$term1 = new Term();
		$term1->setEntitle(1)
		->setName("En autonomie");
		$manager->persist($term1);

		$term2 = new Term();
		$term2->setEntitle(2)
		->setName("Accompagnée");
		$manager->persist($term2);

		$term3 = new Term();
		$term3->setEntitle(3)
		->setName("En observation");
		$manager->persist($term3);

		// fiche SP
		$card = new Card();
		$card->setStudent($student)
		->setNumbersp(1)
		->setProblem($problem1)
		->setModality($modality1)
		->setTerm($term1)
		->setActivity($activity1)
		->setTask($task1_1_1)
		->setCreatedAt(new \DateTime())
		->setExist(1)
		->setAssociate(true);
		$manager->persist($card);

		// passport
		$passport = new Passport();
		$passport->setStudent($student)
		->setCreatedAt(new \DateTime())
		->setExist(1);
		$manager->persist($passport);

		$manager->flush();
	}
}
