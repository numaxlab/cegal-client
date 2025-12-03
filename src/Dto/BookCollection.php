<?php

namespace NumaxLab\Cegal\Dto;

use RuntimeException;
use SimpleXMLElement;

readonly class BookCollection extends Collection
{
    public static function fromXml(SimpleXMLElement $xml): static
    {
        $items = [];

        foreach ($xml as $node) {
            $items[] = match ($node->getName()) {
                'LIBRO' => Book::fromXml($node),
                default => throw new RuntimeException("Unexpected XML element: {$node->getName()}"),
            };
        }

        return new self($items);
    }
}
