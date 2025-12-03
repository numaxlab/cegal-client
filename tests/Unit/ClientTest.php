<?php

use NumaxLab\Cegal\Client;
use NumaxLab\Cegal\Dto\AvailabilityCollection;
use NumaxLab\Cegal\Dto\Book;
use NumaxLab\Cegal\Dto\BookAvailability;
use NumaxLab\Cegal\Dto\BookCollection;
use NumaxLab\Cegal\Exceptions\CegalApiException;
use NumaxLab\Cegal\Exceptions\TooManyIsbnsException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

function mockHttpClient(string $xmlBody): MockHttpClient
{
    return new MockHttpClient(new MockResponse($xmlBody, [
        'http_code' => 200,
        'response_headers' => ['Content-Type' => 'text/xml'],
    ]));
}

it('gets availability for multiple isbns', function () {
    $xmlBody = <<<XML
        <?xml version="1.0" encoding="utf-8"?>
        <DISPONIBILIDAD>
            <LIBRO>
                <ISBN>978-84-8332-838-5</ISBN>
                <ID_SINLI_ASOCIADO>LIB00460</ID_SINLI_ASOCIADO>
                <NOMBREA_ASOCIADO>Arnoia Distribucións</NOMBREA_ASOCIADO>
                <TIPO_ASOCIADO>D</TIPO_ASOCIADO>
                <DIRECCION>Ramalleira, 5</DIRECCION>
                <CODIGO_POSTAL>36140</CODIGO_POSTAL>
                <LOCALIDAD>Vilaboa</LOCALIDAD>
                <PROVINCIA>Pontevedra</PROVINCIA>
                <PERSONA_CONTACTO>Alberto Pérez Abreu</PERSONA_CONTACTO>
                <TELEFONO>986679007</TELEFONO>
                <EMAIL>alberto@arnoia.com</EMAIL>
                <WEB>www.arnoia.com</WEB>
                <PAIS_ISO>ES</PAIS_ISO>
                <FECHA_COMUNICACION>20081017</FECHA_COMUNICACION>
            </LIBRO>
            <LIBRO>
                <ISBN>978-84-8332-838-5</ISBN>
                <ID_SINLI_ASOCIADO>LIB00087</ID_SINLI_ASOCIADO>
                <NOMBREA_ASOCIADO>Librería Rayuela General</NOMBREA_ASOCIADO>
                <TIPO_ASOCIADO>L</TIPO_ASOCIADO>
                <DIRECCION>Cl. Cárcer, 1</DIRECCION>
                <CODIGO_POSTAL>29008</CODIGO_POSTAL>
                <LOCALIDAD>Málaga</LOCALIDAD>
                <PROVINCIA>Málaga</PROVINCIA>
                <PERSONA_CONTACTO>Juan Manuel Cruz</PERSONA_CONTACTO>
                <TELEFONO>952 219697</TELEFONO>
                <EMAIL>rayuela@libreriarayuela.com</EMAIL>
                <WEB>www.libreriarayuela.com</WEB>
                <PAIS_ISO>ES</PAIS_ISO>
                <FECHA_COMUNICACION>20081019</FECHA_COMUNICACION>
            </LIBRO>
            <LIBRO>
                <ISBN>978-0-582-40227-0</ISBN>
                <ID_SINLI_ASOCIADO>LIB00460</ID_SINLI_ASOCIADO>
                <NOMBREA_ASOCIADO>Arnoia Distribucións</NOMBREA_ASOCIADO>
                <TIPO_ASOCIADO>D</TIPO_ASOCIADO>
                <DIRECCION>Ramalleira, 5</DIRECCION>
                <CODIGO_POSTAL>36140</CODIGO_POSTAL>
                <LOCALIDAD>Vilaboa</LOCALIDAD>
                <PROVINCIA>Pontevedra</PROVINCIA>
                <PERSONA_CONTACTO>Alberto Pérez Abreu</PERSONA_CONTACTO>
                <TELEFONO>986679007</TELEFONO>
                <EMAIL>alberto@arnoia.com</EMAIL>
                <WEB>www.arnoia.com</WEB>
                <PAIS_ISO>ES</PAIS_ISO>
                <FECHA_COMUNICACION>20081017</FECHA_COMUNICACION>
            </LIBRO>
        </DISPONIBILIDAD>
        XML;

    $httpClient = \mockHttpClient($xmlBody);
    $client = new Client($httpClient, 'user', 'password');

    $response = $client->getAvailability([
        '978-84-8332-838-5',
        '978-0-582-40227-0',
    ]);

    expect($response)
        ->toBeInstanceOf(AvailabilityCollection::class)->toHaveCount(3)
        ->and($response->first())->toBeInstanceOf(BookAvailability::class)
        ->and($response->first()->isbn)->toBe('978-84-8332-838-5')
        ->and($response->first()->sinliId)->toBe('LIB00460')
        ->and($response->first()->name)->toBe('Arnoia Distribucións')
        ->and($response->first()->type)->toBe('D')
        ->and($response->first()->address)->toBe('Ramalleira, 5')
        ->and($response->first()->postalCode)->toBe('36140');
});

