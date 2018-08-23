<?php

require '../vendor/autoload.php';

use Inteleon\SmsPark\ParkingFee;
use Inteleon\SmsPark\ParkingTariff;
use Inteleon\SmsPark\TariffRules\HourlyTariffRule;

/**
 * Parking tariff for Storgatan
 */
$storgatan = new ParkingTariff();

// Max fee per day is 25 SEK
$hourlyTariff = new HourlyTariffRule(25);

// Free parking between 00:00 and 09:00
$hourlyTariff->addRulePart(0, 0, 0, 9, 0);

// First hour between 09:00 and 18:00 should be 10 SEK/h
$hourlyTariff->addRulePart(10, 9, 0, 18, 0, 1);

// Parking between 09:00 and 18:00 after first hour should be 5 SEK/h
$hourlyTariff->addRulePart(5, 9, 0, 18, 0);

// Free parking between 18:00 and 24:00
$hourlyTariff->addRulePart(0, 18, 0, 24, 0);

$storgatan->addTariffRule($hourlyTariff);

/**
 * Parking A
 */

// Parking between 2018-01-01 10:00:00 and 12:00:00
$parkingA = new ParkingFee($storgatan, 1514800800, 1514808000);

// Parking fee should be 10 SEK for first hour and 5 SEK for second hour
$parkingFeeA = $parkingA->getTotalFee();
echo 'Parking between' . PHP_EOL;
echo $parkingA->getParking()->getStartDate()->format('Y-m-d H:i:s') . ' - ' . $parkingA->getParking()->getEndDate()->format('Y-m-d H:i:s') . PHP_EOL;
echo $parkingFeeA . ' SEK' . PHP_EOL;

/**
 * Parking B
 */

// Parking between 2018-01-01 10:00:00 and 2018-01-02 22:00:00
$parkingB = new ParkingFee($storgatan, 1514800800, 1514930400);

// Parking fee should be 25 SEK for the first day and 25 SEK for the second day
$parkingFeeB = $parkingB->getTotalFee();
echo 'Parking between' . PHP_EOL;
echo $parkingFeeB . ' SEK' . PHP_EOL;
echo $parkingB->getParking()->getStartDate()->format('Y-m-d H:i:s') . ' - ' . $parkingB->getParking()->getEndDate()->format('Y-m-d H:i:s') . PHP_EOL;