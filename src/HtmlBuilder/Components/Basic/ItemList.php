<?php
/**
 * Copyright (c) 2020.
 * Adrian Schubek - https://adriansoftware.de
 */

namespace adrianschubek\HtmlBuilder\Components\Basic;


use adrianschubek\HtmlBuilder\Components\Component;

class ItemList extends Component
{
    protected array $props = ["type", "text"];

    public function render(): string
    {
        return <<<HTML
            <ol>                
                {{text}}     
                <children/>     
            </ol>         
        HTML;
    }
}