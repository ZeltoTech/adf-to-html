<?php

namespace Zeltotech\AdfToHtml;

use DOMDocument;

class HtmlConverter
{
    public readonly string $content;

    public function __construct(string $html)
    {
        $this->content = $html;
    }

    public function toAdf(): array
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($this->content);

        $adf = [
            "version" => 1,
            "type" => "doc",
            "content" => []
        ];

        $body = $dom->getElementsByTagName('body')->item(0) ?: $dom->documentElement;

        foreach ($body->childNodes as $node) {
            $this->processNode($node, $adf['content']);
        }

        return $adf;
    }

    private function processNode($node, &$content)
    {
        if ($node->nodeType === XML_ELEMENT_NODE) {
            switch ($node->nodeName) {
                case 'a':
                    $content[] = $this->convertLink($node);
                    break;
                case 'p':
                case 'div':
                    $content[] = $this->convertParagraph($node);
                    break;
                case 'h1':
                case 'h2':
                case 'h3':
                case 'h4':
                case 'h5':
                case 'h6':
                    $content[] = $this->convertHeading($node);
                    break;
                case 'ul':
                case 'ol':
                    $content[] = $this->convertList($node, $node->nodeName === 'ul' ? 'bulletList' : 'orderedList');
                    break;
            }
        }
    }

    private function convertParagraph($node): array
    {
        $content = $this->getNodeContent($node);
        return [
            "type" => "paragraph",
            "content" => $content
        ];
    }

    private function convertHeading($node)
    {
        $level = substr($node->nodeName, 1, 1);
        $content = $this->getNodeContent($node);

        return [
            "type" => "heading",
            "attrs" => ["level" => (int)$level],
            "content" => $content
        ];
    }

    private function convertList($node, $type)
    {
        $items = [];
        foreach ($node->childNodes as $child) {
            if ($child->nodeName === 'li') {
                $items[] = $this->convertListItem($child);
            }
        }
        return [
            "type" => $type,
            "content" => $items
        ];
    }

    private function convertListItem($node) {
        $paragraphContent = [];
        $this->convertNodeChildren($node, $paragraphContent);

        return [
            "type" => "listItem",
            "content" => [
                [
                    "type" => "paragraph",
                    "content" => $paragraphContent
                ]
            ]
        ];
    }

    private function convertNodeChildren($node, &$content) {
        foreach ($node->childNodes as $child) {
            if ($child->nodeType === XML_TEXT_NODE && trim($child->nodeValue) !== '') {
                $content[] = ["type" => "text", "text" => trim($child->nodeValue)];
            } elseif ($child->nodeType === XML_ELEMENT_NODE) {
                $this->processNode($child, $content);
            }
        }
    }

    private function convertLink($node)
    {
        $href = $node->getAttribute('href');
        return [
            "type" => "text",
            "text" => $node->textContent,
            "marks" => [
                ["type" => "link", "attrs" => ["href" => $href]]
            ]
        ];
    }

    private function getNodeContent($node)
    {
        $content = [];
        foreach ($node->childNodes as $child) {
            if ($child->nodeType === XML_TEXT_NODE && trim($child->nodeValue) !== '') {
                $content[] = ["type" => "text", "text" => trim($child->nodeValue)];
            } elseif ($child->nodeType === XML_ELEMENT_NODE) {
                $this->processNode($child, $content);  // Recursive call to handle nested elements
            }
        }
        return $content;
    }
}