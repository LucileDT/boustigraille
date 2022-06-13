<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\IngredientQuantityForRecipe;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientQuantityForRecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', NumberType::class, [
                'label' => 'Quantité par part *'
            ])
            ->add('ingredient', EntityType::class, [
                'label' => 'Ingrédient *',
                'class' => Ingredient::class,
                'choice_label' => function($ingredient, $key, $index) {
                    $message = $ingredient->getLabel();
                    if (!empty($ingredient->getPortionSize()) || !empty($ingredient->getUnitSize()))
                    {
                        $message .= ' (';
                    }

                    if (!empty($ingredient->getUnitSize()))
                    {
                        $message .= sprintf(
                                'une unité = %s %s',
                                $ingredient->getUnitSize(),
                                $ingredient->getMeasureType()
                        );
                    }

                    if (!empty($ingredient->getPortionSize()))
                    {
                        if (!empty($ingredient->getUnitSize()))
                        {
                            $message .= ' & ';
                        }

                        $message .= sprintf(
                                'une part moyenne ≃ %s %s',
                                $ingredient->getPortionSize(),
                                $ingredient->getMeasureType()
                        );
                    }

                    if (!empty($ingredient->getPortionSize()) || !empty($ingredient->getUnitSize()))
                    {
                        $message .= ')';
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
                        'data-measure-type' => $ingredient->getMeasureType(),
                        'data-has-unit-measure-saved' => $ingredient->getUnitSize() > 0,
                        'data-unit-measure-conversion-rate' => $ingredient->getUnitSize() / 100,
                    ] : [];
                }),
                'attr' => [
                    'class' => 'ingredient-select',
                ],
            ])
            ->add('isMeasuredByUnit', ChoiceType::class, [
                'choices'  => [
                    'g' => false,
                    'unité' => true,
                ],
                'choice_attr' => [
                    'g' => ['class' => 'ingredient-measure-type'],
                    'unité' => ['class' => 'absolute-unit'],
                ],
                'attr' => [
                    'class' => 'ingredient-quantity-type',
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
