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

        return [
            'years'=>$baseTime->diff($baseWithInterval)->format('%Y'),
            'months'=>$baseTime->diff($baseWithInterval)->format('%M'),
            'days'=>$baseTime->diff($baseWithInterval)->format('%D'),
            'hours'=>$baseTime->diff($baseWithInterval)->format('%H'),
            'minutes'=>$baseTime->diff($baseWithInterval)->format('%I'),
        ];
    }
}