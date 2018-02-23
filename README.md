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

You can also get a ```HolidayIterator``` with a 2-letter [ISO 3166-1](https://en.wikipedia.org/wiki/ISO_3166-1)
or a 4-letter [ISO 3166-2](https://en.wikipedia.org/wiki/ISO_3166-2) code. And when different language-variations are available you can get them 
by adding the [ISO 639-1 language-code](https://en.wikipedia.org/wiki/ISO_3166-2) before the ISO 3166-code:

```php
// Get the holidays for mainland france
$iterator = $factory->createIteratorFromIso3166('FR');

// Get the holidays for the french overseas-department La Reunion
$iterator = $factory->createIteratorFromIso3166('FR-RE');

// Get the dutch holidays for belgium
$iterator = $factory->createIteratorFromIso3166('fr_BE');
```

## Available Countries

Currently these countries are available:

* Germany (all Bundesländer, german)
* Luxemburg (german, french and luxembourgisch)
* Belgium (flamisch and french)
* Netherlands (dutch)
* France (Mainland and overseas, adaptions for Elsass/Lothringen, french)
* United Kingdom (Islands, Walse, Scotland, Northern Ireland and England, englisch)
* Greece
* Turkey

But the list is constantly extending.

## Extending

Currently not all countries holidays are available. We are trying to fix that 
but you might find that exactly the country you need is missing.

As the holidays are retrieved from XML-files you can add your own ones without 
issue. They need to correspond to the [Schema-file](https://github.com/heiglandreas/holidayChecker/blob/master/share/holidays.xsd) 
and before the schema is checked any XInclude-statements are executed. 

You can then load the holidays from your file using the ```createIteratorFromXmlFile```-method.

If you think the XML-files might be usefull for others you should think about 
contributing back and open a PullRequest here or attach them to an issue you open.

We'd be very thankfull!
