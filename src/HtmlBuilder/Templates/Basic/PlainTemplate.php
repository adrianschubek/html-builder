<?php
/**
 * Copyright (c) 2020.
 * Adrian Schubek - https://adriansoftware.de
 */

namespace adrianschubek\HtmlBuilder\Templates\Basic;


use adrianschubek\HtmlBuilder\Templates\Template;

class PlainTemplate implements Template
{
    public function render(): string
    {
        return <<<HTML
        <!DOCTYPE html>
        <html lang="{{lang}}">
        <head>
            <meta charset="UTF-8">
            <title>{{title}}</title>
            {{styles}}
        </head>
        <body>
            {{body}}
            {{scripts}}
        </body>
        </html>
        HTML;
    }
}