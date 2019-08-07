<?php

namespace Drupal\ad_events\Services;


class ProcessDate
{

  public function CalculateDayDifference($RawEventDate)
  {

    $CurrentDate = strtotime(date('d.m.Y'));
    $EventDate = strtotime(date('d.m.Y', strtotime($RawEventDate))); // eliminate time errors

    $DaysBetween = round(($EventDate - $CurrentDate) / 86400);

    return (string)$DaysBetween;

  }

}
