<?php

namespace App\Form;

use App\Entity\Tchat;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TchatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('topic', TextType::class, ['label' => 'Sujet *'])
        // ->add('createdAt')
        // ->add('forwarder')
        // ->add('recipient', EntityType::class, ['class' => User::class, 'label' => false, 'required' => false, 'placeholder' => 'Choisir un destinataire', 'choice_label' => function ($user) { return $user->getLastName()." ".$user->getFirstName(); }])
        ->add('save', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tchat::class,
        ]);
    }
}
