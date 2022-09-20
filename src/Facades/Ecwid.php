<?php

namespace subcom\Ecwid\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \subcom\Ecwid\Ecwid
 */
class Ecwid extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ecwid';
    }
}
