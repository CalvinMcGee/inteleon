<?php

namespace Inteleon\SmsPark\TariffRules;

use Inteleon\SmsPark\Parking;
use Inteleon\SmsPark\TariffRules\TariffRuleInterface;

class HourlyTariffRule implements TariffRuleInterface
{
    /** @var int */
    private $maxFee;

    /** @var array */
    private $rulePart = [];

    /**
     * @param int $maxFee
     */
    public function __construct($maxFee = null)
    {
        $this->maxFee = $maxFee;
    }

    /**
     * @param int $hourlyRate
     * @param int $fromHour
     * @param int $fromMinute
     * @param int $toHour
     * @param int $toMinute
     * @param int $duration
     */
    public function addRulePart($hourlyRate = 0, $fromHour = 0, $fromMinute = 0, $toHour = 0, $toMinute = 0, $duration = null)
    {
        $rulePart = new \stdClass();
        $rulePart->hourlyRate = $hourlyRate;
        $rulePart->fromHour = $fromHour;
        $rulePart->fromMinute = $fromMinute;
        $rulePart->toHour = $toHour;
        $rulePart->toMinute = $toMinute;
        $rulePart->duration = $duration;

        $this->rulePart[] = $rulePart;
    }

    /**
     * @param Parking $parking
     * @return Parking
     */
    public function execute(Parking $parking)
    {
        $clonedParking = clone $parking;
        $endOfDay = new \Datetime(sprintf('%s 24:00:00', $parking->getCurrent()->format('Y-m-d')));

        if ($clonedParking->getEndDate() > $endOfDay) {
            $clonedParking->setEndDate($endOfDay);
        }

        $iterator = new \ArrayIterator($this->rulePart);

        while ($iterator->valid()) {
            $clonedParking = $this->executePart($clonedParking, $iterator->current());

            $iterator->next();
        }

        $sum = $this->calculateTotal($clonedParking);
        $parking->addTariffPart($sum);
        $parking->setCurrent($clonedParking->getCurrent());

        return $parking;
    }

    /**
     * @param Parking $parking
     * @return Parking
     */
    private function executePart(Parking $parking, \stdClass $rulePart)
    {
        $tariffStart = new \Datetime(sprintf('%s %02d:%02d:00', $parking->getCurrent()->format('Y-m-d'), $rulePart->fromHour, $rulePart->fromMinute));

        if ($parking->getCurrent() < $tariffStart) {
            return $parking;
        }

        if ($rulePart->duration) {
            $tariffEnd = clone $parking->getCurrent();
            $tariffEnd->add(new \DateInterval(sprintf('PT%dH', $rulePart->duration)));
        } else {
            $tariffEnd = new \Datetime(sprintf('%s %02d:%02d:00', $parking->getCurrent()->format('Y-m-d'), $rulePart->toHour, $rulePart->toMinute));
        }

        if ($parking->getCurrent() > $tariffEnd) {
            return $parking;
        }

        if ($parking->getEndDate() > $tariffEnd) {
            $period = $parking->getCurrent()->diff($tariffEnd);
            $parking->setCurrent($tariffEnd);
        } else {
            $period = $parking->getCurrent()->diff($parking->getEndDate());
            $parking->setCurrent($parking->getEndDate());
        }

        $fee = $this->calculateFee($period, $rulePart->hourlyRate);

        $parking->addTariffPart($fee);
        return $parking;
    }

    /**
     * @param \DateInterval $period
     * @param int $hourlyRate
     * @return int
     */
    private function calculateFee(\DateInterval $period, $hourlyRate = 0)
    {
        $fee = ($period->d*24 + $period->h + $period->i/60 + $period->s/3600) * $hourlyRate;

        return $fee;
    }

    /**
     * @param Parking $parking
     * @return int|float
     */
    private function calculateTotal(Parking $parking)
    {
        $sum = array_sum($parking->getTariffParts());

        if ($this->maxFee && $sum > $this->maxFee) {
            return $this->maxFee;
        }

        return $sum;
    }
}
