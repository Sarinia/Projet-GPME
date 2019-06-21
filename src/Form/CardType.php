<?php

namespace App\Form;

use App\Entity\Card;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prob1')
            ->add('prob2')
            ->add('prob3')
            ->add('numbersp')
            ->add('mod1')
            ->add('mod2')
            ->add('mod3')
            ->add('cond1')
            ->add('cond2')
            ->add('cond3')
            ->add('entitledsp')
            ->add('infossp')
            ->add('framesp')
            ->add('problemmanagsp')
            ->add('problemcomosp')
            ->add('problemcomwsp')
            ->add('actorssp')
            ->add('targetsp')
            ->add('conditionssp')
            ->add('resourcessp')
            ->add('answerssp')
            ->add('productionssp')
            ->add('writtensp')
            ->add('oralsp')
            ->add('contributionsp')
            ->add('analysissp')
            ->add('exist')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}
