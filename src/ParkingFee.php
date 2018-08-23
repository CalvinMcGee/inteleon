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

    /**
     * @param ParkingTariff $parkingTariff
     * @param int $startTime Parking start in unix timestamp
     * @param int $endTime Parking end in unix timestamp
     */
    public function __construct(ParkingTariff $parkingTariff, $startTime = 0, $endTime = 0)
    {
        $this->parkingTariff = $parkingTariff;
        $this->parking = new Parking(new \DateTime('@'.$startTime), new \DateTime('@'.$endTime));
    }

    /**
     * @return int|float
     */
    public function getTotalFee()
    {
        $this->parking = $this->generateParking($this->parkingTariff, $this->parking);
        return $this->calculateTotal($this->parking);
    }

    /**
     * @param ParkingTariff $parkingTariff
     * @param Parking $parking
     * @return Parking
     */
    private function generateParking(ParkingTariff $parkingTariff, Parking $parking)
    {
        $parkingTariffIterator = $parkingTariff->getIterator();

        while ($parking->getCurrent() < $parking->getEndDate()) {
            $parking = $parkingTariffIterator->current()->execute($parking);

            $parkingTariffIterator->next();

            if (!$parkingTariffIterator->valid()) {
                $parkingTariffIterator->rewind();
            }
        }

        return $parking;
    }

    /**
     * @param Parking $parking
     * @return int|float
     */
    private function calculateTotal(Parking $parking)
    {
        return array_sum($parking->getTariffParts());
    }
}
