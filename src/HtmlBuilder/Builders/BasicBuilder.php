<?php
/**
 * Copyright (c) 2020.
 * Adrian Schubek - https://adriansoftware.de
 */

namespace adrianschubek\HtmlBuilder\Builders;


use adrianschubek\HtmlBuilder\Components\Basic\Button;
use adrianschubek\HtmlBuilder\Components\Basic\ItemList;
use adrianschubek\HtmlBuilder\Components\Basic\Paragraph;
use adrianschubek\HtmlBuilder\Components\Basic\Title;
use adrianschubek\HtmlBuilder\Templates\Basic\DefaultTemplate;
use adrianschubek\HtmlBuilder\Templates\Basic\PlainTemplate;

class BasicBuilder extends Builder
{
    protected static array $componentsMap = [
        "Title" => Title::class,
        "h1" => Title::class,
        "Paragraph" => Paragraph::class,
        "p" => Paragraph::class,
        "Button" => Button::class,
        "btn" => Button::class,
        "ItemList" => ItemList::class,
        "item-list" => ItemList::class,
    ];

    protected static array $templatesMap = [
        "default" => DefaultTemplate::class,
        "plain" => PlainTemplate::class
    ];
}