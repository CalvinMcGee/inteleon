<?php

namespace Inteleon\SmsPark\TariffRules;

use Inteleon\SmsPark\Parking;
use Inteleon\SmsPark\TariffRules\TariffRuleInterface;

class HourlyTariffRule implements TariffRuleInterface
{
    /** int */
    private $hourlyRate;

    /** int */
    private $fromHour;

    /** int */
    private $fromMinute;

    /** int */
    private $toHour;

    /** int */
    private $toMinute;

    /** int */
    private $maxFee;

    public function __construct($hourlyRate, $fromHour = 0, $fromMinute = 0, $toHour = 0, $toMinute = 0, $maxFee = null)
    {
        $this->hourlyRate = $hourlyRate;
        $this->fromHour = $fromHour;
        $this->fromMinute = $fromMinute;
        $this->toHour = $toHour;
        $this->toMinute = $toMinute;
        $this->maxFee = $maxFee;
    }

    public function execute(Parking $parking)
    {
        $tariffStart = new \Datetime(sprintf('%s %02d:%02d:00', $parking->getCurrent()->format('Y-m-d'), $this->fromHour, $this->fromMinute));

        if($parking->getCurrent() < $tariffStart) {
            return $parking;
        }

        $tariffEnd = new \Datetime(sprintf('%s %02d:%02d:00', $parking->getCurrent()->format('Y-m-d'), $this->toHour, $this->toMinute));

        if($parking->getEndDate() > $tariffEnd) {
            $period = $parking->getCurrent()->diff($tariffEnd);
            $parking->setCurrent($tariffEnd);
        } else {
            $period = $parking->getCurrent()->diff($parking->getEndDate());
            $parking->setCurrent($parking->getEndDate());
        }

        $fee = $this->calculateFee($period);

        $parking->addTariffPart($fee);
        return $parking;
    }

    private function calculateFee(\DateInterval $period)
    {
        $fee = ($period->h + $period->i/60 + $period->s/3600) * $this->hourlyRate;

        if($this->maxFee && $fee > $this->maxFee) {
            return $this->maxFee;
        }

        return $fee;
    }
}