it('gets availability for single isbn', function () {
    $xmlBody = <<<XML
        <?xml version="1.0" encoding="utf-8"?>
        <DISPONIBILIDAD>
            <LIBRO>
                <ISBN>978-84-8332-838-5</ISBN>
                <ID_SINLI_ASOCIADO>LIB00460</ID_SINLI_ASOCIADO>
                <NOMBREA_ASOCIADO>Arnoia Distribucións</NOMBREA_ASOCIADO>
                <TIPO_ASOCIADO>D</TIPO_ASOCIADO>
                <DIRECCION>Ramalleira, 5</DIRECCION>
                <CODIGO_POSTAL>36140</CODIGO_POSTAL>
                <LOCALIDAD>Vilaboa</LOCALIDAD>
                <PROVINCIA>Pontevedra</PROVINCIA>
                <PERSONA_CONTACTO>Alberto Pérez Abreu</PERSONA_CONTACTO>
                <TELEFONO>986679007</TELEFONO>
                <EMAIL>alberto@arnoia.com</EMAIL>
                <WEB>www.arnoia.com</WEB>
                <PAIS_ISO>ES</PAIS_ISO>
                <FECHA_COMUNICACION>20081017</FECHA_COMUNICACION>
            </LIBRO>
            <LIBRO>
                <ISBN>978-84-8332-838-5</ISBN>
                <ID_SINLI_ASOCIADO>LIB00087</ID_SINLI_ASOCIADO>
                <NOMBREA_ASOCIADO>Librería Rayuela General</NOMBREA_ASOCIADO>
                <TIPO_ASOCIADO>L</TIPO_ASOCIADO>
                <DIRECCION>Cl. Cárcer, 1</DIRECCION>
                <CODIGO_POSTAL>29008</CODIGO_POSTAL>
                <LOCALIDAD>Málaga</LOCALIDAD>
                <PROVINCIA>Málaga</PROVINCIA>
                <PERSONA_CONTACTO>Juan Manuel Cruz</PERSONA_CONTACTO>
                <TELEFONO>952 219697</TELEFONO>
                <EMAIL>rayuela@libreriarayuela.com</EMAIL>
                <WEB>www.libreriarayuela.com</WEB>
                <PAIS_ISO>ES</PAIS_ISO>
                <FECHA_COMUNICACION>20081019</FECHA_COMUNICACION>
            </LIBRO>
        </DISPONIBILIDAD>
        XML;

    $httpClient = \mockHttpClient($xmlBody);
    $client = new Client($httpClient, 'user', 'password');

    $response = $client->getAvailability('978-84-8332-838-5');

    expect($response)
        ->toBeInstanceOf(AvailabilityCollection::class)
        ->toHaveCount(2);
});

