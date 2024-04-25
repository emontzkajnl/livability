<?php
namespace sgpbscheduling;

class AdminHelper
{
    public static function getWpTimezone()
    {
        $timezoneString = get_option('timezone_string');

        if (!empty($timezoneString)) {
            return $timezoneString;
        }

        $offset  = get_option('gmt_offset');
        $hours   = (int)$offset;
        $minutes = ($offset - floor($offset))*60;
        $offset  = sprintf('%+03d:%02d', $hours, $minutes);

        return $offset;
    }
}
