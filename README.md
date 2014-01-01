
# Encode/Decode EDN in PHP

A tool to encode/decode between [EDN](https://github.com/edn-format/edn) and
[PHP](http://www.php.net) data structures.

### Note

When converting from EDN to PHP the conversion is lossy as the richness of 
datatypes supported by EDN is not available in PHP.  So a conversion from
EDN to PHP and back to EDN would not lose you data, but it would lose type
information.

## Usage

The interface is via some static functions on the _Edhen_ class.  To decode an
EDN element...

```php
$element = Edhen::decode('(1 :foo [2])');

// array(1, ':foo', array(2))
```

If you have EDN with multiple elements, you can use _decodeAll_

```php
$elements = Edhen::decodeAll(':foo :bar :baz');

// array(':foo', ':bar', ':baz')
```

Then for encoding use the _encode_ function, passing it the data to encode...

```php
$ednString = Edhen::encode(array(1, 2));

// '[1 2]'
```

## Data Type Translations

When decoding EDN to PHP...

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

When encoding PHP to EDN...

| PHP           | EDN     |
| ------------- | ------- |
| null          | nil     |
| boolean       | boolean |
| integer       | integer |
| double        | float   |
| array         | vector  |
| array (assoc) | hashmap |
| object        | hashmap |
| resource      | nil     |
| callable      | nil     |

The decision on if an array is to be converted to a vector or hashmap is done by 
checking its keys.  If any of the keys are non-numeric then a hashmap is used.

EDN is generated as a single string, no pretty-printing is currently supported.
Another tool should be used for this.

## Custom Tag Handlers

To implement your own [tag handlers](https://github.com/edn-format/edn#tagged-elements),
create a class which implements the [Edhen\TagHandler](src/Edhen/TagHandler.php)
interface and pass it in an array as the second argument to _decode_/_decodeAll_

```php
$myHandler = new MyCustomTagHandler();

$element = Edhen::decode($edn, array($myHandler));
```

You can see an [example in the tests](tests/EdhenTest.php#L31).

## Installation

Edhen can be installed via Composer.

