<?php

namespace App\Form;

use App\Entity\Admin;
use App\Entity\Establishment;
use App\Form\UserType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('establishment', EntityType::class, ['class' => Establishment::class, 'label' => 'Selectionner l\'établissement','required' => false, 'placeholder' => 'Choisir un établissement', 'choice_label' => function ($establishment) { return $establishment->getName()." (".$establishment->getDepartment()->getName().")"; }])
        ->add('user', UserType::class)
        ->add('save', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
}