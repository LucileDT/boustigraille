<?php

namespace App\Twig;

use DateInterval;
use DateInvalidOperationException;
use DateTimeImmutable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('date_interval', [$this, 'format_date_interval']),
        ];
    }

    /**
     * @throws DateInvalidOperationException
     */
    public function format_date_interval(?DateInterval $dateInterval): string {
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