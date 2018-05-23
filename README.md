# simple-event
<p align="left">
<a href="https://travis-ci.org/mozartk/SimpleEvent?branch=master"><img src="https://travis-ci.org/mozartk/SimpleEvent.svg?branch=master" alt="Build Status"></a>
<a href='https://travis-ci.org/mozartk/SimpleEvent?branch=master'><img src="https://img.shields.io/travis/php-v/mozartk/SimpleEvent.svg"></a>
<a href='https://coveralls.io/github/mozartk/SimpleEvent?branch=master'><img src='https://coveralls.io/repos/github/mozartk/SimpleEvent/badge.svg?branch=master' alt='Coverage Status' /></a>
<a href='https://opensource.org/licenses/MIT'><img src='https://img.shields.io/badge/License-MIT-green.svg' alt='Coverage Status' /></a>
<a href='OJDDEV.md'><img src="https://img.shields.io/badge/OJD-mozartk-green.svg" alt="OJD" title="WE ARE OJD"></a>
</p>  
This is a simple PHP event implementation.  

## Installation
Coming soon.
  
## Basic Usage
### How to run
```php
<?php

include "vendor/autoload.php";

use \mozartk\SimpleEvent\SimpleEvent;

$event = new SimpleEvent();
$event->set("event1", function(){
     return "Hello World";
});

$result = $event->emit("testEvent");

echo $result; //return Hello World
```

If you want to run only once...
```php
$event->one("event2", function(){
    return 111;
});
$result = $event->emit("testEvent");
echo $result; //return 1
$result = $event->emit("testEvent"); //Exceptions on this line.
```

..And set specific limits...
```php
$event->setWithCount("testEvent", function(){
    return 1;
}, 3);

$result = $event->emit("testEvent");
$result = $event->emit("testEvent");
$result = $event->emit("testEvent");
$result = $event->emit("testEvent"); //Exceptions on this line.
```

## License
Made by mozartk.  
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.