<?php

namespace App\Twig;

use App\Entity\Recipe;
use App\Service\RecipeService;
use DateInterval;
use DateInvalidOperationException;
use DateTimeImmutable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('date_interval', [$this, 'formatDateInterval']),
            new TwigFilter('rating_stars', [$this, 'formatRatingStars']),
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
}