<?php

namespace Arrow;

abstract class Proxy
{
    protected static $container;

    protected static $resolvedInstance;

    abstract public static function getProxyAccessor();

    public static function swap($instance)
    {
        static::$resolvedInstance[static::getProxyAccessor()] = $instance;

        static::$container[static::getProxyAccessor()] = $instance;
    }

    public static function getProxiedObject()
    {
        return static::resolveProxyInstance(static::getProxyAccessor());
    }

    protected static function resolveProxyInstance($name)
    {
        if (isset(static::$resolvedInstance[$name])) {
            return static::$resolvedInstance[$name];
        }

        return static::$resolvedInstance[$name] = static::$container[$name];
    }

    public static function clearResolvedInstances()
    {
        static::$resolvedInstance = array();
    }

    public static function setProxiedContainer($container)
    {
        static::$container = $container;
    }

    public static function getProxiedContainer()
    {
        return static::$container;
    }

    public static function __callStatic($method, $args)
    {
        $instance = static::getProxiedObject();

        if (!count($args)) return $instance->method();

        return call_user_func_array(array($instance, $method), $args);
    }
}
