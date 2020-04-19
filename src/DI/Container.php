<?php declare(strict_types=1);

namespace Y\DI;

/**
 * Class Container
 * @package DI
 */
class Container implements IContainer
{
    use ReflectionResolver;

    /**
     * @var array
     * @example [id => closure]
     */
    protected $params = [];

    /**
     * @var array
     * @example [id => object]
     */
    protected $instances = [];

    /**
     * Check if entity exists by id.
     *
     * @param $id
     *
     * @return bool
     */
    public function has($id): bool
    {
        return isset($this->instances[$id]) || isset($this->params[$id]);
    }

    /**
     * The method returns an instance of the desired class by id.
     * If there is no instance of the class, then it is created.
     *
     * @param $id
     *
     * @return object|null
     * @throws \ReflectionException
     */
    public function get($id)
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (isset($this->params[$id])) {
            $closure = $this->params[$id];
            $this->unbind($id);

            return ($this->instances[$id] = $closure());
        }

        return ($this->instances[$id] = $this->reflectionResolve($id));
    }

    /**
     * Associate a closure with an id in container.
     *
     * @param $id
     * @param \Closure $closure
     *
     * @return $this
     */
    public function bind($id, \Closure $closure): Container
    {
        $this->params[$id] = $closure;

        return $this;
    }

    /**
     * Unbind closure by id.
     *
     * @param $id
     *
     * @return $this
     */
    public function unbind($id): Container
    {
        unset($this->params[$id]);

        return $this;
    }

    /**
     * Add object to container.
     *
     * @param $id
     * @param \Closure $closure
     *
     * @return $this
     */
    public function add($id, \Closure $closure): Container
    {
        $this->instances[$id] = $closure();

        return $this;
    }

    /**
     * Remove object from container.
     *
     * @param $id
     *
     * @return $this
     */
    public function remove($id): Container
    {
        unset($this->instances[$id]);

        return $this;
    }

    /**
     * Clear all instances and associated closures.
     * @return $this
     */
    public function clear(): Container
    {
        $this->instances = [];
        $this->params = [];

        return $this;
    }
}