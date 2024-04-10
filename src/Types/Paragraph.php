<?php

namespace Zeltotech\AdfToHtml\Types;

use Zeltotech\AdfToHtml\AdfConverter;

class Paragraph
{
    public function __construct(public readonly object $block, public readonly AdfConverter $adfConverter)
    {

    }

    public function toHtml()
    {
        $outputBuffer = "<p>";

        foreach ($this->block->content as $contentBlock) {
            $outputBuffer .= $this->adfConverter->parseContentBlockToHtml($contentBlock);
        }

        $outputBuffer .= "</p>";

        return $outputBuffer;
    }
}