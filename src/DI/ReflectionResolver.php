<?php declare(strict_types=1);

namespace Y\DI;

trait ReflectionResolver
{
    /**
     * If there are no parameters in the dependencies or default parameters are set,
     * the class method will resolve these dependencies.
     *
     * @param string $class
     *
     * @return object
     * @throws \ReflectionException
     */
    private function reflectionResolve(string $class)
    {
        $reflector = new \ReflectionClass($class);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class {$class} is not instantiable.");
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return $reflector->newInstance();
        }

        $dependencies = $this->getDependencies($constructor->getParameters());

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * Get all dependencies resolved.
     *
     * @param array $params
     *
     * @return array
     * @throws \Exception
     */
    private function getDependencies(array $params): array
    {
        $dependencies = [];

        foreach ($params as &$param) {
            $dependency = $param->getClass();

            if (!is_null($dependency)) {
                $dependencies[] = $this->reflectionResolve($dependency->name);
                continue;
            }

            if ($param->isDefaultValueAvailable()) {
                $dependencies[] = $param->getDefaultValue();
                continue;
            }

            throw new \Exception("Can not resolve class dependency {$param->name}.");
        }

        return $dependencies;
    }
}