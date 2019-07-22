<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword',PasswordType::class, ['label' => 'Ancien mot de passe *'])
            ->add('newPassword',PasswordType::class, ['label' => 'Nouveau mot de passe *'])
            ->add('confirmPassword',PasswordType::class, ['label' => 'Confirmer le nouveau mot de passe *'])
            ->add('save', SubmitType::class, ['label' => 'Mettre à jour', 'attr' => ['class' => 'btn btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
