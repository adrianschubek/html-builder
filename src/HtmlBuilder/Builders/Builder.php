<?php
/**
 * Copyright (c) 2020.
 * Adrian Schubek - https://adriansoftware.de
 */

namespace adrianschubek\HtmlBuilder\Builders;


use adrianschubek\HtmlBuilder\Components\Component;
use adrianschubek\HtmlBuilder\Exceptions\ComponentAliasNotFound;
use adrianschubek\HtmlBuilder\Templates\Basic\DefaultTemplate;
use adrianschubek\HtmlBuilder\Templates\Template;
use adrianschubek\HtmlBuilder\Templates\TemplateLoader;
use MatthiasMullie\Minify\JS;

abstract class Builder
{
    protected static array $componentsMap = [];
    protected static array $templatesMap = [];
    protected static string $htmlPrefix = "";
    protected TemplateLoader $pageTemplate;
    protected array $scripts = [];
    protected array $components = [];
    protected array $config = [];

    public function __construct(?Template $template = null)
    {
        $this->template($template ?? new DefaultTemplate());
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
        if (isset($app["head"]["template"])) {
            $tmap = static::templatesMap();
            $template = new $tmap[$app["head"]["template"]];
        }
        $builder = new static($template ?? new DefaultTemplate());

        if (isset($app["head"])) {
            $builder->config($app["head"]);
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

    public static function templatesMap(array $templates = []): array
    {
        static::$templatesMap = array_merge(static::$templatesMap, $templates);
        return static::$templatesMap;
    }

    public function config(array $cfg): self
    {
        $this->config = array_merge($this->config, $cfg);
        return $this;
    }

    public static function componentsMap(array $components = []): array
    {
        static::$componentsMap = array_merge(static::$componentsMap, $components);
        return static::$componentsMap;
    }

    public function add(Component ...$components): self
    {
        foreach ($components as $component) {
            $this->components[] = $component;
        }
        return $this;
    }

    public static function fromXml(string $xml): self
    {
        $x = simplexml_load_string($xml);
        $builder = new static();
        foreach ($x->head as $t) {
            $builder->config((array)$t);
        }
        foreach ($x->page->children() as $component) {
            $cname = $component->getName();
            if (!isset(static::$componentsMap[$cname])) {
                throw new ComponentAliasNotFound($cname);
            }
            $tempComponentName = static::$componentsMap[$cname];

            $data = [];

            if ((string)$component !== "") {
                $data["text"] = (string)$component;
            }

            foreach ($component->attributes() as $key => $val) {
                $data[$key] = (string)$val;
            }
            $builder->add(new $tempComponentName($data));
        }
        return $builder;
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

    public function render(): string
    {
        $data = [];
        foreach ($this->components as $component) {
            $this->scripts($component->getInternalScripts());
            $data[] = $component->get();
        }
        $components = implode($data);

        $this->config["body"] = $components;
        $this->config["styles"] = $this->styles();
        $this->config["scripts"] = "<script>" . (new JS(implode($this->scripts)))->minify()  . "</script>";
        $this->config["lang"] ??= "en";

        return $this->pageTemplate->render($this->config);
    }

    public function scripts(string $scripts): self
    {
        $this->scripts[] = $scripts;
        return $this;
    }

    public function styles(): string
    {
        return "";
    }
}