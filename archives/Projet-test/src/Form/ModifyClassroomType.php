<?php

namespace App\Form;

use App\Entity\Classroom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifyClassroomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('degree', TextType::class)
            ->add('startDate', DateType::class,['widget'=>'single_text','format'=>'yyyy'])
            ->add('endDate', DateType::class,['widget'=>'single_text','format'=>'yyyy'])
            // ->add('slug')
            ->add('exist', ChoiceType::class, ['choices'  => ['Oui' => true,'Non' => false,],])
            // ->add('establishment')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Classroom::class,
        ]);
    }
}
