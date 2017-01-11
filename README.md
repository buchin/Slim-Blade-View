# Blade for Slim Framework 3

## Usage

In index.php set up the blade container:

```php
$container = $app->getContainer();
$blade = new \Slim\Views\Blade(
    '/path/to/views/folder',
    '/path/to/cache/folder'
);

// [optional] insert custom directive here

$container['blade'] = $blade;
```

Example code to render index.blade.php in route:

```php
return $this->blade->render($response, 'index', $args);
```

### Advanced: Custom directive:


```php
$container = $app->getContainer();
$blade = new \Slim\Views\Blade(
    '/path/to/views/folder',
    '/path/to/cache/folder'
);

// example directive here, usage: @helloWorld
$blade->getRenderer()->getCompiler()->directive('helloWorld', function(){

    return "<?php echo 'Hello World'; ?>";
});

$container['blade'] = $blade;

$app->run();
```

If you like to build custom directive using $app:

```php

$app->getContainer()['blade']->getRenderer()->getCompiler()->directive('helloWorld', function(){

    return "<?php echo 'Hello My Second World'; ?>";
});
```

