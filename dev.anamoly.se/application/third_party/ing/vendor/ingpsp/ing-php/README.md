## Requirements

* PHP 5.6 or later.

## Installation

```php
include_once 'ing-php/vendor/autoload.php';
```

## Getting started

First create a new API client with your API key and ING product:

```php
use \GingerPayments\Payment\Ginger;

$client = Ginger::createClient('ing-api-key', 'ing_product');
```

## Full documentation
https://github.com/ingpsp/ing-php
