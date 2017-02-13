<?php
/**
 * Time Class Doc Comment
 *
 * PHP version 5
 *
 * @category PHP
 * @package  OpenChat
 * @author   Ankit Jain <ankitjain28may77@gmail.com>
 * @license  The MIT License (MIT)
 * @link     https://github.com/ankitjain28may/openchat
 */
namespace ChatApp;

/**
 * For the conversion of time
 *
 * @category PHP
 * @package  OpenChat
 * @author   Ankit Jain <ankitjain28may77@gmail.com>
 * @license  The MIT License (MIT)
 * @link     https://github.com/ankitjain28may/openchat
 */
class Time
{
    /*
    |--------------------------------------------------------------------------
    | Time Class
    |--------------------------------------------------------------------------
    |
    | For the conversion of time.
    |
    */

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        // code...
    }

    /**
     * Convert Time according to the time passes
     *
     * @param string $time To store time
     *
     * @return string $time
     */
    public function timeConversion($time)
    {

        if (substr($time, 4, 11) == date("d M Y", time() + 16200)) {
            $time = substr($time, 16, 5);
        } else if (substr($time, 7, 8) == date("M Y", time() + 16200)
            && substr($time, 4, 2) - date("d") < 7
        ) {
            $time = substr($time, 0, 3);
        } else if (substr($time, 11, 4) == date("Y", time() + 16200)) {
            $time = substr($time, 4, 6);
        } else {
            $time = substr($time, 4, 11);
        }

        return $time;
    }
}
