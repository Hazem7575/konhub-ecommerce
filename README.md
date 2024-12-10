# Konhub Lido

Convert Lido JS JSON designs to responsive HTML layouts.

## Installation

You can install the package via composer:

```bash
composer require konhub/lido
```

## Usage

```php
use Konhub\Lido\Facades\LidoConverter;

$json = File::get('design.json');
$result = LidoConverter::convert($json);

// Access the converted HTML, CSS and JS
$html = $result['html'];
$cssUrl = $result['css'];
$jsUrl = $result['js'];
$sizes = $result['sizes'];
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.