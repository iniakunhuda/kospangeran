<?php

namespace App\Helpers;

use Carbon\Carbon;
use DateTime;

class CarbonDateHelper
{
    static function formatTimestampToMongodate($timestamp = null, $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        return new \MongoDB\BSON\UTCDateTime($timestamp);
    }

    // $date = 2025-04-25
    static function formatDateStringToMongodate($date, $format = null)
    {
        return new \MongoDB\BSON\UTCDateTime(strtotime($date) * 1000);
    }

    static function today($format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        return Carbon::today()->format($format);
    }

    static function tomorrow($format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        return Carbon::tomorrow()->format($format);
    }

    static function yesterday($format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        return Carbon::yesterday()->format($format);
    }

    static function nextDay($datetime = null, $day, $format = null)
    {
        $day = strtoupper($day);
        $format = $format ? $format : 'Y-m-d H:i:s';
        $datetime = $datetime ? $datetime : Carbon::now();
        $days = ['SUNDAY' => Carbon::SUNDAY, 'MONDAY' => Carbon::MONDAY, 'TUESDAY' => Carbon::TUESDAY, 'WEDNESDAY' => Carbon::WEDNESDAY, 'THURSDAY' => Carbon::THURSDAY, 'FRIDAY' => Carbon::FRIDAY, 'SATURDAY' => Carbon::SATURDAY];
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->next($days[$day])->format($format);
    }

    static function dayOfWeek($datetime = null)
    {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $datetime = $datetime ? $datetime : Carbon::now();
        return $days[Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->dayOfWeek];
    }

    static function ukDate($datetime = null, $timestamp = false)
    {
        $datetime = $datetime ? $datetime : Carbon::now();
        $timestamp = $timestamp ? 'd/m/Y H:ia' : 'd/m/Y';
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->format($timestamp);
    }

    static function ukDateToDate($datetime = null, $timestamp = false)
    {
        $datetime = $datetime ? $datetime : Carbon::now();
        $format = $timestamp ? 'd/m/Y H:i:s' : 'd/m/Y';
        $timestamp = $timestamp ? 'Y-m-d H:i:s' : 'Y-m-d';
        return Carbon::createFromFormat($format, $datetime)->format($timestamp);
    }

    static function humanDate($datetime)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->diffForHumans();
    }

    static function age($datetime)
    {
        return Carbon::createFromFormat('Y-m-d', $datetime)->age;
    }

    static function weekend($datetime = null)
    {
        $datetime = $datetime ? $datetime : Carbon::now();
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->isWeekend();
    }

    static function diffInDays($datetime)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->diffInDays();
    }

    static function addYears($datetime = null, $years, $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        $datetime = $datetime ? $datetime : Carbon::now();
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->addYears($years)->format($format);
    }

    static function addMonths($datetime = null, $months, $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        $datetime = $datetime ? $datetime : Carbon::now();
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->addMonths($months)->format($format);
    }

    static function addWeeks($datetime = null, $weeks, $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        $datetime = $datetime ? $datetime : Carbon::now();
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->addWeeks($weeks)->format($format);
    }

    static function addDays($datetime = null, $days, $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        $datetime = $datetime ? $datetime : Carbon::now();
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->addDays($days)->format($format);
    }

    static function startOfDay($datetime = null, $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        $datetime = $datetime ? $datetime : Carbon::now();
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->startOfDay()->format($format);
    }

    static function endOfDay($datetime = null, $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        $datetime = $datetime ? $datetime : Carbon::now();
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->endOfDay()->format($format);
    }

    static function startOfWeek($datetime = null, $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        $datetime = $datetime ? $datetime : Carbon::now();
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->startOfWeek()->format($format);
    }

    static function endOfWeek($datetime = null, $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        $datetime = $datetime ? $datetime : Carbon::now();
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->endOfWeek()->format($format);
    }

    static function startOfMonth($datetime = null, $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        $datetime = $datetime ? $datetime : Carbon::now();
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->startOfMonth()->format($format);
    }

    static function endOfMonth($datetime = null, $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        $datetime = $datetime ? $datetime : Carbon::now();
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->endOfMonth()->format($format);
    }

    static function startOfYear($datetime = null, $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        $datetime = $datetime ? $datetime : Carbon::now();
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->startOfYear()->format($format);
    }

    static function endOfYear($datetime = null, $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        $datetime = $datetime ? $datetime : Carbon::now();
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->endOfYear()->format($format);
    }

    static function startOfDecade($datetime = null, $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        $datetime = $datetime ? $datetime : Carbon::now();
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->startOfDecade()->format($format);
    }

    static function endOfDecade($datetime = null, $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        $datetime = $datetime ? $datetime : Carbon::now();
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->endOfDecade()->format($format);
    }

    static function startOfCentury($datetime = null, $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        $datetime = $datetime ? $datetime : Carbon::now();
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->startOfCentury()->format($format);
    }

    static function endOfCentury($datetime = null, $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        $datetime = $datetime ? $datetime : Carbon::now();
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->endOfCentury()->format($format);
    }
}
