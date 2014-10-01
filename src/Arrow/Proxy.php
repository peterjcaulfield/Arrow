<?php

namespace Arrow;

abstract class Proxy
{
    protected static $app;

    public static function setProxyApplication($app)
    {
        static::$app = $app;
    }
}
