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
        ->add('department', EntityType::class, ['class' => Department::class, 'label' => false, 'choice_label' => function ($department) { return $department->getName(); }])
        ->add('name', TextType::class)
        ->add('adress', TextType::class)
        ->add('postalcode', TextType::class)
        ->add('city', TextType::class)
        ->add('latitude', TextType::class, array('required' => false,))
        ->add('longitude', TextType::class, array('required' => false,))
        ->add('backgroundurl', UrlType::class)
        // ->add('slug')
        ->add('exist', ChoiceType::class, ['choices'  => ['Oui - Visible par tout le monde' => true,'Non - Non visible par tout le monde' => false,],])
        // ->add('createdAt')
        ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Establishment::class,
        ]);
    }
}
