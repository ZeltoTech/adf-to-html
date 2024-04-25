<?php

namespace Zeltotech\AdfToHtml;

use JiraCloud\ADF\AtlassianDocumentFormat;
use DOMDocument;

class HtmlConverter
{
    public readonly string $content;

    public function __construct(string $html)
    {
        $this->content = $html;
    }

    public function toAdf():array
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($this->content);

        $adf = [
            "version" => 1,
            "type" => "doc",
            "content" => []
        ];

        foreach ($dom->getElementsByTagName('body')->item(0)->childNodes as $node) {
            if ($node->nodeType === XML_ELEMENT_NODE) {
                switch ($node->nodeName) {
                    case 'p':
                        $adf['content'][] = $this->convertParagraph($node);
                        break;
                    case 'h1':
                    case 'h2':
                    case 'h3':
                    case 'h4':
                    case 'h5':
                    case 'h6':
                        $adf['content'][] = $this->convertHeading($node);
                        break;
                }
            }
        }
        return $adf;
    }

    public function toStdClass(): \stdClass
    {
        $adfArray = $this->toAdf();
        return json_decode(json_encode($adfArray));
    }

    private function convertParagraph($node): array
    {
        $content = [];

        foreach ($node->childNodes as $child) {
            if ($child->nodeType === XML_TEXT_NODE) {
                $content[] = [
                    "type" => "text",
                    "text" => $child->nodeValue
                ];
            } elseif ($child->nodeType === XML_ELEMENT_NODE && $child->nodeName === 'strong') {
                $content[] = [
                    "type" => "text",
                    "text" => $child->textContent,
                    "marks" => [
                        ["type" => "strong"]
                    ]
                ];
            }
        }

        return [
            "type" => "paragraph",
            "content" => $content
        ];
    }

    private function convertHeading($node) {
        $level = substr($node->nodeName, 1, 1);
        $content = $this->convertNodeChildren($node);

        return [
            "type" => "heading",
            "attrs" => [
                "level" => (int)$level
            ],
            "content" => $content
        ];
    }

    private function convertNodeChildren($node): array
    {
        $content = [];

        foreach ($node->childNodes as $child) {
            if ($child->nodeType === XML_TEXT_NODE) {
                $content[] = [
                    "type" => "text",
                    "text" => $child->nodeValue
                ];
            } elseif ($child->nodeType === XML_ELEMENT_NODE) {
                switch ($child->nodeName) {
                    case 'strong':
                        $content[] = [
                            "type" => "text",
                            "text" => $child->textContent,
                            "marks" => [
                                ["type" => "strong"]
                            ]
                        ];
                        break;
                    case 'em':
                        $content[] = [
                            "type" => "text",
                            "text" => $child->textContent,
                            "marks" => [
                                ["type" => "em"]
                            ]
                        ];
                        break;
                }
            }
        }

        return $content;
    }
}