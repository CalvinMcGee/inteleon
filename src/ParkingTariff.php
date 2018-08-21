<?php

namespace Inteleon\SmsPark;

use Inteleon\SmsPark\TariffRules\TariffRuleInterface;
use Inteleon\SmsPark\Exceptions\SmsParkException;

class ParkingTariff
{
    /** @var array */
    private $tariffRules = [];

    public function addTariffRule($tariffRule)
    {
        // @todo add Closure support
        if(!$tariffRule instanceof TariffRuleInterface) {
            throw new SmsParkException(sprintf('Tariff rule must be an instance of TariffRuleInterface but %s given', gettype($tariffRule)));
        }

        $this->tariffRules[] = $tariffRule;
    }
}
