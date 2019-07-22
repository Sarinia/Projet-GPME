<?php

namespace App\Form;

use App\Entity\Classroom;
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
        ->add('degree', TextType::class, ['label' => 'Diplôme *'])
        ->add('startDate', TextType::class, ['label' => 'Date de début *'])
        ->add('endDate', TextType::class, ['label' => 'Date de fin *'])
        // ->add('slug')
        // ->add('createdAt')
        ->add('exist', ChoiceType::class, ['label' => 'Activer la classe', 'choices'  => ['Oui - Visible par tout le monde' => true,'Non - Non visible par tout le monde' => false,],])
        // ->add('establishment')
        // ->add('teachers')
        // ->add('students')
        ->add('save', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Classroom::class,
        ]);
    }
}