<?php

namespace App\Twig;

use App\Entity\Recipe;
use App\Service\RecipeService;
use App\Service\TagService;
use DateInterval;
use DateInvalidOperationException;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(private TagService $tagService) {}

    public function getFilters(): array
    {
        return [
            new TwigFilter('date_interval', [$this, 'formatDateInterval']),
            new TwigFilter('rating_stars', [$this, 'formatRatingStars']),
            new TwigFilter('vegan_icon', [$this, 'formatVeganIcon']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('average_score', [$this, 'calculateAverageScore']),
        ];
    }

    public function calculateAverageScore(Recipe $recipe): float|null
    {
        return RecipeService::getAverageGrade($recipe);
    }

    public function formatRatingStars(?float $ratingOnFive): string {
        if ($ratingOnFive === null) {
            return str_repeat('<i class="ri-star-line recipe-not-rated"></i>', 5);
        }
        $ratingOnTen = round($ratingOnFive * 2);
        $hasHalfStar = false;
        if ($ratingOnTen % 2 !== 0) {
            $hasHalfStar = true;
        }
        $rating = $ratingOnTen / 2;

        $fullStars = str_repeat('<i class="ri-star-fill"></i>', $rating);
        $invertRating = 5 - $rating;
        $emptyStars = str_repeat('<i class="ri-star-line"></i>', $invertRating);

        $stars = $fullStars;
        if ($hasHalfStar) {
            $stars .= '<i class="ri-star-half-line"></i>';
        }
        return $stars . $emptyStars;
    }

    /**
     * @throws DateInvalidOperationException
     */
    public function formatDateInterval(?DateInterval $dateInterval): string {
        if (empty($dateInterval)) {
            return '-';
        }
        $now = new DateTimeImmutable();
        $dateIntervalInSeconds = $now->add($dateInterval)->getTimestamp() - $now->getTimestamp();

        $oneHour = $now->getTimestamp() - $now->sub(new DateInterval('PT1H'))->getTimestamp();

        if ($dateIntervalInSeconds === 0) {
            return '-';
        } elseif ($dateIntervalInSeconds < $oneHour) {
            return $dateInterval->format('%i min');
        } else {
            if ($dateInterval->i === 0) {
                return $dateInterval->format('%hh');
            } else {
                return $dateInterval->format('%hh%I');
            }
        }
    }

    /**
     * Return a Remix Icon depending on the presence of a Vegetarian or Vegan tag in the given collection
     *
     * @param PersistentCollection $tags
     * @return string
     */
    public function formatVeganIcon(PersistentCollection $tags): string
    {
        if ($this->tagService->isVegan($tags)) {
            return '<i class="ri-plant-fill text-success" data-bs-toggle="tooltip" title="Vegan"></i>';
        } else if ($this->tagService->isVegetarian($tags)) {
            return '<i class="ri-seedling-fill text-success" data-bs-toggle="tooltip" title="Végé"></i>';
        } else {
            return '<i class="ri-skull-line" data-bs-toggle="tooltip" title="Carné"></i>';
        }
    }
}