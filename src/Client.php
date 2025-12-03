<?php

namespace NumaxLab\Cegal;

use NumaxLab\Cegal\Dto\Collection;
use NumaxLab\Cegal\Exceptions\TooManyIsbnsException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client
{
    public const API_URL = 'https://www.cegalenred.com/peticiones/';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $username,
        private readonly string $password,
    ) {}

    public static function create(string $username, string $password): self
    {
        return new self(
            HttpClient::create([
                'base_uri' => self::API_URL,
            ]),
            $username,
            $password,
        );
    }

    public function getBookByIsbn(string|array $isbns): Collection
    {
        return $this->sendRequest('fichalibro.xml.php', [
            'ISBN' => $this->prepareIsbnParam($isbns),
            'TIPOFICHA' => 'C',
            'version_sinli' => '07',
            'formato' => 'XML',
        ]);
    }

    private function sendRequest(string $uri, array $queryParams = []): Collection
    {
        $response = $this->httpClient->request('GET', $uri, [
            'query' => array_merge($queryParams, [
                'USUARIO' => $this->username,
                'CLAVE' => $this->password,
            ]),
        ]);

        $xmlString = $response->getContent();

        $parser = new Parser($xmlString);

        /** @var Collection<int, mixed> $collection */
        $collection = $parser->parse();

        return $collection;
    }

    protected function prepareIsbnParam(array|string $isbns): string
    {
        $isbnParam = is_array($isbns) ? implode('|', $isbns) : $isbns;

        if (strlen($isbnParam) > 576) {
            throw new TooManyIsbnsException(
                "The ISBNs list can't be longer than 576 characters (current length: ".strlen($isbnParam).")",
            );
        }
        return $isbnParam;
    }

    public function getAvailability(string|array $isbns): Collection
    {
        return $this->sendRequest('disponibilidad.xml.php', [
            'ISBN' => $this->prepareIsbnParam($isbns),
        ]);
    }
}
