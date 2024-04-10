<?php

namespace Zeltotech\AdfToHtml\Types;

use Zeltotech\AdfToHtml\AdfConverter;

class Orderedlist
{

    public function __construct(public readonly object $block, public readonly AdfConverter $adfConverter)
    {
    }

    public function toHtml()
    {
        $outputBuffer = "<ol>";

        foreach ($this->block->content as $listContent) {
            $outputBuffer .= $this->adfConverter->parseContentBlockToHtml($listContent);
        }

        $outputBuffer .= "</ol>";

        return $outputBuffer;
    }
}