<?php
/**
 * Copyright (c) 2020.
 * Adrian Schubek - https://adriansoftware.de
 */

namespace adrianschubek\HtmlBuilder\Templates;


class TemplateLoader
{
    private Template $template;

    private function __construct(Template $template)
    {
        $this->template = $template;
    }

    public static function load(Template $tmpl): self
    {
        return new static($tmpl);
    }

    public function render(array $values): string
    {
        $tmpl = $this->template->render();
        foreach ($values as $key => $value) {
            $tmpl = str_replace("{{" . $key . "}}", $value, $tmpl);
        }
        return $tmpl;
    }
}