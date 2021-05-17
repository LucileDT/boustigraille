<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\IngredientQuantityForRecipe;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientQuantityForRecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', NumberType::class, [
                'label' => 'Quantité *'
            ])
            ->add('ingredient', EntityType::class, [
                'label' => 'Ingrédient *',
                'class' => Ingredient::class,
                'choice_label' => function($ingredient, $key, $index) {
                    $message = '';
                    if (empty($ingredient->getPortionSize()))
                    {
                        $message = $ingredient->getLabel();
                    }
                    else
                    {
                        $message = sprintf(
                                '%s (une part moyenne ≃ %s %s)',
                                $ingredient->getLabel(),
                                $ingredient->getPortionSize(),
                                $ingredient->getMeasureType()
                        );
                    }
                    return $message;
                },
                'query_builder' => function (EntityRepository $entityRepository) {
                        return $entityRepository
                                ->createQueryBuilder('i')
                                ->orderBy('i.label', 'ASC');
                    },
                'choice_attr' => ChoiceList::attr($this, function (?Ingredient $ingredient) {
                    return $ingredient ? [
                        'data-proteins' => $ingredient->getProteins(),
                        'data-fat' => $ingredient->getFat(),
                        'data-carbohydrates' => $ingredient->getCarbohydrates(),
                        'data-energy' => $ingredient->getEnergy(),
                    ] : [];
                }),
                'attr' => [
                    'class' => 'ingredient-select',
                ],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IngredientQuantityForRecipe::class,
        ]);
    }
}
