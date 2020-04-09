<?php
/**
 * Copyright (c) 2020.
 * Adrian Schubek - https://adriansoftware.de
 */

namespace adrianschubek\HtmlBuilder\Components;


use adrianschubek\HtmlBuilder\Exceptions\PropNotFoundException;
use adrianschubek\HtmlBuilder\Exceptions\TooManyPropsInMethodCall;

abstract class Component
{
    protected array $props = [];

    private array $internalProps;
    private array $data = [];

    public function __construct(...$props)
    {
        $this->internalProps = [...$this->props, "class"];

        if (func_num_args() !== 0) {
            if (is_array($arr = $props[0])) {
                foreach ($arr as $key => $val) {
                    $this->__set($key, $val);
                }
                return;
            }

            $i = 0;
            $maxIndex = count($this->internalProps);
            foreach ($props as $prop) {
                if ($i === $maxIndex) {
                    throw new TooManyPropsInMethodCall(static::class);
                }
                $this->data[$this->internalProps[$i]] = $prop;
                $i++;
            }
        }
    }

    public function __set($name, $value): self
    {
        if (!in_array($name, $this->props)) {
            throw new PropNotFoundException(static::class);
        }
        $this->data[$name] = $value;
        return $this;
    }

    public function get(): string
    {
        $str = $this->render();
        foreach ($this->data as $key => $value) {
            $str = str_replace("{{" . $key . "}}", $value, $str);
        }
        return $str;
    }

    abstract public function render(): string;
}