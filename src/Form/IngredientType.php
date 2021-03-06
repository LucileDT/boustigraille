<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('barcode', TextType::class, [
                'label' => 'Code barre',
                'required' => false,
            ])
            ->add('brand', TextType::class, [
                'label' => 'Marque',
                'required' => false,
            ])
            ->add('shop', TextType::class, [
                'label' => 'Magasin',
                'required' => false,
            ])
            ->add('measureType', TextType::class, [
                'label' => 'Manière de mesurer (g, ml, l, ...) *',
            ])
            ->add('unitSize', IntegerType::class, [
                'label' => 'Taille d\'une unité',
                'required' => false,
                'help' => 'Par exemple pour des barres de céréales, le poids d\'une barre.',
            ])
            ->add('portionSize', IntegerType::class, [
                'label' => 'Taille d\'une part moyenne',
                'required' => false,
                'help' => 'Par exemple pour du riz cru, le poids de ce que je mangerais en un repas.',
            ])
            ->add('shopBatchSize', NumberType::class, [
                'label' => 'Vendus par paquets de',
                'required' => false,
                'help' => 'Par exemple pour des pâtes, le poids d\'un paquet tel que vendu.',
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
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
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
