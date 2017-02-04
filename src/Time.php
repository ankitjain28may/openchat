<?php

namespace ChatApp;

/**
* For the conversion of time
*/
class Time
{

    public function __construct()
    {
        # code...
    }

    public function timeConversion($time)
    {

        if (substr($time, 4, 11) == date("d M Y", time() + 16200)) {
            $time = substr($time, 16, 5);
        } else if (substr($time, 7, 8) == date("M Y", time() + 16200) && substr($time, 4, 2) - date("d") < 7) {
            $time = substr($time, 0, 3);
        } else if (substr($time, 11, 4) == date("Y", time() + 16200)) {
            $time = substr($time, 4, 6);
        } else {
            $time = substr($time, 4, 11);
        }

        return $time;
    }
}