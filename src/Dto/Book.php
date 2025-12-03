<?php

namespace NumaxLab\Cegal\Dto;

use SimpleXMLElement;

class Book
{
    private function __construct(
        public string $isbn,
        public ?string $ean,
        public ?string $invoicingIsbn,
        public ?string $completeWorksIsbn,
        public ?string $completeVolumeIsbn,
        public ?string $installmentIsbn,
        public ?string $referenceIsbn,
        public string $title,
        public ?string $subtitle,
        public ?string $publicationCountryIsoCode,
        public ?string $editorialIsbn,
        public ?string $editorial,
        public ?string $bindingTypeCode,
        public ?string $publicationLanguage,
        public ?string $editionNumber,
        public ?string $publicationMonth,
        public ?int $pagesQty,
        public ?int $width,
        public ?int $height,
        public ?string $topic,
        public ?string $ibic,
        public ?array $descriptors,
        public ?int $statusCode,
        public ?string $productTypeCode,
        public ?int $priceWithoutTaxes,
        public ?int $priceWithTaxes,
        public ?int $taxes,
        public ?string $collection,
        public ?string $collectionNumber,
        public ?string $volumeNumber,
        public ?string $url,
    ) {}

    public static function fromXml(SimpleXMLElement $xml): self
    {
        return new self(
            trim($xml->ISBN),
            trim($xml->EAN),
            trim($xml->ISBN_FACTURACION),
            trim($xml->ISBN_OBRA_COMPLETA),
            trim($xml->ISBN_TOMO_COMPLETO),
            trim($xml->ISBN_FASCICULO),
            trim($xml->ISBN_REFERENCIA),
            trim($xml->TITULO),
            trim($xml->SUBTITULO),
            trim($xml->PAIS_PUBLICACION),
            trim($xml->ISBN_EDITORIAL),
            trim($xml->NOMBRE_EDITORIAL),
            trim($xml->ENCUADERNACION),
            trim($xml->LENGUA_PUBLICACION),
            trim($xml->NUMERO_EDICION),
            trim($xml->FECHA_PUBLICACION),
            (int) trim($xml->NUMERO_PAGINAS),
            (int) trim($xml->ANCHO),
            (int) trim($xml->ALTO),
            trim($xml->CDU),
            trim($xml->IBIC),
            explode('/', trim($xml->DESCRIPTORES)),
            (int) trim($xml->SITUACION),
            trim($xml->TIPO_PRODUCTO),
            (int) trim($xml->PRECIO_SIN_IVA),
            (int) trim($xml->PRECIO_CON_IVA),
            (int) trim($xml->IVA),
            trim($xml->COLECCION),
            trim($xml->NUMERO_COLECCION),
            trim($xml->NUMERO_VOLUMEN),
            trim($xml->URL),
        );
    }
}