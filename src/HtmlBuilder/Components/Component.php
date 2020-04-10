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
    protected static string $scriptReference = "$";
    protected array $props = [];
    private array $internalProps;
    private array $data = [];
    private ?string $id;

    public function __construct(...$props)
    {
        $this->id ??= bin2hex(random_bytes(5));
        $this->data["_id"] = "id=" . $this->id;
        $this->internalProps = [...$this->props, "class"];

        if (func_num_args() !== 0) {
            // Case ([key => $val,...])
            if (is_array($arr = $props[0])) {
                foreach ($arr as $key => $val) {
                    $this->__set($key, $val);
                }
                return;
            }

            // Case (val, val, val...)
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
        $computed = $this->computed();
        foreach ($this->data as $key => &$value) {
            if (key_exists($key, $computed)) {
                $this->data[$key] = $computed[$key]($value);
            }
            $str = str_replace("{{" . $key . "}}", $value, $str);
        }
        return $str;
    }

    abstract public function render(): string;

    protected function computed(): array
    {
        return [];
    }

    public function getInternalScripts(): string
    {
        return 'let v' . $this->id . ' = document.getElementById("' . $this->id . '");'
            . str_replace(static::$scriptReference, "v" . $this->id, $this->scripts());
    }

    public function scripts(): string
    {
        return "";
    }
}