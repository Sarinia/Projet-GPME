<?php

namespace App\Form;

use App\Form\ActivityType;
use App\Form\ModalityType;
use App\Form\ProblemType;
use App\Form\TaskType;
use App\Form\TermType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReferentielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('activity', ActivityType::class)
            ->add('task', TaskType::class)
            ->add('problem', ProblemType::class)
            ->add('modality', ModalityType::class)
            ->add('term', TermType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
