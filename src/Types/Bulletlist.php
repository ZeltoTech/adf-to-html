<?php

namespace Zeltotech\AdfToHtml\Types;

use Zeltotech\AdfToHtml\AdfConverter;

class Bulletlist
{

    public function __construct(public readonly object $block, public readonly AdfConverter $adfConverter)
    {
    }

    public function toHtml()
    {
        $outputBuffer = "<ul>";

        foreach ($this->block->content as $listContent) {
            $outputBuffer .= $this->adfConverter->parseContentBlockToHtml($listContent);
        }

        $outputBuffer .= "</ul>";

        return $outputBuffer;
    }
}