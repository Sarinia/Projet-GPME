<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AdminRepository;
use App\Repository\ClassroomRepository;
use App\Repository\DepartmentRepository;
use App\Repository\EstablishmentRepository;
use App\Repository\SadminRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use App\Repository\UserRepository;
use App\Repository\CardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
	/**
	* @Route("/dashboard", name="dashboard")
	*/
	public function dashboard()
	{
		// si un utilisateur est connecté
		if ($this->getUser() && $this->getUser()->getExist() == true) {

			if ($this->getUser()->getTitle() == 'ROLE_USER') {
				return $this->RedirectToRoute('dashboard_student', [
					'slug' => $this->getUser()->getSlug(),
				]);
			}

			if ($this->getUser()->getTitle() == 'ROLE_TEACHER') {
				return $this->RedirectToRoute('dashboard_teacher', [
					'slug' => $this->getUser()->getSlug(),
				]);
			}

			if ($this->getUser()->getTitle() == 'ROLE_ADMIN') {
				return $this->RedirectToRoute('dashboard_admin', [
					'slug' => $this->getUser()->getSlug(),
				]);
			}

			if ($this->getUser()->getTitle() == 'ROLE_SADMIN') {
				return $this->RedirectToRoute('dashboard_sadmin', [
					'slug' => $this->getUser()->getSlug(),
				]);
			}
		} else {
			return $this->redirectToRoute('homepage');
		}
	}
	/**
	* @Route("/dashboard/etudiant/{slug}", name="dashboard_student")
	*/
	public function dashboard_student(User $user, StudentRepository $studentRepo)
	{
		// Récupération de l'utilisateur connecté
		$user = $this->getUser();
		$student = $studentRepo->findOneBy(['user' => $user]);

		// on retourne la vue et les données
		return $this->render('dashboard/student.html.twig', [
			'student' => $student,
		]);
	}

	/**
	* @Route("/dashboard/enseignant/{slug}", name="dashboard_teacher")
	*/
	public function dashboard_teacher(User $user, TeacherRepository $teacherRepo)
	{
		// Récupération des informations de la personne connecté
		$user = $this->getUser();
		$teacher = $teacherRepo->findOneBy(['user' => $user]);

		// on retourne la vue et les données
		return $this->render('dashboard/teacher.html.twig', [
			'teacher' => $teacher,
		]);
	}

	/**
	* @Route("/dashboard/admin/{slug}", name="dashboard_admin")
	*/
	public function dashboard_admin(User $user, UserRepository $userRepo, AdminRepository $adminRepo, TeacherRepository $teacherRepo, StudentRepository $studentRepo, ClassroomRepository $classroomRepo)
	{
		// Récupération des informations de la personne connecté
		$user = $this->getUser();
		$admin = $adminRepo->findOneBy(['user' => $user]);

		// Récupération de la liste des enseignants créés
		$teachers = $teacherRepo->findBy(['establishment' => $admin->getEstablishment()->getId()],['createdAt' => 'DESC'],5);

		// Récupération de la liste des étudiants créés
		$students = $studentRepo->findBy(['establishment' => $admin->getEstablishment()->getId()],['createdAt' => 'DESC'],5);

		// Récupération de la liste des classes créées
		$classrooms = $classroomRepo->findBy(['establishment' => $admin->getEstablishment()->getId()],['createdAt' => 'DESC'],5);

		return $this->render('dashboard/admin.html.twig', [
			'admin' => $admin,
			'teachers' => $teachers,
			'students' => $students,
			'classrooms' => $classrooms,
		]);
	}

	/**
	* @Route("/dashboard/sadmin/{slug}", name="dashboard_sadmin")
	*/
	public function dashboard_sadmin(User $user, UserRepository $userRepo, SadminRepository $sadminRepo, AdminRepository $adminRepo, TeacherRepository $teacherRepo, StudentRepository $studentRepo, ClassroomRepository $classroomRepo, EstablishmentRepository $estabRepo, DepartmentRepository $departmentRepo)
	{
		// Récupération des informations de la personne connecté
		$user = $this->getUser();
		$sadmin = $sadminRepo->findOneBy(['user' => $user]);

		// Récupération de la liste des derniers administrateurs créés
		$admins = $adminRepo->findBy([],['createdAt' => 'DESC'],5);

		// Récupération de la liste des derniers enseignants créés
		$teachers = $teacherRepo->findBy([],['createdAt' => 'DESC'],5);

		// Récupération de la liste des derniers étudiants créés
		$students = $studentRepo->findBy([],['createdAt' => 'DESC'],5);

		// Récupération de la liste des dernieres classes créées
		$classrooms = $classroomRepo->findBy([],['createdAt' => 'DESC'],5);

		// Récupération de la liste des derniers établissements créés
		$establishments = $estabRepo->findBy([],['createdAt' => 'DESC'],5);

		// Récupération de la liste des derniers départements créés
		$departments = $departmentRepo->findBy([],['createdAt' => 'DESC'],5);

		return $this->render('dashboard/sadmin.html.twig', [
			'sadmin' => $sadmin,
			'admins' => $admins,
			'teachers' => $teachers,
			'students' => $students,
			'classrooms' => $classrooms,
			'establishments' => $establishments,
			'departments' => $departments,
		]);
	}
}
