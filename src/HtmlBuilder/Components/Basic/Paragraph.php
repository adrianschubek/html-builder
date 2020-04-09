<?php
/**
 * Copyright (c) 2020.
 * Adrian Schubek - https://adriansoftware.de
 */

namespace adrianschubek\HtmlBuilder\Components\Basic;


use adrianschubek\HtmlBuilder\Components\Component;

class Paragraph extends Component
{
    protected array $props = ["text"];

    public function render(): string
    {
        return <<<HTML
            <p>{{text}}</p>
        HTML;
    }
}