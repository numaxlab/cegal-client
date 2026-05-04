# Cegal API Client

Cliente PHP para la
API [Cegal en Red](https://www.cegal.es/que-hacemos/proyectos-tecnologicos-y-de-innovacion/cegal-en-red/que-es-cegal-en-red/)
que ofrece [Cegal](https://www.cegal.es/).
La documentación completa de los endpoints disponibles puede consultarse
en [su documentación](https://www.cegal.es/wp-content/uploads/2023/06/ESPECIFICACIONES_PETICIONES_SEPTIEMBRE_2019_V4.00.pdf).

## Instalación

Puedes instalar el paquete a través de Composer:

```bash
composer require numaxlab/cegal-client
```

## Uso

Para usar el cliente, necesitas disponer de credenciales de acceso para [Cegal en red](https://cegalenred.com/).

```php
use NumaxLab\Cegal\Client;

$client = Client::create('tu-usuario', 'tu-contraseña');
```

### Obtener disponibilidad de libros por ISBN

Puedes obtener la disponibilidad en asociados de Cegal para uno o varios ISBNs.

```php
$availabilityCollection = $client->getAvailability('978-84-9865-535-7');
$availability = $availabilityCollection->first();

echo $availability->sinliId;
echo $availability->name;
echo $availability->isDistributor();
echo $availability->isBookshop();
```

### Obtener información de un libro por ISBN

Puedes obtener la información de un libro (o de varios) a partir de su ISBN.

```php
// Para un único ISBN
$bookCollection = $client->getBookByIsbn('978-84-9865-535-7');
$book = $bookCollection->first();

echo $book->title;

// Para múltiples ISBNs
$bookCollection = $client->getBookByIsbn([
    '978-84-9865-535-7',
    '978-84-9182-325-4'
]);

foreach ($bookCollection as $book) {
    echo $book->title . "\n";
}
```

## Testing

Para ejecutar los tests, usa el siguiente comando:

```bash
composer test
```

## Licencia

Este proyecto está bajo la licencia MIT. Para más detalles, consulta el archivo LICENSE.
