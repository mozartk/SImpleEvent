<?php

namespace mozartk\SimpleEvent\Test;

use mozartk\SimpleEvent\SimpleEvent;
use PHPUnit\Framework\TestCase;

class SimpleEventTest extends TestCase
{
    public function testEmit()
    {
        $event = new SimpleEvent();
        $event->set("testEvent", function(){
            return 123;
        });

        $result = $event->emit("testEvent");
        $this->assertEquals($result, 123);
    }

    public function testEmitWithArgs()
    {
        $paramStr = "mozartk/SimpleEvent";
        $arg_arr = explode("/", $paramStr);

        $event = new SimpleEvent();
        $event->set("testEvent", function($param1, $param2){
            $param = array();
            $param[] = $param1;
            $param[] = $param2;

            return implode("/", $param);
        });
        $returnStr = $event->emit("testEvent", $arg_arr[0], $arg_arr[1]);

        $this->assertEquals($paramStr, $returnStr);
    }
}