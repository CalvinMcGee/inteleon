<?php

require '../vendor/autoload.php';

use Inteleon\SmsPark\ParkingFee;
use Inteleon\SmsPark\ParkingTariff;
use Inteleon\SmsPark\TariffRules\HourlyTariffRule;

/**
 * Parking tariff for Storgatan
 */
$storgatan = new ParkingTariff();

// Free parking between 00:00 and 09:00
$storgatan->addTariffRule(new HourlyTariffRule(0, 0, 0, 9, 0));

// First hour between 09:00 and 18:00 should be 10 SEK/h
$storgatan->addTariffRule(new HourlyTariffRule(10, 9, 0, 18, 0, 1));

// Parking between 09:00 and 18:00 after first hour should be 5 SEK/h
$storgatan->addTariffRule(new HourlyTariffRule(5, 9, 0, 18, 0, 0, 25));

// Free parking between 18:00 and 24:00
$storgatan->addTariffRule(new HourlyTariffRule(0, 18, 0, 24, 0));

/**
 * Parking A
 */

// Parking between 2018-01-01 10:00:00 and 12:00:00
$parkingA = new ParkingFee($storgatan, 1514800800, 1514808000);

// Parking fee should be 10 SEK for first hour and 5 SEK for second hour
echo $parkingA->getTotalFee() . PHP_EOL;

/**
 * Parking B
 */

// Parking between 2018-01-01 10:00:00 and 2018-01-02 22:00:00
$parkingB = new ParkingFee($storgatan, 1514800800, 1514930400);

// Parking fee should be 25 SEK for the first day and 25 SEK for the second day
echo $parkingB->getTotalFee() . PHP_EOL;