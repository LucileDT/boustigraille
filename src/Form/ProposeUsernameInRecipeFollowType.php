<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProposeUsernameInRecipeFollowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'attr' => ['placeholder' => 'Username'],
                'help' => 'Ce formulaire permet de proposer à une autre personne inscrite de voir votre pseudo sur les recettes que vous avez rédigées. Une fois la proposition envoyée, lae destinataire recevra une notification et pourra l\'accepter ou la refuser.',
            ])
        ;
    }
}
