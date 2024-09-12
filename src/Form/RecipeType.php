<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RecipeType extends AbstractType
{
    public function __construct(
        private EntityManagerInterface $entityManager,
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
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
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
            $formData = $event->getData();

            if (!$formData) {
                return; // form data is empty, no need to continue
            }

            // this array can contain tag ids (the tag already existed) or tag labels (the tag is newly created)
            $tagsSelectId = $formData['tags'];

            // check for each tag if it's an existing one or if it needs to be created
            foreach ($tagsSelectId as $index => $tagSelectId) {
                if (is_numeric($tagSelectId) && $this->entityManager->getRepository(Tag::class)->find($tagSelectId)) {
                    continue; // tag exists
                }

                // tag does not exist, create it
                $tag = new Tag();
                $tag->setLabel($tagSelectId);
                $this->entityManager->persist($tag);
                $this->entityManager->flush();

                // after creation, set the true id in the form data
                $formData['tags'][$index] = (string) $tag->getId();
            }
            $event->setData($formData);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
