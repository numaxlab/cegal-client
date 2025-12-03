<?php

namespace NumaxLab\Cegal\Dto;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use SimpleXMLElement;
use Traversable;

readonly abstract class Collection implements IteratorAggregate, Countable
{
    protected function __construct(public array $items) {}

    public abstract static function fromXml(SimpleXMLElement $xml): self;

    public function hasManyItems(): bool
    {
        return $this->count() > 1;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function first(): mixed
    {
        return $this->items[0] ?? null;
    }
}
