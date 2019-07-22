<?php

namespace App\Form;

use App\Entity\Establishment;
use App\Entity\Student;
use App\Form\UserType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('candidateNb', TextType::class,['label' => 'Numéro de candidat', 'required' => false])
        ->add('birthDate', DateType::class, ['label' => 'Date de naissance', 'widget'=> 'single_text', 'required' => false])
        ->add('establishment', EntityType::class, ['class' => Establishment::class, 'label' => false, 'choice_label' => function ($establishment) { return $establishment->getName()." (".$establishment->getDepartment()->getName().")"; }])
        ->add('user', UserType::class)
        // ->add('classrooms')
        ->add('save', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
