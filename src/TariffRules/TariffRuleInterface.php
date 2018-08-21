<?php

namespace Inteleon\SmsPark\TariffRules;

use Inteleon\SmsPark\Parking;

interface TariffRuleInterface
{
    public function execute(Parking $parking);
}
