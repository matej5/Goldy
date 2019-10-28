<?php

namespace App\Form;

use App\Entity\Food;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class FoodFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = $options['category'];

        $builder
            ->add('name')
            ->add('content')
            ->add('price')
            ->add(
                'category',
                ChoiceType::class,
                [
                    'choices' => $choices,
                    'choice_label' => 'name',
                    'label' => 'Select category',
                    'multiple' => true,
                    'expanded' => true,
                    'by_reference' => false,
                    'empty_data' => 0
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => null,
                'category' => ''
            ]
        );
    }
}