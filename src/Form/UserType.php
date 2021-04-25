<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Responsibility;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
            ])
            ->add('responsibilities', EntityType::class, [
                // looks for choices from this entity
                'class' => Responsibility::class,
                // uses the Responsibility.label property as the visible option string
                'choice_label' => 'label',
                'label' => 'Responsabilités',
                'multiple' => true,
                'expanded' => true,
                'choice_attr' => function($responsibility)
                {
                    return [
                        'data-responsibility-label' => $responsibility->getLabel(),
                        'data-responsibility-description' => $responsibility->getDescription(),
                    ];
                },
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
