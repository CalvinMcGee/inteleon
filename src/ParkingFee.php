<?php

namespace Inteleon\SmsPark;

use Inteleon\SmsPark\Parking;
use Inteleon\SmsPark\ParkingTariff;

class ParkingFee
{
    /** @var Parking */
    private $parking;

    /** @var ParkingTariff */
    private $parkingTariff;

    public function __construct(ParkingTariff $parkingTariff, $startTime = 0, $endTime = 0)
    {
        $this->parkingTariff = $parkingTariff;
        $this->parking = new Parking(\DateTime::createFromFormat('c', $startDateTime), \DateTime::createFromFormat('c', $endDateTime));
    }
}
