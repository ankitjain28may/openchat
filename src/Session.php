<?php
/**
 * Session Class Doc Comment
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

@session_start();

/**
 * Session Management
 *
 * @category PHP
 * @package  OpenChat
 * @author   Ankit Jain <ankitjain28may77@gmail.com>
 * @license  The MIT License (MIT)
 * @link     https://github.com/ankitjain28may/openchat
 */
class Session
{
    /*
    |--------------------------------------------------------------------------
    | Session Class
    |--------------------------------------------------------------------------
    |
    | For Fetching the sidebar results.
    |
    */

    /**
     * Create Key-Value pain in session storage
     *
     * @param int $key   To store key for the value
     * @param int $value To store value for the corresponding key
     *
     * @return void
     */
    public static function put($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get Value from the session storage
     *
     * @param int $key To store key for the value
     *
     * @return string or null
     */
    public static function get($key)
    {
        return (isset($_SESSION[$key]) ? $_SESSION[$key] : null);
    }

    /**
     * Destroy key-value pair from the session storage
     *
     * @param int $key To store key for the value
     *
     * @return void
     */
    public static function forget($key)
    {
        unset($_SESSION[$key]);
    }

}
