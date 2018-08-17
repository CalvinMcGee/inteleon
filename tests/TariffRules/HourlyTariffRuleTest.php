<?php

namespace Inteleon\SmsPark\Test\TariffRules;

use PHPUnit\Framework\TestCase;
use Inteleon\SmsPark\Parking;
use Inteleon\SmsPark\TariffRules\HourlyTariffRule;

class HourlyTariffRuleTest extends TestCase
{
    /** @var HourlyTariffRule */
    private $hourlyTariffRule;

    /** Parking */
    private $parking;

    public function setUp()
    {
        $this->hourlyTariffRule = new HourlyTariffRule(10, 0, 0, 15, 0);
        $this->parking = new Parking(new DateTime('2018-01-01 00:00:00'), new DateTime('2018-01-01 16:00:00'));
        
    }

    public function testExecute()
    {
        $actualParking = $this->hourlyTariffRule->execute($this->parking);
        $this->assertEquals([150], $actualParking->getTariffParts());
    }
}
