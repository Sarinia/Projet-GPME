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
        // ->add('createdAt')
        ->add('establishment', EntityType::class, ['class' => Establishment::class, 'label' => false, 'choice_label' => function ($establishment) { return $establishment->getName()." (".$establishment->getDepartment()->getName().")"; }])
        ->add('user', UserType::class, ['label' => false])
        ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
}
