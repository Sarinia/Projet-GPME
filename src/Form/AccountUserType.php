<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('lastName', TextType::class, ['label' => 'Nom *'])
        ->add('firstName', TextType::class, ['label' => 'Prénom *'])
        ->add('email', EmailType::class, ['label' => 'Email *'])
        // ->add('hash')
        // ->add('slug')
        // ->add('title')
        // ->add('createdAt')
        // ->add('exist')
        // ->add('sadmin')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
