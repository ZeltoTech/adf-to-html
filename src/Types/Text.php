<?php

namespace Zeltotech\AdfToHtml\Types;

use Zeltotech\AdfToHtml\AdfConverter;

class Text
{
    public function __construct(public readonly object $block, public readonly AdfConverter $adfConverter)
    {
    }

    public function toHtml()
    {
        return $this->formatTextBlockToHtml($this->block);
    }

    private function formatTextBlockToHtml(object $block): string
    {
        if (!isset($block->marks)) {
            return $block->text;
        }

        $outputBuffer = "";

        foreach ($block->marks as $mark) {
            if ($mark->type === 'link') {
                $outputBuffer .= "<a target='_blank' href='".$mark->attrs->href."'>";
            } elseif ($mark->type === 'underline') {
                $outputBuffer .= "<u>";
            } else {
                $outputBuffer .= "<" . $mark->type . ">";
            }
        }

        $outputBuffer .= $block->text;

        foreach (array_reverse($block->marks, true) as $mark) {
            if ($mark->type === 'link') {
                $outputBuffer .= "</a>";
            } elseif ($mark->type === 'underline') {
                $outputBuffer .= "</u>";
            } else {
                $outputBuffer .= "</" . $mark->type . ">";
            }
        }

        return $outputBuffer;
    }
}