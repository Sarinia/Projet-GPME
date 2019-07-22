<?php

namespace App\Form;

use App\Entity\Student;
use App\Form\AccountUserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountStudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('candidateNb', TextType::class,['label' => 'Numéro de candidat', 'required' => false])
        ->add('birthDate', DateType::class, ['label' => 'Date de naissance', 'required' => false, 'widget'=> 'single_text'])
        // ->add('classrooms')
        // ->add('establishment')
        ->add('user', AccountUserType::class)
        ->add('save', SubmitType::class, ['label' => 'Mettre à jour', 'attr' => ['class' => 'btn btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}