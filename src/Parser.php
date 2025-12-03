<?php

namespace NumaxLab\Cegal;

use NumaxLab\Cegal\Dto\AvailabilityCollection;
use NumaxLab\Cegal\Dto\BookCollection;
use NumaxLab\Cegal\Dto\Collection;
use NumaxLab\Cegal\Exceptions\CegalApiException;
use RuntimeException;
use SimpleXMLElement;

class Parser
{
    private SimpleXMLElement $xml;

    /**
     * @param  string  $xmlString
     * @throws RuntimeException
     */
    public function __construct(string $xmlString)
    {
        libxml_use_internal_errors(true);

        $xml = simplexml_load_string($xmlString);

        if ($xml === false) {
            $errors = array_map(fn (\LibXMLError $error) => $error->message, libxml_get_errors());
            throw new RuntimeException("Could not parse XML: ".implode(", ", $errors));
        }

        $this->xml = $xml;
    }

    public function parse(): Collection
    {
        $this->validate();

        return match ($this->xml->getName()) {
            'DISPONIBILIDAD' => AvailabilityCollection::fromXml($this->xml),
            'FICHALIBROS' => BookCollection::fromXml($this->xml),
            default => throw new RuntimeException("Unexpected XML root element: {$this->xml->getName()}"),
        };
    }

    private function validate(): void
    {
        foreach ($this->xml->children() as $child) {
            if ($child->getName() === 'ERRORES') {
                foreach ($child->children() as $error) {
                    throw new CegalApiException((int) $error->CODIGO);
                }
            }
        }
    }
}
