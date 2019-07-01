<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('activity', EntityType::class, ['class' => Activity::class, 'label' => 'Séléctionner l\'activité :', 'choice_label' => function ($activity) { return $activity->getNumber()." ".$activity->getName(); }])
        ->add('number', TextType::class, ['label' => 'Numéro :'])
        ->add('name', TextType::class, ['label' => 'Nom :'])
        ->add('save', SubmitType::class, ['label' => 'Enregistrer'],['attr' => ['class' => 'btn btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
