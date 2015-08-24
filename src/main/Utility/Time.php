<?php
/*
 * Copyright (c) Shingo OKAWA <shingo.okawa.n.a@gmail.com>
 */
namespace Illuminate\YetAnother\Utility;

use Carbon\Carbon;
use \DateTime;
use \Exception;

/**
 * [Yet Another Implementation]
 * Provides useful functions to manipulate time-relating objects.
 *
 * @author Shingo OKAWA
 */
class Time
{
    /**
     * Returns Time-To-Live in seconds.
     *
     * @param  int|DateTime $minutes the duration of lifetime.
     * @param  int          $now     the user defined current time.
     * @return int          the caluculated ttl value.
     */
    public static function getTTL($minutes, $now=null)
    {
        if ($minutes instanceof DateTime) {
            $from = - Carbon::instance($minutes)->diffInSeconds($now, false);
            if ($from < 0) {
                //TODO: Implement appropriate exception.
                throw new Exception('invalid time specified.');
            }
            return $from;
        }
        return $minutes * 60;
    }
}