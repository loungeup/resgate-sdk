# Resgate SDK

## Getting Started

### Installation

Resgate SDK requires PHP >= 8.1.

```shell
composer require resgate-sdk/resgate
```

### Basic Usage

First thing to do, add message driver to config file service

```php
...
    'message' => [
        'current' => 'resgate',
        'drivers' => [
            'resgate' => 'LoungeUp\Resgate\ResgateMessageDriver',
            'pure_nats' => 'class/message'
        ]
    ],
...
```

Update all Controllers in the service to extend AbstractNatsController

```php
class SubscriptionsController extends AbstractNatsController
{
...
```

## Request Object

```php
$request->get("name"): string // get event subject by name
$request->all(): array // get all events subject

$request->getFiltersString(): string // get all query in the body ex: "filter=model&page=1
$request->getFilter("name"): string // get query in the body by name
$request->getFilters(): array // get all query in the body
```

## Response Object

```php
// Code error
define("RES_NOTFOUND", "system.notFound");
define("RES_INVALIDPARAMS", "system.invalidParams");
define("RES_INVALIDQUERY", "system.invalidQuery");
define("RES_INTERNALERROR", "system.internalError");
define("RES_METHODNOTFOUND", "system.methodNotFound");
define("RES_ACCESSDENIED", "system.accessDenied");
define("RES_TIMEOUT", "system.timeout");
```
