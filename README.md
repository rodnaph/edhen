
# Edhen - EDN to PHP

A simple parser to decode [EDN](https://github.com/edn-format/edn) to [PHP](http://www.php.net) data structures.

## Usage

The interface is via some static functions on the _Edhen_ class.

```php
$element = Edhen::decode('(1 :foo [2])');

// array(1, ':foo', array(2))
```

If you have EDN with multiple elements, you can use _decodeAll_

```php
$elements = Edhen::decodeAll(':foo :bar :baz');

// array(':foo', ':bar', ':baz')
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

### Builtin Tags

| EDN   | PHP      |
| ----- | -------- |
| inst  | DateTime |
| uuid  | string   |

## Custom Tag Handlers

To implement your own [tag handlers](https://github.com/edn-format/edn#tagged-elements),
create a class which implements the [Edhen\TagHandler](src/Edhen/TagHandler.php)
interface and pass it in an array as the second argument to _decode_/_decodeAll_

```php
$myHandler = new MyCustomTagHandler();

$element = Edhen::decode($edn, array($myHandler));
```

You can see an [example in the tests](tests/EdhenTest.php#31).

## Installation

Edhen can be installed via Composer.

