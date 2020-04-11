<?php
/**
 * Copyright (c) 2020.
 * Adrian Schubek - https://adriansoftware.de
 */

namespace adrianschubek\HtmlBuilder\Components\Basic;


use adrianschubek\HtmlBuilder\Components\Component;

class Button extends Component
{
    protected array $props = ["text", "color"];

    protected function computed(): array
    {
        return [
            "color" => fn($val): string => $val === "danger" ? "red" : "blue"
        ];
    }

    public function render(): string
    {
        return <<<HTML
            <button {{_id}} style="background: {{color}}">{{text}}</button>
        HTML;
    }

    public function scripts(): string
    {
        return <<<JS
             $.addEventListener("click", () => {
                 alert($.innerText)
             });     
        JS;
    }
}