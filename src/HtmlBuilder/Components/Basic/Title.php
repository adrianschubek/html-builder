<?php
/**
 * Copyright (c) 2020.
 * Adrian Schubek - https://adriansoftware.de
 */

namespace adrianschubek\HtmlBuilder\Components\Basic;


use adrianschubek\HtmlBuilder\Components\Component;

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