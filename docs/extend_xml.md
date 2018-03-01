---
layout: default
title: "Extend XML-Files"
permalink: /extend_xml
---

# How to create a new Holiday-File

Holiday files are XML-Files that validate against the 
[holiday.xsd Schema-File](https://www.heigl.org/xml/xsd/holidays.xsd)

That means they need at least the following code:

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<holidays xmlns="https://www.heigl.org"
          xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
          xsi:schemaLocation="https://www.heigl.org/xml/xsd/holidays.xsd holidays.xsd"
          xmlns:xi="http://www.w3.org/2001/XInclude">
</holidays>
```

Within the ```holidays```-tag a list of tags defining the different days is 
expected. The following tags can be used for that:

# Tags defining a holiday

All tags have some attributes in common. These are

* **free** This can be either ```true``` or ```false```. That defines whether 
the government decided that the day is a free day or not. This field is mandatory!
* **comment** This can be any string you like and should describe the day in 
english language. This attribute can be ommited.

The Text-Value of the tag is the name of the day that will be reported back. 
The name is mandatory! 

## Date

 The ```date```-tag is probably the most obvious one. It defines a day by it's 
 day within the month as well as a month. Both values have to be numeric.
 
 So the minimal version of a ```date```-tag would be
 
 ```xml
 <date day="25" month="12" free="true">
     Christmas
 </date>
 ```

 That describes that the 25th of december is a free day by governmental
 decision. The name of the day will be reported as "Christmas".

 But…

 wait a minute…

 What about non-christian days? What about for example
 jewish days like "Rosh Hashanah"? That'd be on the 1st of Tishrei
 (which is the first month in the hebrew calendar).

 We've thought of that and thanks to the work of the ICU we can use their different
 calendars within the ```date```-tag by specifying one of them in the
 ```calendar```-attribute. So the entry for "Rosh Hashanah" would look like this:

 ```xml
<date day="1" month="1" free="true" calendar="hebrew" comment="Rosh Hashanah">
    ראש השנה
</date>
```

Currently the buddhist, chinese, coptic, ethiopian, gregorian, hebrew, indian,
islamic, japanese and persian calendars are available.

Note though that the islamic calendar can be inaccurate due to the way the islam
defines the way the first day of a month is determined!

There is also the possibility to add a ```year```-tag to a date to mark a day
that is only a holiday once. We've had this in Germany in 2017 where the day to
commemorate the reformation by Matin Luther was a general holiday only in that year.

So the entry for that day looked like this

```xml
<date day="31" month="10" year="2017" free="true">
    Reformationstag
</date>
```

## DateFollowup

Some countries have the habit of being very friendly to their workers. They
decided that - should a holiday fall onto a weekend - they would make the
following monday a free day. That's where the ```datefollowup``` tag comes into
play. The given day is a free day, but should that day be on a saturday or sunday
the following day defined by the ```followup```-attribute would be a free day.

An example for St. Patricks day in Northern Ireland looks like this:

```xml
<datefollowup day="17" month="3" followup="monday">
    St. Patricks Day
</datefollowup>
```

DateFollowup also supports the calendar-attribute.

## Relative Dates

Sometimes dates are relative to a certain date like "The 4th sunday before the 25th
of december" (which would be the 1st Sunday of Advent). For such relative
structures there is the ```relative``` Tag. It takes anything the
[modify-method](http://php.net/manual/de/datetimeimmutable.modify.php) can handle
inside the ```relation```-attribute. The Referencing day is again given with the
```day``` and ```month```-attribute.

So the above example would look like this:

```xml
<relative day="25" month="12" free="false" relation="last sunday -3 weeks">
    1st of Advent
</relative>
```

Note the use of ```last sunday``` (would be the sunday before the given day and
therefore the 4th of advent) ```-3 weeks``` (would then be the 1st of advent)

## Special Relative Dates

There are some very special cases where dates are not calculated relative to a
fixed date but relative to a relative date whose calculation is a bit more complex.

### Dates relative to Easter

In the cristian world those are all the dates in relation to the easter date.

And then there are two ways to calculate the easter date which causes the odd
thing that western (protestant and catholic) and eastern (orthodox) christians
celebrate their easter with up to 6 weeks difference.

To take that into account there are the ```easter```- and ```easterorthodox```-tags
that can be used to calculate holydays in relation to the respective easter-date.

So an entry for Easter Sunday (which is one day before the easter date) would
look like this for greece

```xml
<easterorthodox offset="-1">
    Easter Sunday
</easterorthodox>
```

and like this for germany

```xml
<easter offset="-1">
    Easter Sunday
</easter>
```

## Note on Non-Gregorian calendars.

### Hebrew Calendar

* Months are counted after the civil calendar. So Tishrei is considered the first month!
* Adar I is *always* considered to be the sixth month regardless whether it's a leapyear or not.
  So Adar in non-leapyears is also the seventh month!

### Islamic calendar

* Months are counted beginning with Muḥarram.

> Currently there is an issue with the Islamic calendars in that it seems to be off
> by one day due to differences in the calculation of the underlying ICU-Implementation
> and some online calendars. But due to the way months are determined in the islamic
> calendar that can always be the case also on short notice. For more informations have
> a look at [Wikipedias entry on the Islamic Calendar](https://en.wikipedia.org/wiki/Islamic_calendar)
