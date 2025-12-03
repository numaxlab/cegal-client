<?php

use Tests\Unit\Dto\ConcreteCollection;

it('can be instantiated from xml', function () {
    $xml = new SimpleXMLElement('<root></root>');
    $collection = ConcreteCollection::fromXml($xml);
    expect($collection)->toBeInstanceOf(ConcreteCollection::class);
});

it('can be created from xml', function () {
    $xml = new SimpleXMLElement('<root><item>one</item><item>two</item></root>');
    $collection = ConcreteCollection::fromXml($xml);
    expect($collection->count())->toBe(2);
});

it('can check if it has many items', function () {
    $xml = new SimpleXMLElement('<root><item>one</item><item>two</item></root>');
    $collection = ConcreteCollection::fromXml($xml);
    expect($collection->hasManyItems())->toBeTrue();

    $xml = new SimpleXMLElement('<root><item>one</item></root>');
    $collection = ConcreteCollection::fromXml($xml);
    expect($collection->hasManyItems())->toBeFalse();
});

it('can count its items', function () {
    $xml = new SimpleXMLElement('<root><item>one</item><item>two</item></root>');
    $collection = ConcreteCollection::fromXml($xml);
    expect($collection->count())->toBe(2);
});

it('is countable', function () {
    $xml = new SimpleXMLElement('<root><item>one</item><item>two</item></root>');
    $collection = ConcreteCollection::fromXml($xml);
    expect(count($collection))->toBe(2);
});

it('can be iterated', function () {
    $items = ['one', 'two'];
    $xml = new SimpleXMLElement('<root><item>one</item><item>two</item></root>');
    $collection = ConcreteCollection::fromXml($xml);

    foreach ($collection as $key => $value) {
        expect($value)->toBe($items[$key]);
    }
});

it('can check if it is empty', function () {
    $xml = new SimpleXMLElement('<root></root>');
    $collection = ConcreteCollection::fromXml($xml);
    expect($collection->isEmpty())->toBeTrue();

    $xml = new SimpleXMLElement('<root><item>one</item></root>');
    $collection = ConcreteCollection::fromXml($xml);
    expect($collection->isEmpty())->toBeFalse();
});

it('can get the first item', function () {
    $xml = new SimpleXMLElement('<root><item>one</item><item>two</item></root>');
    $collection = ConcreteCollection::fromXml($xml);
    expect($collection->first())->toBe('one');

    $xml = new SimpleXMLElement('<root></root>');
    $collection = ConcreteCollection::fromXml($xml);
    expect($collection->first())->toBeNull();
});
