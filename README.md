# simple-event
  
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