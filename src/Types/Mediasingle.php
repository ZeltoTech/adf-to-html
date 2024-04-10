<?php

namespace Zeltotech\AdfToHtml\Types;

use Zeltotech\AdfToHtml\AdfConverter;

class Mediasingle
{
    public function __construct(public readonly object $block, public readonly AdfConverter $adfConverter)
    {

    }

    public function toHtml(): string
    {
        $outputBuffer = "";
        foreach ($this->block->content as $contentBlock) {
            $outputBuffer .= $this->adfConverter->parseContentBlockToHtml($contentBlock);
        }

        return $outputBuffer;
    }
}