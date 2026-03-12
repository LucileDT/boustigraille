<?php

namespace App\Service;

use DateInterval;
use DateTimeImmutable;

/**
 * Class containing methods used to manage dates
 */
class DateService
{
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