it('gets book for isbn', function () {
    $xmlBody = <<<XML
        <?xml version="1.0" encoding="utf-8"?>
        <FICHALIBROS>
            <LIBRO>
                <ISBN>978-84-932550-3-9</ISBN>
                <EAN>9788423341009</EAN>
                <ISBN_FACTURACION></ISBN_FACTURACION>
                <ISBN_OBRA_COMPLETA></ISBN_OBRA_COMPLETA>
                <ISBN_TOMO_COMPLETO></ISBN_TOMO_COMPLETO>
                <ISBN_FASCICULO></ISBN_FASCICULO>
                <ISBN_REFERENCIA></ISBN_REFERENCIA>
                <TITULO></TITULO>
                <SUBTITULO></SUBTITULO>
                <PAIS_PUBLICACION>ES</PAIS_PUBLICACION>
                <ISBN_EDITORIAL></ISBN_EDITORIAL>
                <NOMBRE_EDITORIAL></NOMBRE_EDITORIAL>
                <ENCUADERNACION>03</ENCUADERNACION>
                <LENGUA_PUBLICACION>spa</LENGUA_PUBLICACION>
                <NUMERO_EDICION>01</NUMERO_EDICION>
                <FECHA_PUBLICACION></FECHA_PUBLICACION>
                <NUMERO_PAGINAS>0752</NUMERO_PAGINAS>
                <ANCHO></ANCHO>
                <ALTO></ALTO>
                <CDU></CDU>
                <IBIC></IBIC>
                <DESCRIPTORES></DESCRIPTORES>
                <SITUACION>0</SITUACION>
                <TIPO_PRODUCTO>00</TIPO_PRODUCTO>
                <PRECIO_SIN_IVA></PRECIO_SIN_IVA>
                <PRECIO_CON_IVA></PRECIO_CON_IVA>
                <COLECCION></COLECCION>
                <NUMERO_COLECCION></NUMERO_COLECCION>
                <NUMERO_VOLUMEN></NUMERO_VOLUMEN>
                <URL></URL>
                <ILUSTRADOR_CUBIERTA></ILUSTRADOR_CUBIERTA>
                <ILUSTRADOR_INTERIOR></ILUSTRADOR_INTERIOR>
                <NUMERO_ILUSTRACIONES_COLOR></NUMERO_ILUSTRACIONES_COLOR>
                <TRADUCTOR></TRADUCTOR>
                <IDIOMA_ORIGINAL>spa</IDIOMA_ORIGINAL>
                <GROSOR></GROSOR>
                <PESO></PESO>
                <AUDIENCIA></AUDIENCIA>
                <NIVEL_LECTURA>0</NIVEL_LECTURA>
                <NIVEL>0</NIVEL>
                <CURSO></CURSO>
                <ASIGNATURA></ASIGNATURA>
                <AUTONOMIA></AUTONOMIA>
                <RESUMEN></RESUMEN>
                <PORTADA>
                    <IMAGEN_PORTADA></IMAGEN_PORTADA>
                    <FORMATO_PORTADA></FORMATO_PORTADA>
                </PORTADA>
                <AUTORES>
                    <AUTOR>
                        <NOMBRE></NOMBRE>
                        <BIOGRAFIA></BIOGRAFIA>
                    </AUTOR>
                </AUTORES>
            </LIBRO>
        </FICHALIBROS>
        XML;

    $httpClient = \mockHttpClient($xmlBody);
    $client = new Client($httpClient, 'user', 'password');

    $response = $client->getBookByIsbn('978-84-932550-3-9');

    expect($response)
        ->toBeInstanceOf(BookCollection::class)
        ->toHaveCount(1)
        ->and($response->first())->toBeInstanceOf(Book::class)
        ->and($response->first()->isbn)->toBe('978-84-932550-3-9')
        ->and($response->first()->ean)->toBe('9788423341009');
});

it('throws exception with too many isbns', function () {
    $httpClient = \mockHttpClient('');
    $client = new Client($httpClient, 'user', 'password');

    $client->getAvailability([
        'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA',
        'BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB',
        'CCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCC',
        'DDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDD',
        'EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE',
        'FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF',
    ]);
})->throws(TooManyIsbnsException::class);

it('throws exception with invalid credentials', function () {
    $xmlBody = <<<XML
        <?xml version="1.0" encoding="utf-8"?>
        <DISPONIBILIDAD>
            <ERRORES>
                <ERROR>
                    <CODIGO>2</CODIGO>
                    <DESCRIPCION>USUARIO O CONTRASEÑA NO VÁLIDA</DESCRIPCION>
                </ERROR>
            </ERRORES>
        </DISPONIBILIDAD>
        XML;

    $httpClient = \mockHttpClient($xmlBody);
    $client = new Client($httpClient, 'user', 'invalid');

    $client->getAvailability('invalid');
})->throws(exception: CegalApiException::class, exceptionMessage: 'Invalid credentials', exceptionCode: 2);
