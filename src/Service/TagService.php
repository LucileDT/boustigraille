<?php

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Form\FormEvent;

class TagService
{
    public function __construct(private TagRepository $tagRepository) {}

    /**
     * Create new tags submitted through a Select2 component and add them to the form
     *
     * @param EntityManagerInterface $entityManager
     * @param FormEvent $event
     * @return void
     */
    public function manageTagsFromSelect2Form(EntityManagerInterface $entityManager, FormEvent &$event): void
    {
        $formData = $event->getData();

        if (!$formData) {
            return; // form data is empty, no need to continue
        }

        if (!empty($formData['tags'])) {
            // this array can contain tag ids (the tag already existed) or tag labels (the tag is newly created)
            $tagsSelectId = $formData['tags'];

            // check for each tag if it's an existing one or if it needs to be created
            foreach ($tagsSelectId as $index => $tagSelectId) {
                if (is_numeric($tagSelectId) && $entityManager->getRepository(Tag::class)->find($tagSelectId)) {
                    continue; // tag exists
                }

                // tag does not exist, create it
                $tag = new Tag();
                $tag->setLabel($tagSelectId);
                $entityManager->persist($tag);
                $entityManager->flush();

                // after creation, set the true id in the form data
                $formData['tags'][$index] = (string)$tag->getId();
            }
            $event->setData($formData);
        }
    }


    public function getVegetarianTag(): Tag
    {
        return $this->tagRepository->findOneBy(['label' => 'Végé']);
    }

    public function getVeganTag(): Tag
    {
        return $this->tagRepository->findOneBy(['label' => 'Vegan']);
    }

    public function isVegetarian(PersistentCollection $tags): bool
    {
        return $tags->contains($this->getVegetarianTag());
    }
    public function isVegan(PersistentCollection $tags): bool
    {
        return $tags->contains($this->getVeganTag());
    }
}