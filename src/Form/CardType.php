<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Card;
use App\Entity\Modality;
use App\Entity\Problem;
use App\Entity\Term;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
        // ->add('numbersp')
        ->add('entitledsp', TextType::class, ['label' => false, 'required' => false, 'attr' => ['class' => 'form-control-sm']])
        ->add('infossp', TextType::class, ['label' => false, 'required' => false, 'attr' => ['class' => 'form-control-sm']])
        ->add('framesp', TextType::class, ['label' => false, 'required' => false, 'attr' => ['class' => 'form-control-sm']])
        ->add('problemmanagsp', TextType::class, ['label' => false, 'required' => false, 'attr' => ['class' => 'form-control-sm']])
        ->add('problemcomosp', TextType::class, ['label' => false, 'required' => false, 'attr' => ['class' => 'form-control-sm']])
        ->add('problemcomwsp', TextType::class, ['label' => false, 'required' => false, 'attr' => ['class' => 'form-control-sm']])
        ->add('actorssp', TextType::class, ['label' => false, 'required' => false, 'attr' => ['class' => 'form-control-sm']])
        ->add('targetsp', TextType::class, ['label' => false, 'required' => false, 'attr' => ['class' => 'form-control-sm']])
        ->add('conditionssp', TextType::class, ['label' => false, 'required' => false, 'attr' => ['class' => 'form-control-sm']])
        ->add('resourcessp', TextType::class, ['label' => false, 'required' => false, 'attr' => ['class' => 'form-control-sm']])
        ->add('answerssp', TextType::class, ['label' => false, 'required' => false, 'attr' => ['class' => 'form-control-sm']])
        ->add('productionssp', TextType::class, ['label' => false, 'required' => false, 'attr' => ['class' => 'form-control-sm']])
        ->add('writtensp', TextType::class, ['label' => false, 'required' => false, 'attr' => ['class' => 'form-control-sm']])
        ->add('oralsp', TextType::class, ['label' => false, 'required' => false, 'attr' => ['class' => 'form-control-sm']])
        ->add('contributionsp', TextType::class, ['label' => false, 'required' => false, 'attr' => ['class' => 'form-control-sm']])
        ->add('analysissp', TextType::class, ['label' => false, 'required' => false, 'attr' => ['class' => 'form-control-sm']])
        ->add('exist', ChoiceType::class, ['label' => 'Activer la fiche de situation professionnelle', 'attr' => ['class' => 'form-control-sm'], 'choices'  => ['Oui - Visible par tout le monde' => true,'Non - Non visible par tout le monde' => false,],])
        // ->add('createdAt')
        ->add('datesp', TextType::class, ['label' => 'Date', 'required' => false, 'attr' => ['class' => 'form-control-sm']])
        ->add('associate', ChoiceType::class, ['label' => 'Associer la fiche au passeport professionnel', 'attr' => ['class' => 'form-control-sm'],'choices'  => ['Oui - Associer à mon passeport' => true,'Non - Ne pas associer à mon passeport' => false,],])
        // ->add('student')
        ->add('problem', EntityType::class, ['class' => Problem::class, 'label' => false, 'choice_value' => function (Problem $value = null) { return $value ? $value->getEntitle() : '';}, 'choice_label' => function ($problem) { return $problem->getName();}])
        ->add('modality', EntityType::class, ['class' => Modality::class, 'label' => false, 'choice_label' => function ($modality) { return $modality->getName();}])
        ->add('term', EntityType::class, ['class' => Term::class, 'label' => false, 'choice_label' => function ($term) { return $term->getName();}])
        ->add('activity', EntityType::class, ['class' => Activity::class, 'label' => false, 'choice_label' => function ($activity) { return $activity->getName();}])
        // ->add('tasks', EntityType::class, ['class' => Task::class, 'label' => false, 'multiple' => true, 'expanded' => true, 'choice_label' => 'name'])
        ->add('save', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}