<?php

if (! function_exists('mongo')) {
    /**
     * Establishes MongoDB connection.
     *
     * @param  string  $db
     * @return \Database
     */
    function mongo($db = 'chess')
    {
        return app('mongo')->$db;
    }
}
