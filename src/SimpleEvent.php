<?php

namespace mozartk\SimpleEvent;


use mozartk\SimpleEvent\Exception\CannotFindTypesException;
use mozartk\SimpleEvent\Exception\EmptyFunctionArraysException;

class SimpleEvent
{
    private $functions = array();
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

        if(is_callable($this->functions[$arr['type']])){
            if($arr['param'] !== null) {
                $return = call_user_func_array($this->functions[$arr['type']], $arr['param']);
            } else {
                $return = call_user_func($this->functions[$arr['type']]);
            }
        }

        return $return;
    }

}