<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\Establishment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EstablishmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('department', EntityType::class, ['class' => Department::class, 'label' => 'Sélectionner le département', 'required' => false, 'placeholder' => 'Choisir un établissement', 'choice_label' => function ($department) { return $department->getName(); }])
        ->add('name', TextType::class, ['label' => 'Nom *'])
        ->add('adress', TextType::class, ['label' => 'Adresse *'])
        ->add('postalcode', TextType::class, ['label' => 'Code postal *'])
        ->add('city', TextType::class, ['label' => 'Ville *'])
        ->add('backgroundurl', UrlType::class, ['label' => 'Url de l\'image de l\'écran de connexion', 'required' => false])
        // ->add('slug')
        // ->add('createdAt')
        ->add('exist', ChoiceType::class, ['label' => 'Activer l\'établissement', 'choices'  => ['Oui - Visible par tout le monde' => true,'Non - Non visible par tout le monde' => false,],])
        ->add('save', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Establishment::class,
        ]);
    }
}