<?php

namespace App\Form;

use App\Entity\MealList;
use App\FormDataObject\GroceryListFDO;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroceryListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mealLists', EntityType::class, [
                'class' => MealList::class,
                // 'choices' => $options['mealLists'],
                'choice_label' => function (MealList $mealList) {
                    // Returning empty label because this form will be used
                    // inside a meal list table containing all the meal list data
                    return ' ';
                },
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GroceryListFDO::class,
            'csrf_token_id'   => 'task_item',
        ]);
    }
}
