<?php

namespace Zeltotech\AdfToHtml\Types;

use Zeltotech\AdfToHtml\AdfConverter;

class Hardbreak
{
    public function __construct(public readonly object $block, public readonly AdfConverter $adfConverter)
    {

    }

    public function toHtml()
    {
        return "<br>";
    }
}