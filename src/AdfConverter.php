<?php

namespace Zeltotech\AdfToHtml;

use JiraCloud\ADF\AtlassianDocumentFormat;

class AdfConverter
{
    public readonly array $content;

    public function __construct(AtlassianDocumentFormat|array $atlassianDocumentFormat, public readonly string $mediaServer)
    {
        if (is_a($atlassianDocumentFormat, AtlassianDocumentFormat::class)) {
            $this->content = $atlassianDocumentFormat->content;
        } else {
            $this->content = json_decode(json_encode($atlassianDocumentFormat['content']));
        }
    }

    public function toHtml(): string
    {
        $outputBuffer = "";


        foreach ($this->content as $contentBlock)
        {
            $outputBuffer .= self::parseContentBlockToHtml($contentBlock);
        }

        return $outputBuffer;

    }

    public function parseContentBlockToHtml($contentBlock): string
    {
        $outputBuffer = "";
        $class = "\\Zeltotech\\AdfToHtml\\Types\\".ucfirst(strtolower($contentBlock->type));
        if (class_exists($class))
        {
            $invokedClass = new $class($contentBlock, $this);
            $outputBuffer .= $invokedClass->toHtml();
        }

        return $outputBuffer;
    }

    public function getMediaServer()
    {
        return $this->mediaServer;
    }
}