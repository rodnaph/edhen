
# Edhen - EDN to PHP

A simple parser to decode [EDN](https://github.com/edn-format/edn) to [PHP](http://www.php.net) data structures.

## Usage

The interface is via some static functions on the _Edhen_ class.

```php
$data = Edhen::decode('(1 :foo [2])');
```

## Data Type Translations

| EDN               | PHP     |
| ----------------- | ------- |
| nil               | null    |
| true              | true    |
| false             | false   |
| strings           | string  |
| characters        | string  |
| symbols           | string  |
| keywords          | string  |
| integer           | integer |
| floating-point    | double  |
| lists             | array   |
| vectors           | array   |
| maps              | array   |
| sets              | array   |

## Unsupported

Currently not supported are:

 * [Tagged elements](https://github.com/edn-format/edn#tagged-elements)
 * [Comments](https://github.com/edn-format/edn#comments)
 * [Discard](https://github.com/edn-format/edn#discard)

## Installation

Edhen can be installed via Composer.

