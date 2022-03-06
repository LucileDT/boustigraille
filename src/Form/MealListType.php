<?php

namespace App\Form;

use App\Entity\MealList;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MealListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('personName', TextType::class, [
                'label' => 'Nom de la personne *'
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Commence au *',
                'widget' => 'single_text',
            ])
            ->add('isStartingAtLunch', ChoiceType::class, [
                'label' => 'Le *',
                'choices' => [
                    'Midi' => true,
                    'Soir' => false,
                ],
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Termine au *',
                'widget' => 'single_text',
            ])
            ->add('isEndingAtLunch', ChoiceType::class, [
                'label' => 'Le *',
                'choices' => [
                    'Midi' => true,
                    'Soir' => false,
                ],
            ])
            ->add('meals', CollectionType::class, [
                'entry_type' => MealQuantityForListType::class,
                'label' => 'Repas',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false, // use addMeal and removeMeal in MealList
            ])
            ->add('comment')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MealList::class,
        ]);
    }
}
