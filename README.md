# sms-park

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what
PSRs you support to avoid any confusion with users and contributors.

## Structure

```
app/
bin/
src/
tests/
vendor/
```

## Install

Via Composer

``` bash
$ composer require inteleon/sms-park
```

## Usage

``` php
$tariff = new Inteleon\SmsPark\ParkingTariff();
$hourlyTariff = new Inteleon\SmsPark\TariffRules\HourlyTariffRule();

// Parking between 09:00 and 18:00 id 5 SEK/h
$hourlyTariff->addRulePart(5, 9, 0, 18, 0);

$tariff->addTariffRule($hourlyTariff);

// Parking between 2018-01-01 10:00:00 and 12:00:00
$parking = new Inteleon\SmsPark\ParkingFee($tariff, 1514800800, 1514808000);

echo $parking->getTotalFee() . PHP_EOL;
```

## Testing

``` bash
$ composer test
```

## Credits

- [Joachim Wallsin][link-author]
- [All Contributors][link-contributors]
