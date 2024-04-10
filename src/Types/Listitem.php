<?php

namespace Zeltotech\AdfToHtml\Types;

use Zeltotech\AdfToHtml\AdfConverter;

class Listitem
{

    public function __construct(public readonly object $block, public readonly AdfConverter $adfConverter)
    {
    }

    public function toHtml()
    {
        $outputBuffer = "<li>";

        foreach ($this->block->content as $listContent) {
            $outputBuffer .= $this->adfConverter->parseContentBlockToHtml($listContent);
        }

        $outputBuffer .= "</li>";

        return $outputBuffer;
    }
}