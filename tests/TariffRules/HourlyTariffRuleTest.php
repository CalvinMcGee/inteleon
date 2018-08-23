<?php

namespace Inteleon\SmsPark\Test\TariffRules;

use PHPUnit\Framework\TestCase;
use Inteleon\SmsPark\Parking;
use Inteleon\SmsPark\TariffRules\HourlyTariffRule;

class HourlyTariffRuleTest extends TestCase
{

    /**
     * Set up test
     */
    public function setUp()
    {
    }

    public function testSimple()
    {
        $tariff = new HourlyTariffRule();
        $tariff->addRulePart(5, 9, 0, 18, 0);
        $parking = new Parking(new \DateTime('2018-01-01 10:00:00'), new \DateTime('2018-01-01 13:00:00'));
        $actualParking = $tariff->execute($parking);
        $this->assertEquals([15], $actualParking->getTariffParts());
        $this->assertEquals($actualParking->getEndDate(), $actualParking->getCurrent());
    }

    public function testSimpleDuration()
    {
        $tariff = new HourlyTariffRule();
        $tariff->addRulePart(10, 9, 0, 18, 0, 1);
        $parking = new Parking(new \DateTime('2018-01-01 10:00:00'), new \DateTime('2018-01-01 13:00:00'));
        $actualParking = $tariff->execute($parking);
        $this->assertEquals([10], $actualParking->getTariffParts());
        $this->assertEquals(new \DateTime('2018-01-01 11:00:00'), $actualParking->getCurrent());
    }

    public function testSimpleMaxFee()
    {
        $tariff = new HourlyTariffRule(25);
        $tariff->addRulePart(5, 9, 0, 18, 0);
        $parking = new Parking(new \DateTime('2018-01-01 10:00:00'), new \DateTime('2018-01-01 17:00:00'));
        $actualParking = $tariff->execute($parking);
        $this->assertEquals([25], $actualParking->getTariffParts());
        $this->assertEquals($actualParking->getEndDate(), $actualParking->getCurrent());
    }

    public function testComplex()
    {
        $tariff = new HourlyTariffRule();
        $tariff->addRulePart(0, 0, 0, 9, 0);
        $tariff->addRulePart(10, 9, 0, 18, 0, 1);
        $tariff->addRulePart(5, 9, 0, 18, 0);
        $parking = new Parking(new \DateTime('2018-01-01 08:00:00'), new \DateTime('2018-01-01 13:00:00'));
        $actualParking = $tariff->execute($parking);
        $this->assertEquals([25], $actualParking->getTariffParts());
        $this->assertEquals($actualParking->getEndDate(), $actualParking->getCurrent());
    }

    public function testComplexMaxFee()
    {
        $tariff = new HourlyTariffRule(25);
        $tariff->addRulePart(0, 0, 0, 9, 0);
        $tariff->addRulePart(10, 9, 0, 18, 0, 1);
        $tariff->addRulePart(5, 9, 0, 18, 0);
        $parking = new Parking(new \DateTime('2018-01-01 08:00:00'), new \DateTime('2018-01-01 15:00:00'));
        $actualParking = $tariff->execute($parking);
        $this->assertEquals([25], $actualParking->getTariffParts());
        $this->assertEquals($actualParking->getEndDate(), $actualParking->getCurrent());
    }
}
