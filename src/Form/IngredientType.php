<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Store;
use App\Entity\Tag;
use App\Service\TagService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientType extends AbstractType
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TagService $tagService
    ) {}

    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
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
            ->add('store', EntityType::class, [
                'class' => Store::class,
                'label' => 'Magasin',
                'required' => false,
                'choice_label' => function (Store $store) {
                    return $store->getLabel();
                },
                'query_builder' => function (EntityRepository $entityRepository) {
                    return $entityRepository
                        ->createQueryBuilder('s')
                        ->orderBy('s.label', 'ASC');
                },
                'multiple' => false,
                'expanded' => false,
                'label_attr' => [
                    'class' => 'mb-0 me-2',
                ],
            ])
            ->add('measureType', TextType::class, [
                'label' => 'Unité de mesure (g, ml, l, ...) *',
            ])
            ->add('unitSize', NumberType::class, [
                'label' => 'Taille d\'une unité',
                'required' => false,
                'help' => 'Pour des barres de céréales, le poids d\'une barre.',
            ])
            ->add('portionSize', IntegerType::class, [
                'label' => 'Taille d\'une part moyenne',
                'required' => false,
                'help' => 'Pour du riz cru, le poids de ce que je mangerais en un repas.',
            ])
            ->add('shopBatchSize', NumberType::class, [
                'label' => 'Vendus par paquets de',
                'required' => false,
                'help' => 'Pour des pâtes, le poids d\'un paquet tel que vendu.',
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
            ->add('hasStockCheckNeededBeforeBuying', CheckboxType::class, [
                'label' => 'Vérifier dans les placards avant d\'acheter',
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
                'required' => false,
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
            ])
            ->add('lastSynchronizedAt', DateTimeType::class, [
                'attr' => ['class' => 'd-none'],
            ])
        ;
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $this->tagService->manageTagsFromSelect2Form($this->entityManager, $event);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
