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

    protected function computed(): array
    {
        return [
            "type" => fn($val) => $val === "ol" ? "ol" : "ul"
        ];
    }

    public function render(): string
    {
        return <<<HTML
            <{{type}}>                
                {{text}}     
                <children/>     
            <{{type}}/>         
        HTML;
    }
}