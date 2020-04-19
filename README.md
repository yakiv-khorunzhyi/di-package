
## Installation
Install the latest version with:
```
$ composer require yakiv-khorunzhyi/di-container
```

## Examples
```
$container = new Y\DI\Container();
```
Simple lazy load:
```
$container->bind('test', function () {
    return new SomeClass(new A(new B(1, 'str')));
});
```
Adding an instance of an object:
```
$container->add('test', function () {
    return new B(1, 'str');
});
```
You can check if the object is registered or added to the container:
```
$container->has('test');
```
Get dependency:
```
$container->get('test');
```
Using this entry automatically resolves the dependencies:
```
$container->get(SomeClass::class);
```