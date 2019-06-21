<?php

namespace App\Form;

use App\Entity\Establishment;
use App\Repository\DepartmentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewEstablishmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('adress', TextType::class)
            ->add('postalcode', TextType::class)
            ->add('city', TextType::class)
            ->add('latitude', TextType::class, array('required' => false,))
            ->add('longitude', TextType::class, array('required' => false,))
            ->add('backgroundurl', UrlType::class)
            ->add('exist', ChoiceType::class, ['choices'  => ['Oui' => true,'Non' => false,],])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Establishment::class,
        ]);
    }
}
