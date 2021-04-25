<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Label *',
            ])
            ->add('brand', TextType::class, [
                'label' => 'Marque *',
            ])
            ->add('shop', TextType::class, [
                'label' => 'Magasin',
                'required' => false,
            ])
            ->add('measureType', TextType::class, [
                'label' => 'Manière de mesurer (g, ml, l, ...) *',
            ])
            ->add('portionSize', IntegerType::class, [
                'label' => 'Taille d\'une portion',
            ])
            ->add('proteins', NumberType::class, [
                'label' => 'Protéines (g) *',
            ])
            ->add('carbohydrates', NumberType::class, [
                'label' => 'Glucides (g) *',
            ])
            ->add('fat', NumberType::class, [
                'label' => 'Lipides (g) *',
            ])
            ->add('energy', NumberType::class, [
                'label' => 'Énergie (kcal) *',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
