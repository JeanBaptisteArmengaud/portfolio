<?php

namespace App\Service;

use App\Entity\Calendar\Movie;
use App\Entity\Calendar\ScreeningSchedule;
use App\Repository\Calendar\ScreeningScheduleRepository;

class MovieScreeningSchedules
{
    private ScreeningScheduleRepository $screeningScheduleRepository;

    public function __construct(ScreeningScheduleRepository $screeningScheduleRepository)
    {
        $this->screeningScheduleRepository = $screeningScheduleRepository;
    }

    public function checkScreeningSchedules(Movie $movie): Movie
    {
        $weeks = $movie->getScreeningSchedules()->getValues();
        foreach ($weeks as $week) {
            $weekExists = $this->screeningScheduleRepository->findOneByDates($week->getWeekStart(), $week->getWeekEnd());
            if ($weekExists instanceof ScreeningSchedule) {
                $movie->removeScreeningSchedule($week);
                $movie->addScreeningSchedule($weekExists);
            }
        }

        return $movie;
    }
}
