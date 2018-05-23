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

    /**
     * @expectedException \mozartk\SimpleEvent\Exception\EmptyFunctionArraysException
     */
    public function testWithEmptyFunctionArrays()
    {
        $event = new SimpleEvent();
        $event->emit('runEmptyArrays');
    }

    /**
     * @expectedException \mozartk\SimpleEvent\Exception\CannotFindTypesException
     */
    public function testUnregisteredTypes()
    {
        $event = new SimpleEvent();
        $event->set("testEvent", function(){
            return 1;
        });

        $event->emit("IveNeverDefinedTypes");
    }

    /**
     * @expectedException \mozartk\SimpleEvent\Exception\FunctionExistsButExpiredException
     */
    public function testDefineOnes()
    {
        $event = new SimpleEvent();
        $event->one("testEvent", function(){
            return 1;
        });

        $event->emit("testEvent");
        $event->emit("testEvent");
    }

    /**
     * @expectedException \mozartk\SimpleEvent\Exception\FunctionExistsButExpiredException
     */
    public function testDefineWithCount()
    {
        $event = new SimpleEvent();
        $event->setWithCount("testEvent", function(){
            return 1;
        }, 3);

        $event->emit("testEvent");
        $event->emit("testEvent");
        $event->emit("testEvent");
        $event->emit("testEvent"); //Will fire an exception on this line.
        $event->emit("IveNeverDefinedTypes");
    }

    public function testCheckCount()
    {
        $type = "testEvent";
        $event = new SimpleEvent();
        $event->setWithCount($type, function(){
            return 1;
        }, 3);

        $check1 = $event->getCount($type);
        $event->emit($type);

        $check2 = $event->getCount($type);

        $this->assertEquals(($check1-1), $check2);
    }



    public function testCheckCountWithoutLimit()
    {
        $type = "testEvent";
        $event = new SimpleEvent();
        $event->set($type, function(){
            return 1;
        });

        $check1 = $event->getCount($type);
        $event->emit($type);

        $check2 = $event->getCount($type);

        $this->assertEquals($check1, $check2);
    }

    /**
     * @expectedException \mozartk\SimpleEvent\Exception\CannotFindTypesException
     */
    public function testCheckCountWithoutDefinedFunction()
    {
        $type = "testEvent";
        $event = new SimpleEvent();
        $event->set($type, function(){
            return 1;
        });

        $event->getCount("IveNeverDefinedTypes");
    }

    /**
     * @expectedException \mozartk\SimpleEvent\Exception\CannotFindTypesException
     */
    public function testRemoveCheck()
    {
        $type = "testEvent";
        $type2 = "testEvent2";
        $event = new SimpleEvent();
        $event->set($type, function(){
            return 1;
        });

        $event->set($type2, function(){
            return 1;
        });
        $event->remove($type);
        $event->remove($type);
    }

    /**
     * @expectedException \mozartk\SimpleEvent\Exception\CannotFindTypesException
     */
    public function testRemoveAndCallFunction()
    {
        $type = "testEvent";
        $type2 = "testEvent2";
        $event = new SimpleEvent();
        $event->set($type, function(){
            return 1;
        });

        $event->set($type2, function(){
            return 1;
        });
        $event->remove($type);
        $event->emit($type);
    }

    /**
     * @expectedException \mozartk\SimpleEvent\Exception\EmitTypeException
     */
    public function testMissEmitType()
    {
        $type = "testEvent";
        $event = new SimpleEvent();
        $event->set($type, function(){
            return 1;
        });
        $event->emit();
    }
}