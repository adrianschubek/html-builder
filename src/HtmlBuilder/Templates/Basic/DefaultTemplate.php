<?php
/**
 * Copyright (c) 2020.
 * Adrian Schubek - https://adriansoftware.de
 */

namespace adrianschubek\HtmlBuilder\Templates\Basic;


use adrianschubek\HtmlBuilder\Templates\Template;

class DefaultTemplate implements Template
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
            <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
            <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.css">
            <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/milligram/1.3.0/milligram.css">
        </head>
        <body style="padding: 25px">
            {{body}}
            {{scripts}}
        </body>
        </html>
        HTML;
    }
}