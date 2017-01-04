<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three LTD <support@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Enable Laravel Segment
    |--------------------------------------------------------------------------
    |
    | This option specifies if Segment tracking is enabled.
    |
    | Default: true
    |
    */

    'enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Segment Write Key
    |--------------------------------------------------------------------------
    |
    | This option specifies key which enables you to write to Segment's API.
    |
    | Default: true
    |
    */

    'write_key' => env('SEGMENT_KEY'),

];
