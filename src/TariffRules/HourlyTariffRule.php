<?php

namespace Inteleon\SmsPark\TariffRules;

use Inteleon\SmsPark\Parking;
use Inteleon\SmsPark\TariffRules\TariffRuleInterface;

class HourlyTariffRule implements TariffRuleInterface
{
    /** @var int */
    private $hourlyRate;

    /** @var int */
    private $fromHour;

    /** @var int */
    private $fromMinute;

    /** @var int */
    private $toHour;

    /** @var int */
    private $toMinute;

    /** @var int */
    private $duration;

    /** @var int */
    private $maxFee;

    /**
     * @param int $hourlyRate
     * @param int $fromHour
     * @param int $fromMinute
     * @param int $toHour
     * @param int $toMinute
     * @param int $duration
     * @param int $maxFee
     */
    public function __construct($hourlyRate = 0, $fromHour = 0, $fromMinute = 0, $toHour = 0, $toMinute = 0, $duration = null, $maxFee = null)
    {
        $this->hourlyRate = $hourlyRate;
        $this->fromHour = $fromHour;
        $this->fromMinute = $fromMinute;
        $this->toHour = $toHour;
        $this->toMinute = $toMinute;
        $this->duration = $duration;
        $this->maxFee = $maxFee;
    }

    /**
     * @param Parking $parking
     * @return Parking
     */
    public function execute(Parking $parking)
    {
        $tariffStart = new \Datetime(sprintf('%s %02d:%02d:00', $parking->getCurrent()->format('Y-m-d'), $this->fromHour, $this->fromMinute));

        if($parking->getCurrent() < $tariffStart) {
            return $parking;
        }

        if($this->duration) {
            $tariffEnd = clone $parking->getCurrent();
            $tariffEnd->add(new \DateInterval(sprintf('PT%dH', $this->duration)));
        } else {
            $tariffEnd = new \Datetime(sprintf('%s %02d:%02d:00', $parking->getCurrent()->format('Y-m-d'), $this->toHour, $this->toMinute));
        }

        if($parking->getCurrent() > $tariffEnd) {
            return $parking;
        }

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

    /**
     * @param \DateInterval $period
     * @return int
     */
    private function calculateFee(\DateInterval $period)
    {
        $fee = ($period->d*24 + $period->h + $period->i/60 + $period->s/3600) * $this->hourlyRate;

        if($this->maxFee && $fee > $this->maxFee) {
            return $this->maxFee;
        }

        return $fee;
    }
}
