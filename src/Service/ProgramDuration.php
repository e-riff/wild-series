<?php

namespace App\Service;

use App\Entity\Program;
use DateInterval;
use DateTime;
use DateTimeImmutable;

class ProgramDuration
{
      public function calculate(Program $program): array
    {
        $baseTime = new DateTimeImmutable();
        $baseWithInterval = new DateTime();
        foreach($program->getSeasons() as $season)
        {
            foreach($season->getEpisodes() as $episode)
            {
                $baseWithInterval->add(new DateInterval("PT" . $episode->getDuration() . "M"));
            }
        }

        $totalInterval = $baseTime->diff($baseWithInterval);

        return [
            'years'=>$totalInterval->format('%Y'),
            'months'=>$totalInterval->format('%M'),
            'days'=>$totalInterval->format('%D'),
            'hours'=>$totalInterval->format('%H'),
            'minutes'=>$totalInterval->format('%I'),
            'total'=>$this->intervalToSeconds($totalInterval)
        ];
    }

    private function intervalToSeconds(DateInterval $interval):string
    {
        return $interval->days * 1440 + $interval->h * 60 + $interval->i;
    }
}