<?php

namespace mozartk\SimpleEvent;

use mozartk\SimpleEvent\Exception\CannotFindTypesException;
use mozartk\SimpleEvent\Exception\EmitTypeException;
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
        if (!is_string($type)) {
            throw new EmitTypeException();
        }

        return array(
            'type' => $type,
            'param'=> $args
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

    public function remove($type)
    {
        if (!array_key_exists($type, $this->functions)) {
            throw new CannotFindTypesException();
        }

        unset($this->functions[$type]);
        unset($this->callsRemaining[$type]);
    }

    /**
     * Check emit expiration.
     *
     * @param string $type
     * @return int
     * @throws CannotFindTypesException
     */
    public function getCount($type)
    {
        if (!array_key_exists($type, $this->functions)) {
            throw new CannotFindTypesException();
        }

        return $this->callsRemaining[$type];
    }

    private function descCount($type)
    {
        if ($this->callsRemaining[$type] > 0) {
            $this->callsRemaining[$type]--;
        }
    }

    public function emit()
    {
        $arr = $this->splitArgv(func_get_args());
        $return = null;

        if (count($this->functions) === 0) {
            throw new EmptyFunctionArraysException();
        }

        if (!array_key_exists($arr['type'], $this->functions)) {
            throw new CannotFindTypesException();
        }

        if ($this->getCount($arr['type']) === 0) {
            throw new FunctionExistsButExpiredException();
        }

        if (is_callable($this->functions[$arr['type']])) {
            $return = call_user_func_array($this->functions[$arr['type']], $arr['param']);

            $this->descCount($arr['type']);
        }

        return $return;
    }
}
