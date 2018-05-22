<?php

namespace mozartk\SimpleEvent;

use mozartk\SimpleEvent\Exception\CannotFindTypesException;
use mozartk\SimpleEvent\Exception\EmptyFunctionArraysException;
use mozartk\SimpleEvent\Exception\FunctionExistsButExpiredException;

class SimpleEvent
{
    private $functions = array();
    private $callsRemaining = array();
    public function __construct()
    {
    }

    private function splitArgv($args)
    {
        $type = array_shift($args);
        $param = null;
        if(count($args) !== 0) {
            $param = $args;
        }

        return array(
            'type' => $type,
            'param'=> $param
        );
    }

    public function set($type, callable $function)
    {
        $this->functions[$type] = $function;
        $this->callsRemaining[$type] = -1;
    }

    public function one($type, callable $function)
    {
        $this->set($type, $function);
        $this->callsRemaining[$type] = 1;
    }

    public function setWithCount($type, callable $function, $count)
    {
        $this->set($type, $function);
        $this->callsRemaining[$type] = $count;
    }

    private function getCount($type)
    {
        return $this->callsRemaining[$type];
    }

    private function descCount($type)
    {
        if($this->callsRemaining[$type] > 0) {
            $this->callsRemaining[$type]--;
        }
    }

    public function emit()
    {
        $arr = $this->splitArgv(func_get_args());
        $return = null;

        if(count($this->functions) === 0)
        {
            throw new EmptyFunctionArraysException();
        }

        if(!array_key_exists($arr['type'], $this->functions)) {
            throw new CannotFindTypesException();
        }

        if($this->getCount($arr['type']) === 0) {
            throw new FunctionExistsButExpiredException();
        }

        if(is_callable($this->functions[$arr['type']])){
            if($arr['param'] !== null) {
                $return = call_user_func_array($this->functions[$arr['type']], $arr['param']);
            } else {
                $return = call_user_func($this->functions[$arr['type']]);
            }

            $this->descCount($arr['type']);
        }

        return $return;
    }

}