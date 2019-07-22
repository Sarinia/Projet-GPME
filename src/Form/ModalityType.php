<?php

namespace App\Form;

use App\Entity\Modality;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModalityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('entitle', TextType::class, ['label' => 'Numéro *'])
        ->add('name', TextType::class, ['label' => 'Nom *'])
        ->add('save', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Modality::class,
        ]);
    }
}