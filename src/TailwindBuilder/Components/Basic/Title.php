<?php
/**
 * Copyright (c) 2020.
 * Adrian Schubek - https://adriansoftware.de
 */

namespace adrianschubek\TailwindBuilder\Components\Basic;


use adrianschubek\TailwindBuilder\Components\Component;

class Title extends Component
{
    protected array $props = ["text"];

    public function render(): string
    {
        return <<<HTML
            <h1 class="{{class}}">{{text}}</h1>
        HTML;
    }
}