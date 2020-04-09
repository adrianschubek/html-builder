<?php
/**
 * Copyright (c) 2020.
 * Adrian Schubek - https://adriansoftware.de
 */

namespace adrianschubek\TailwindBuilder\Builders;


use adrianschubek\TailwindBuilder\Components\Basic\Paragraph;
use adrianschubek\TailwindBuilder\Components\Basic\Title;
use adrianschubek\TailwindBuilder\Components\Component;
use adrianschubek\TailwindBuilder\Exceptions\ComponentAliasNotFound;
use adrianschubek\TailwindBuilder\Templates\DefaultTemplate;
use adrianschubek\TailwindBuilder\Templates\Template;
use adrianschubek\TailwindBuilder\Templates\TemplateLoader;

class Builder
{
    protected static array $componentsMap = [
        "title" => Title::class,
        "p" => Paragraph::class
    ];
    protected static array $templatesMap = [
        "default" => DefaultTemplate::class
    ];
    protected TemplateLoader $pageTemplate;
    protected array $scripts = [];
    protected array $components = [];
    protected array $config = [];

    public function __construct(Template $template)
    {
        $this->template($template);
        $this->scripts = [];
    }

    public function template(Template $template): self
    {
        $this->pageTemplate = TemplateLoader::load($template);

        return $this;
    }

    public static function fromJson(string $json, Template $template = null): self
    {
        $app = json_decode($json, true);
        $builder = new static($template ?? new DefaultTemplate());

        $templates = static::templatesMap();
        if (isset($app["head"])) {
            if (isset($app["head"]["title"])) $builder->title($app["head"]["title"]);
            if (isset($app["head"]["lang"])) $builder->lang($app["head"]["lang"]);
        }
        $map = static::componentsMap();
        if (isset($app["page"])) {
            foreach ($app["page"] as $key => $val) {
                if (!isset($map[$key])) throw new ComponentAliasNotFound($key);

                $component = new $map[$key]($val);
                $builder->add($component);
            }
        }

        return $builder;
    }

    public static function templatesMap(): array
    {
        return static::$templatesMap;
    }

    public function title(string $title): self
    {
        $this->config["title"] = $title;
        return $this;
    }

    public function lang(string $lang): self
    {
        $this->config["lang"] = $lang;
        return $this;
    }

    public static function componentsMap(): array
    {
        return static::$componentsMap;
    }

    public function add(Component ...$components): self
    {
        foreach ($components as $component) {
            $this->components[] = $component;
        }
        return $this;
    }

    public function config(array $cfg): self
    {
        $this->config = array_merge($this->config, $cfg);
        return $this;
    }

    public function scripts(string $scripts): self
    {
        $this->scripts[] = $scripts;
        return $this;
    }

    public function render(): string
    {
        $data = [];
        foreach ($this->components as $component) {
            $data[] = $component->get();
        }
        $components = implode($data);

        $this->config["body"] = $components;
        $this->config["styles"] = $this->styles();
        $this->config["scripts"] = implode($this->scripts);
        $this->config["lang"] ??= "en";

        return $this->pageTemplate->render($this->config);
    }

    public function styles(): string
    {
        return <<<HTML
            <style>
                @import "https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css";            
            </style>
        HTML;
    }
}