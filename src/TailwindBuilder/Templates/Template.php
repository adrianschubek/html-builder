<?php
/**
 * Copyright (c) 2020.
 * Adrian Schubek - https://adriansoftware.de
 */

namespace adrianschubek\TailwindBuilder\Templates;


interface Template
{
    public function render(): string;
}