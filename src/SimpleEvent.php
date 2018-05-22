<?php

namespace mozartk\SimpleEvent;


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
        if($args !== null) {
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