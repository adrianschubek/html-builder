<?php
/**
 * Copyright (c) 2020.
 * Adrian Schubek - https://adriansoftware.de
 */

namespace adrianschubek\HtmlBuilder\Builders;

use adrianschubek\HtmlBuilder\Templates\SimpleBlog;

class TailwindBuilder extends Builder
{
    protected static array $componentsMap = [

    ];

    protected static array $templatesMap = [
        "blog" => SimpleBlog::class
    ];

    public function styles(): string
    {
        return <<<HTML
            <style>
                @import "https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css";            
            </style>
        HTML;
    }
}