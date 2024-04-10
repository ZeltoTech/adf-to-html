<?php

namespace Zeltotech\AdfToHtml\Types;

use Zeltotech\AdfToHtml\AdfConverter;

class Media
{
    public function __construct(public readonly object $block, public readonly AdfConverter $adfConverter)
    {
    }

    public function toHtml(): string
    {
        if ($this->isImage()) {
            return "<img src='".$this->adfConverter->getMediaServer().$this->block->attrs->id."' alt='".$this->block->attrs->alt."'/>";
        } else {
            return "<a target='_blank' href='".$this->adfConverter->getMediaServer().$this->block->attrs->id."'>Attachment</a>";
        }
    }

    private function isImage(): bool
    {
        if (!isset($this->block->attrs->alt)) {
            return false;
        }
        $filenameExploded = explode(".", $this->block->attrs->alt);
        $extension = end($filenameExploded);

        $imageExtensions = [
            'png',
            'jpg',
            'jpeg',
            'gif',
            'bmp',
            'webp'
        ];

        if (in_array(strtolower($extension), $imageExtensions)) {
            return true;
        }

        return false;
    }
}