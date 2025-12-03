<?php

namespace NumaxLab\Cegal\Dto;

use SimpleXMLElement;

readonly class BookAvailability
{
    private function __construct(
        public string $isbn,
        public string $sinliId,
        public string $name,
        public string $type,
        public string $address,
        public string $postalCode,
        public string $municipality,
        public string $province,
        public string $contactPerson,
        public string $phoneNumber,
        public string $email,
        public string $website,
        public string $countryIsoCode,
        public string $timestamp,
    ) {}

    public static function fromXml(SimpleXMLElement $xml): self
    {
        return new self(
            trim($xml->ISBN),
            trim($xml->ID_SINLI_ASOCIADO),
            trim($xml->NOMBREA_ASOCIADO),
            trim($xml->TIPO_ASOCIADO),
            trim($xml->DIRECCION),
            trim($xml->CODIGO_POSTAL),
            trim($xml->LOCALIDAD),
            trim($xml->PROVINCIA),
            trim($xml->PERSONA_CONTACTO),
            trim($xml->TELEFONO),
            trim($xml->EMAIL),
            trim($xml->WEB),
            trim($xml->PAIS_ISO),
            trim($xml->FECHA_COMUNICACION),
        );
    }

    public function isBookshop(): bool
    {
        return strtoupper($this->type) === 'L';
    }

    public function isDistributor(): bool
    {
        return strtoupper($this->type) === 'D';
    }
}
