<?php

namespace App\Form;

use App\Entity\Teacher;
use App\Form\AccountUserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountTeacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        // ->add('createdAt')
        // ->add('establishment')
        // ->add('classroom')
        ->add('user', AccountUserType::class)
        ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Teacher::class,
        ]);
    }
}
