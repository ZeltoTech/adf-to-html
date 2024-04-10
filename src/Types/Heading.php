<?php

namespace Zeltotech\AdfToHtml\Types;

use Zeltotech\AdfToHtml\AdfConverter;

class Heading
{
    public function __construct(public readonly object $block, public readonly AdfConverter $adfConverter)
    {
    }

    public function toHtml()
    {
        $outputBuffer = "<h".$this->block->attrs->level.">";
        foreach ($this->block->content as $content) {
            if ($content->type === 'text') {
                $outputBuffer .= $content->text;
            }
        }
        $outputBuffer .= "</h".$this->block->attrs->level.">";

        return $outputBuffer;
    }
}