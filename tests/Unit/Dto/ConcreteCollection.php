<?php

namespace Tests\Unit\Dto;

use NumaxLab\Cegal\Dto\Collection;
use SimpleXMLElement;

/**
 * @extends Collection<int, string>
 */
readonly class ConcreteCollection extends Collection
{
    /**
     * @param  SimpleXMLElement  $xml
     * @return static
     */
    public static function fromXml(SimpleXMLElement $xml): static
    {
        // For testing purposes, we'll just create a collection with dummy data.
        $items = [];
        foreach ($xml->item as $item) {
            $items[] = (string) $item;
        }

        /** @var static $collection */
        $collection = new self($items);

        return $collection;
    }
}
