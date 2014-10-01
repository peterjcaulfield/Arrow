<?php

namespace Arrow;

abstract class Proxy
{
    protected static $app;

    protected static $resolvedInstance;

    public function getProxyAccessor();

    public static function setProxiedApplication($app)
    {
        static::$app = $app;
    }

    public static function swap($instance)
    {
        static::$resolvedInstance[static::getProxyAccessor()] = $instance;

        static::$app[static::getProxyAccessor()] = $instance;
    }

    public function getProxiedObject()
    {
        return static::resolveProxyInstance(static::getProxyAccessor());
    }

    protected static function resolveProxyInstance($name)
    {
        if (isset(static::$resolvedInstance[$name])) {
            return static::$resolvedInstance[$name];
        }

        return static $resolvedInstance[$name] = static::$app[$name];
    }

    public function __callStatic($method, $args)
    {
        $instance = static::getProxiedObject();

        if (!count($args)) return $instance->method();

        return call_user_func_array(array($instance, $method), $args);
    }
}
