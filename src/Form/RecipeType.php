<?php

namespace App\Form;

use App\Entity\DifficultyLevel;
use App\Entity\Recipe;
use App\Entity\Tag;
use App\Service\TagService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RecipeType extends AbstractType
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TagService $tagService
    ) {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom *',
            ])
            ->add('main_picture', FileType::class, [
                'label' => 'Photo de couverture',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024M',
                        'mimeTypes' => [
                            'image/gif',
                            'image/png',
                            'image/jpeg',
                            'image/bmp',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Prière de téléverser une image valide.',
                    ])
                ],
            ])
            ->add('ingredients', CollectionType::class, [
                'entry_type' => IngredientQuantityForRecipeType::class,
                'label' => 'Ingrédients',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false, // use addIngredient and removeIngredient in Recipe
            ])
            ->add('difficulty_level', EntityType::class, [
                'class' => DifficultyLevel::class,
                'label' => 'Niveau de difficulté',
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                'choice_label' => function (DifficultyLevel $difficulty): string {
                    return $difficulty->getSelectName();
                }
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
            ])
            ->add('preparation_duration', DateIntervalType::class, [
                'label' => 'Temps de préparation',
                'required' => false,
                'with_years'  => false,
                'with_months' => false,
                'with_days'   => false,
                'with_hours'  => true,
                'with_minutes'  => true,
                'labels' => [
                    'days' => 'Jours',
                    'hours' => 'Heures',
                    'minutes' => 'Minutes',
                ],
                'attr' => ['class' => 'form_duration row'],
                'help' => 'Si la préparation est réalisée en plusieurs étapes, renseignez ici le temps total.',
            ])
            ->add('cooking_duration', DateIntervalType::class, [
                'label' => 'Temps de cuisson',
                'required' => false,
                'with_years'  => false,
                'with_months' => false,
                'with_days'   => false,
                'with_hours'  => true,
                'with_minutes'  => true,
                'labels' => [
                    'days' => 'Jours',
                    'hours' => 'Heures',
                    'minutes' => 'Minutes',
                ],
                'attr' => ['class' => 'form_duration row'],
                'help' => 'Si la cuisson est réalisée en plusieurs étapes, renseignez ici le temps total.',
            ])
            ->add('rest_duration', DateIntervalType::class, [
                'label' => 'Temps de repos',
                'required' => false,
                'with_years'  => false,
                'with_months' => false,
                'with_days'   => true,
                'with_hours'  => true,
                'with_minutes'  => true,
                'labels' => [
                    'days' => 'Jours',
                    'hours' => 'Heures',
                    'minutes' => 'Minutes',
                ],
                'attr' => ['class' => 'form_duration row'],
                'help' => 'Si le repos des pâtes est réalisé en plusieurs étapes, renseignez ici le temps total.',
            ])
            ->add('process', TextareaType::class, [
                'label' => 'Recette',
                'required' => false,
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
            ])
        ;
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $this->tagService->manageTagsFromSelect2Form($this->entityManager, $event);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
