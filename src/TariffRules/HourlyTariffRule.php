<?php

namespace Inteleon\SmsPark\TariffRules;

use Inteleon\SmsPark\Parking;
use Inteleon\SmsPark\TariffRules\TariffRuleInterface;

class HourlyTariffRule implements TariffRuleInterface
{
    /** int */
    private $hourlyRate;

    public function __construct($hourlyRate, $fromHour = 0, $fromMinute = 0, $toHour = 0, $toMinute = 0)
    {
        $this->hourlyRate = $hourlyRate;
        $this->fromHour = $fromHour;
        $this->fromMinute = $fromMinute;
        $this->toHour = $toHour;
        $this->toMinute = $toMinute;
    }

    public function execute(Parking $parking)
    {
        return $parking;
    }
}
