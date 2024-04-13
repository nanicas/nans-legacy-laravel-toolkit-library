<?php

namespace Zevitagem\LaravelToolkit\Staters;

abstract class BaseStater
{
    protected static $instance = null;
    protected static $data     = array();

    /** protected to prevent cloning */
    protected function __clone()
    {

    }

    /** protected to prevent instantiation from outside of the class */
    protected function __construct()
    {

    }
    
    /** call this method to get instance */
    public static function instance(array $data)
    {
        if (static::$instance === null) {
            static::$instance = new static();
            static::$instance->setData($data);
        }

        return static::$instance;
    }

    public static function setItem(string $key, $value)
    {
        static::$data[$key] = $value;
    }

    public static function setData(array $data)
    {
        static::$data = $data;
    }

    public static function all()
    {
        return static::$data;
    }

    public static function exists(string $key)
    {
        return (isset(static::$data[$key]));
    }

    public static function getItem(string $key)
    {
        return static::$data[$key] ?? null;
    }
}