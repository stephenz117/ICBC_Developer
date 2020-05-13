<?php

namespace ICBC\B2BPay\Contracts;

class Container
{
    public $building = [];
	
    public function bind($abstract, $concrete = null, $shared = false)
    {
        if (is_null($concrete))
		{
            $concrete = $abstract;
        }

        if (!$concrete instanceOf Closure)
		{
            $concrete = $this->getClosure($abstract, $concrete);
        }

        $this->building[$abstract] = compact("concrete", "shared");
    }
	
    public function singleton($abstract, $concrete, $shared = true)
	{
        $this->bind($abstract, $concrete, $shared);
    }
	
    public function getClosure($abstract, $concrete)
    {
        return function($c) use($abstract, $concrete)
		{
            $method = ($abstract == $concrete) ? 'build' : 'make';

            return $c->$method($concrete);
        };
    }
	
    public function make($abstract)
    {
        $concrete = $this->getConcrete($abstract);

        if ($this->isBuildable($concrete, $abstract))
		{
            $object = $this->build($concrete);
        }
		else
		{
            $object = $this->make($concrete);
        }

        return $object;
    }
	
    public function getConcrete($abstract)
    {
        if (!isset($this->building[$abstract]))
		{
            return $abstract;
        }

        return $this->building[$abstract]['concrete'];
    }
	
    public function isBuildable($concrete, $abstract)
    {
        return $concrete === $abstract || $concrete instanceof Closure;
    }
	
    public function build($concrete)
    {
        if ($concrete instanceof Closure)
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
        $instance = $this->getDependencies($dependencies);

        return $reflector->newInstanceArgs($instance);
    }
	
    public function getDependencies(array $dependencies)
    {
        $results = [];
        foreach ($dependencies as $dependency)
		{
            $results[] = is_null($dependency->getClass())
                ? $this->resolvedNonClass($dependency)
                : $this->resolvedClass($dependency);
        }

        return $results;
    }
	
    public function resolvedNonClass(ReflectionParameter $parameter)
    {
        if ($parameter->isDefaultValueAvailable())
		{
            return $parameter->getDefaultValue();
        }
        throw new \Exception('出错');
    }
	
    public function resolvedClass(ReflectionParameter $parameter)
    {
        return $this->make($parameter->getClass()->name);
    }
}

?>