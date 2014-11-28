<?php
/*
 * A class containing functions for each dynamic widget
 *
*/
class MugoESIWidgets
{
    /*
     * Return a single array containing all of the data you need as array elements
     * You can set the TTL with an element 'ttl' and a value in seconds
    */
    public static function timestampExampleAction()
    {
        return array( 'timestamp' => time() );
    }
}