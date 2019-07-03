<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Card;
use App\Entity\Modality;
use App\Entity\Problem;
use App\Entity\Task;
use App\Entity\Term;
use App\Form\TaskType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('createdAt')
            // ->add('student')
            // ->add('passport')
            ->add('associate', ChoiceType::class, ['choices'  => ['Oui' => true,'Non' => false,],])
            ->add('problem', EntityType::class, ['class' => Problem::class, 'label' => false, 'choice_value' => function (Problem $value = null) { return $value ? $value->getNumber() : '';}, 'choice_label' => function ($problem) { return $problem->getTitle();}])
            // ->add('numbersp', TextType::class, ['label' => false, 'required' => false])
            ->add('modality', EntityType::class, ['class' => Modality::class, 'label' => false, 'choice_label' => function ($modality) { return $modality->getTitle();}])
            ->add('term', EntityType::class, ['class' => Term::class, 'label' => false, 'choice_label' => function ($term) { return $term->getTitle();}])
            ->add('activity', EntityType::class, ['class' => Activity::class, 'label' => false, 'choice_label' => function ($activity) { return $activity->getNumber()." ".$activity->getName();}])
            // ->add('tasks')
            ->add('entitledsp', TextType::class, ['label' => false, 'required' => false])
            ->add('infossp', TextType::class, ['label' => false, 'required' => false])
            ->add('framesp', TextType::class, ['label' => false, 'required' => false])
            ->add('problemmanagsp', TextType::class, ['label' => false, 'required' => false])
            ->add('problemcomosp', TextType::class, ['label' => false, 'required' => false])
            ->add('problemcomwsp', TextType::class, ['label' => false, 'required' => false])
            ->add('actorssp', TextType::class, ['label' => false, 'required' => false])
            ->add('targetsp', TextType::class, ['label' => false, 'required' => false])
            ->add('conditionssp', TextType::class, ['label' => false, 'required' => false])
            ->add('resourcessp', TextType::class, ['label' => false, 'required' => false])
            ->add('answerssp', TextType::class, ['label' => false, 'required' => false])
            ->add('productionssp', TextType::class, ['label' => false, 'required' => false])
            ->add('writtensp', TextType::class, ['label' => false, 'required' => false])
            ->add('oralsp', TextType::class, ['label' => false, 'required' => false])
            ->add('contributionsp', TextType::class, ['label' => false, 'required' => false])
            ->add('analysissp', TextType::class, ['label' => false, 'required' => false])
            ->add('monthsp', TextType::class, ['label' => false, 'required' => false])
            ->add('yearsp', TextType::class, ['label' => false, 'required' => false])
            ->add('exist', ChoiceType::class, ['choices'  => ['Oui - Visible par tout le monde' => true,'Non - Non visible par tout le monde' => false,],])
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}