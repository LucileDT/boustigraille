<?php

namespace App\Form;

use App\FormDataObject\UserNutritionalDataFDO;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserNutritionalDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            'data_class' => UserNutritionalDataFDO::class,
        ]);
    }
}
