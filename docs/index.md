---
layout: default
title: "holidayChecker"
permalink: /
---

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

Currently the holidays for these countries are available:

![Map of the world](world.svg)

* Germany (all Bundesländer, german)
* Luxemburg (german, french and luxembourgisch)
* Belgium (flamisch and french)
* Netherlands (dutch)
* France (Mainland and overseas, adaptions for Elsass/Lothringen, french)
* United Kingdom (Islands, Wales, Scotland, Northern Ireland and England, englisch)
* Finland
* Russia
* Greece
* Turkey
* South Africa (english)
* Ireland (english and irish)
* Spain (all provinces, spanish)
* Portugal (mainland, Madeira and Azores, portuguese)
* Denmark (danish)
* Sweden (swedish)
* Norway (bokmål)
* Poland (polish)
* Austria (german)
* Italy (italian)
* Canada (french and english)
* United States (Federal holidays only - english)
* Brazil (brazilian portuguese)
* Japan (needs better way to handle Equinoxes)
* Chinese (Needs better way to handle solar terms)
* Afghanistan (english)
* Albania (Albanian)
* Algeria (English)
* Andorra (all parishes)
* Angola
* Antigua and Barbuda
* Argentina
* Armenia

But the list is constantly extending.

## Extending

Currently not all countries holidays are available. We are trying to fix that
but you might find that exactly the country you need is missing.

As the holidays are retrieved from XML-files you can add your own ones without
issue. They need to correspond to the [Schema-file](https://www.heigl.org/xml/xsd/holidays.xsd)
and before the schema is checked any XInclude-statements are executed. For more
information on that have a look at the [more detailed description](extend_xml)

You can then load the holidays from your file using the ```createIteratorFromXmlFile```-method.

If you think the XML-files might be usefull for others you should think about
contributing back and open a PullRequest here or attach them to an issue you open.

We'd be very thankfull!
