<?php

namespace Inteleon\SmsPark;

use Inteleon\SmsPark\TariffRules\TariffRuleInterface;
use Inteleon\SmsPark\Exceptions\SmsParkException;

class ParkingTariff implements \IteratorAggregate
{
    /** @var array */
    private $tariffRules = [];

    /**
     * @param TariffRuleInterface $tariffRule
     * @throws SmsParkException
     */
    public function addTariffRule($tariffRule)
    {
        // @todo add Closure support
        if(!$tariffRule instanceof TariffRuleInterface) {
            throw new SmsParkException(sprintf('Tariff rule must be an instance of TariffRuleInterface but %s given', gettype($tariffRule)));
        }

        $this->tariffRules[] = $tariffRule;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->tariffRules);
    }
}
