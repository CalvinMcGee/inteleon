<?php

namespace Inteleon\SmsPark\Test\TariffRules;

use PHPUnit\Framework\TestCase;
use Inteleon\SmsPark\Parking;
use Inteleon\SmsPark\TariffRules\HourlyTariffRule;

class HourlyTariffRuleTest extends TestCase
{
    /** @var HourlyTariffRule */
    private $hourlyTariffRule;

    /**
     * Set up test
     */
    public function setUp()
    {
        $this->hourlyTariffRule = new HourlyTariffRule(5, 9, 0, 18, 0, 25);
        
    }

    /**
     * Test calculate fee
     */
    public function testExecuteFee()
    {
        $parking = new Parking(new \DateTime('2018-01-01 12:00:00'), new \DateTime('2018-01-01 16:00:00'));
        $actualParking = $this->hourlyTariffRule->execute($parking);
        $this->assertEquals([20], $actualParking->getTariffParts());
        $this->assertEquals($actualParking->getEndDate(), $actualParking->getCurrent());
    }

    /**
     * Test skipped TariffRule
     *
     * Test that when starting of parking is before when tariff begins, the rule
     * will be skipped.
     */
    public function testExecuteSkipped()
    {
        $parking = new Parking(new \DateTime('2018-01-01 06:00:00'), new \DateTime('2018-01-01 16:00:00'));
        $actualParking = $this->hourlyTariffRule->execute($parking);
        $this->assertEquals([], $actualParking->getTariffParts());
        $this->assertEquals($actualParking->getStartDate(), $actualParking->getCurrent());
    }

    /**
     * Test longer parking
     *
     * Test that when the parking is longer than when tariff ends, we will not
     * calculate that time.
     */
    public function testExecuteLonger()
    {
        $parking = new Parking(new \DateTime('2018-01-01 17:15:00'), new \DateTime('2018-01-01 19:00:00'));
        $actualParking = $this->hourlyTariffRule->execute($parking);
        $this->assertEquals([3.75], $actualParking->getTariffParts());
        $this->assertEquals((new \DateTime('2018-01-01 18:00:00')), $actualParking->getCurrent());
    }

    /**
     * Test longer parking
     *
     * Test that we cannot receive more than maximum fee
     */
    public function testExecuteMaxFee()
    {
        $parking = new Parking(new \DateTime('2018-01-01 09:00:00'), new \DateTime('2018-01-01 15:00:00'));
        $actualParking = $this->hourlyTariffRule->execute($parking);
        $this->assertEquals([25], $actualParking->getTariffParts());
        $this->assertEquals($parking->getEndDate(), $actualParking->getCurrent());
    }
}
