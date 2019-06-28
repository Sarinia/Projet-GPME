<?php

namespace App\Form;

use App\Entity\Classroom;
use App\Entity\Establishment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClassroomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('degree', TextType::class)
            ->add('startDate', TextType::class)
            ->add('endDate', TextType::class)
            // ->add('slug')
            ->add('exist', ChoiceType::class, ['choices'  => ['Oui - Visible par tout le monde' => true,'Non - Non visible par tout le monde' => false,],])
            ->add('establishment', EntityType::class, ['class' => Establishment::class, 'label' => false, 'choice_label' => function ($establishment) { return $establishment->getName()." (".$establishment->getDepartment()->getName().")"; }])
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-primary']]);
            // ->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Classroom::class,
        ]);
    }
}
