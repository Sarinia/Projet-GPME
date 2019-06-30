<?php

namespace App\Form;

use App\Entity\Establishment;
use App\Entity\Student;
use App\Form\UserType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('candidateNb')
            ->add('birthDate', DateType::class, ['widget'=> 'single_text'])
            // ->add('createdAt')
            ->add('establishment', EntityType::class, ['class' => Establishment::class, 'label' => false, 'choice_label' => function ($establishment) { return $establishment->getName()." (".$establishment->getDepartment()->getName().")"; }])
            // ->add('classroom')
            ->add('user', UserType::class, ['label' => false])
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
