<?php

namespace ICBC\B2BPay\Contracts;

class Container
{
    private static $building = [];
	
    public static function bind($abstract, $concrete = null, $shared = false)
    {
        if (is_null($concrete))
		{
            $concrete = $abstract;
        }
		
        if (!$concrete instanceOf \Closure)
		{
            $concrete = self::getClosure($abstract, $concrete);
        }

        self::building[$abstract] = compact("concrete", "shared");
    }
	
    public static function singleton($abstract, $concrete, $shared = true)
	{
        self::bind($abstract, $concrete, $shared);
    }
	
    private static function getClosure($abstract, $concrete)
    {
        return function($c) use($abstract, $concrete)
		{
            $method = ($abstract == $concrete) ? 'build' : 'make';

            return $c::$method($concrete);
        };
    }
	
    public static function make($abstract)
    {
        $concrete = self::getConcrete($abstract);

        if (self::isBuildable($concrete, $abstract))
		{
            $object = self::build($concrete);
        }
		else
		{
            $object = self::make($concrete);
        }

        return $object;
    }
	
    private static function getConcrete($abstract)
    {
        if (!isset(self::building[$abstract]))
		{
            return $abstract;
        }

        return self::building[$abstract]['concrete'];
    }
	
    private static function isBuildable($concrete, $abstract)
    {
        return $concrete === $abstract || $concrete instanceof \Closure;
    }
	
    private static function build($concrete)
    {
        if ($concrete instanceof \Closure)
		{
            return $concrete($this);
        }
		
        $reflector = new \ReflectionClass($concrete);

        if (!$reflector->isInstantiable())
		{
            throw new \Exception('无法实例化');
        }

        $constructor = $reflector->getConstructor();
        if (is_null($constructor))
		{
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();
        $instance = self::getDependencies($dependencies);

        return $reflector->newInstanceArgs($instance);
    }
	
    private static function getDependencies(array $dependencies)
    {
        $results = [];
        foreach ($dependencies as $dependency)
		{
            $results[] = is_null($dependency->getClass())
                ? self::resolvedNonClass($dependency)
                : self::resolvedClass($dependency);
        }

        return $results;
    }
	
    private static function resolvedNonClass(\ReflectionParameter $parameter)
    {
        if ($parameter->isDefaultValueAvailable())
		{
            return $parameter->getDefaultValue();
        }
        throw new \Exception('resolve出错');
    }
	
    private static function resolvedClass(\ReflectionParameter $parameter)
    {
        return self::make($parameter->getClass()->name);
    }
}

?>
