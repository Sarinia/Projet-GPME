<?php

namespace App\Form;

use App\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ModifyStudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('candidateNb',IntegerType::class, array('required' => false,))
        ->add('lastName',TextType::class)
        ->add('firstName',TextType::class)
        ->add('email',EmailType::class)
        // ->add('hash')
        ->add('birthDate',DateType::class,['widget'=>'single_text'])
        //->add('slug',HiddenType::class)
        ->add('exist', ChoiceType::class, ['choices'  => ['Oui' => true,'Non' => false,],])
        //->add('userRoles')
    ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
