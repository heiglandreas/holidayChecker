[![Build Status](https://travis-ci.org/heiglandreas/holidayChecker.svg?branch=master)](https://travis-ci.org/heiglandreas/holidayChecker)
[![Coverage Status](https://coveralls.io/repos/github/heiglandreas/holidayChecker/badge.svg?branch=master)](https://coveralls.io/github/heiglandreas/holidayChecker?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/heiglandreas/holidayChecker/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/heiglandreas/holidayChecker/?branch=master)
[![Code Climate](https://lima.codeclimate.com/github/heiglandreas/holidayChecker/badges/gpa.svg)](https://lima.codeclimate.com/github/heiglandreas/holidayChecker)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/151d3ea7c58d4f4eb7dc7b2073781657)](https://www.codacy.com/app/github_70/holidayChecker?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=heiglandreas/holidayChecker&amp;utm_campaign=Badge_Grade)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/36a28cf5-b7e4-474d-bded-8f6b70fa9ea8/mini.png)](https://insight.sensiolabs.com/projects/36a28cf5-b7e4-474d-bded-8f6b70fa9ea8)

[![Latest Stable Version](https://poser.pugx.org/org_heigl/holidaychecker/v/stable)](https://packagist.org/packages/org_heigl/holidaychecker)
[![Total Downloads](https://poser.pugx.org/org_heigl/holidaychecker/downloads)](https://packagist.org/packages/org_heigl/holidaychecker)
[![License](https://poser.pugx.org/org_heigl/holidaychecker/license)](https://packagist.org/packages/org_heigl/holidaychecker)
[![composer.lock](https://poser.pugx.org/org_heigl/holidaychecker/composerlock)](https://packagist.org/packages/org_heigl/holidaychecker)


# holidayChecker

Check whether a given date is a holiday - locale-aware

This library allows you to check a single day against one or multiple calendars 
to see whether the given day is a holiday or not.

That also includes "named Days" that are not necessarily "free" but have a special 
name like "Maundy Thursday". 

## Installation

holidayChecker is best installed using [composer](https://getcomposer.org)

```bash
composer require org_heigl/holidaychecker
```

## Usage

Simple usage:

```php
$factory  = new HolidayIteratorFactory();
$iterator = $factory->createIteratorFromXmlFile('path/to/a/holiday/file.xml');
$checker  = new Holidaychecker($iterator);

$result = $checker->check(new \DateTime());
// $result will be an instance of Org_Heigl\HolidayChecker\Holiday
```

$result has 3 methods:

* **isHoliday** is ```true``` when the day is a free day according to the local law. Otherwise it's ```false```
* **isNamed** is ```true``` when the day has a special name despite being not a free day.
* **getName** contains the name of a named day. 
