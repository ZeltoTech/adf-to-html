<?php

namespace Zeltotech\AdfToHtml\Types;

use Zeltotech\AdfToHtml\AdfConverter;

class Inlinecard
{
    public function __construct(public readonly object $block, public readonly AdfConverter $adfConverter)
    {
    }

    public function toHtml(): string
    {
        if ($this->block->attrs->url) {
            return "<a target='_blank' href='".$this->block->attrs->url."'>".$this->block->attrs->url."</a>";
        }

        return '';
    }
}