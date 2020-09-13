<?php

declare(strict_types=1);

namespace tests;

use tests\TestClasses\A;
use tests\TestClasses\B;
use tests\TestClasses\SomeClass;

class ContainerTest extends \PHPUnit\Framework\TestCase
{
    /** @var \Y\DI\Container */
    protected static $container;

    public static function setUpBeforeClass(): void
    {
        self::$container = new \Y\DI\Container();
    }

    public function testHasDependency()
    {
        $this->assertFalse(self::$container->has(SomeClass::class));

        self::$container->bind('SomeClass', function () {
            return new SomeClass(new A(new B(1, 'str')));
        });

        $this->assertTrue(self::$container->has('SomeClass'));
        self::$container->clear();
        $this->assertFalse(self::$container->has('SomeClass'));

        self::$container->add('SomeClass', function () {
            return new SomeClass(new A(new B(1, 'str')));
        });

        $this->assertTrue(self::$container->has('SomeClass'));
        self::$container->clear();
    }

    public function testBindAndUnbindDependency()
    {
        self::$container->bind('A', function () {
            return new SomeClass(new A(new B(1, 'str')));
        });

        $containerParams = $this->getContainerProperties(\Y\DI\Container::class, 'params');

        $this->assertArrayHasKey('A', $containerParams);
        $this->assertInstanceOf(\Closure::class, $containerParams['A']);

        self::$container->unbind('A');

        $containerParams = $this->getContainerProperties(\Y\DI\Container::class, 'params');
        $this->assertArrayNotHasKey('A', $containerParams);

        self::$container->clear();
    }

    public function testGetDependency()
    {
        // getting dependency using reflection
        $someClass = self::$container->get(SomeClass::class);

        $this->assertInstanceOf(SomeClass::class, $someClass);
        $this->assertEmpty($this->getContainerProperties(\Y\DI\Container::class, 'params'));

        $instances = $this->getContainerProperties(\Y\DI\Container::class, 'instances');
        $this->assertArrayHasKey(SomeClass::class, $instances);

        // getting dependency with preliminary binding
        self::$container->bind('A', function () {
            return new SomeClass(new A(new B(1, 'str')));
        });

        $classA = self::$container->get('A');
        $this->assertInstanceOf(SomeClass::class, $classA);

        $params = $this->getContainerProperties(\Y\DI\Container::class, 'params');
        $this->assertEmpty($params);

        $instances = $this->getContainerProperties(\Y\DI\Container::class, 'instances');
        $this->assertArrayHasKey('A', $instances);

        $classA    = self::$container->get('A');
        $instances = $this->getContainerProperties(\Y\DI\Container::class, 'instances');

        $this->assertSame(2, sizeof($instances));

        // receiving dependency with preliminary addition
        self::$container->add('B', function () {
            return new SomeClass(new A(new B(1, 'str')));
        });

        $instances = $this->getContainerProperties(\Y\DI\Container::class, 'instances');

        $this->assertSame(3, sizeof($instances));
        $this->arrayHasKey('B', $instances);

        $params = $this->getContainerProperties(\Y\DI\Container::class, 'params');
        $this->assertEmpty($params);

        self::$container->clear();
    }

    public function testAddDependencyToContainer()
    {
        self::$container->add('SomeClass', function () {
            return new SomeClass(new A(new B(1, '3')));
        });

        $instances = $this->getContainerProperties(\Y\DI\Container::class, 'instances');

        $this->assertSame(1, sizeof($instances));
        $this->assertArrayHasKey('SomeClass', $instances);

        self::$container->clear();
    }

    public function testRemoveDependencyFromContainer()
    {
        self::$container->add('SomeClass', function () {
            return new SomeClass(new A(new B(1, '3')));
        });

        self::$container->remove('SomeClass');

        $instances = $this->getContainerProperties(\Y\DI\Container::class, 'instances');

        $this->assertEmpty($instances);
        $this->assertArrayNotHasKey('SomeClass', $instances);

        self::$container->clear();
    }

    public function testClearContainer()
    {
        self::$container->add('SomeClass', function () {
            return new SomeClass(new A(new B(1, '3')));
        });

        self::$container->bind('B', function () {
            return new SomeClass(new A(new B(1, '3')));
        });

        self::$container->clear();

        $instances = $this->getContainerProperties(\Y\DI\Container::class, 'instances');
        $params    = $this->getContainerProperties(\Y\DI\Container::class, 'params');

        $this->assertEmpty($instances);
        $this->assertEmpty($params);
    }

    public static function tearDownAfterClass(): void
    {
        self::$container = null;
    }

    /* ---===### HELPERS ###===--- */

    protected function getContainerProperties($className, $property)
    {
        $params = new \ReflectionProperty($className, $property);
        $params->setAccessible(true);

        return $params->getValue(self::$container);
    }
}