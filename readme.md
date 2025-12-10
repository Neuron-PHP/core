[![CI](https://github.com/Neuron-PHP/core/actions/workflows/ci.yml/badge.svg)](https://github.com/Neuron-PHP/core/actions)
[![codecov](https://codecov.io/gh/Neuron-PHP/core/branch/develop/graph/badge.svg)](https://codecov.io/gh/Neuron-PHP/core)
# Neuron-PHP Core

The foundational component of the Neuron PHP framework, providing essential utilities, string and array manipulation classes, and a comprehensive exception hierarchy for PHP 8.4+ applications.

## Table of Contents

- [Installation](#installation)
- [Quick Start](#quick-start)
- [Core Features](#core-features)
- [String Manipulation (NString)](#string-manipulation-nstring)
- [Array Manipulation (NArray)](#array-manipulation-narray)
- [Exception System](#exception-system)
- [Error Constants](#error-constants)
- [Testing](#testing)
- [Best Practices](#best-practices)
- [More Information](#more-information)

## Installation

### Requirements

- PHP 8.4 or higher
- Extensions: curl, json
- Composer

### Install via Composer

```bash
composer require neuron-php/core
```

## Quick Start

### String Manipulation

```php
use Neuron\Core\NString;

// Create a string object
$str = new NString('hello_world_example');

// Case conversions
echo $str->toPascalCase();       // 'HelloWorldExample'
echo $str->toCamelCase();        // 'helloWorldExample'
echo $str->toSnakeCase();        // 'hello_world_example'

// String extraction
echo $str->left(5);              // 'hello'
echo $str->right(7);             // 'example'
echo $str->mid(6, 10);           // 'world'
```

### Array Manipulation

```php
use Neuron\Core\NArray;

// Create an array object
$arr = new NArray(['apple', 'banana', 'cherry']);

// Safe element access with defaults
$first = $arr->getElement(0, 'default');     // 'apple'
$missing = $arr->getElement(10, 'none');     // 'none'

// Transformations with method chaining
$result = $arr->filter(fn($item) => strlen($item) > 5)
              ->map(fn($item) => ucfirst($item))
              ->sort();
```

## Core Features

- **String Utilities**: Object-oriented string manipulation with fluent interface
- **Array Utilities**: Enhanced array operations with safe access and transformations
- **Exception Hierarchy**: Comprehensive typed exceptions for better error handling
- **Error Constants**: PHP implementation of standard C error codes
- **Modern PHP 8.4+**: Property hooks, union types, and modern syntax
- **Type Safety**: Strongly typed interfaces and return types
- **Method Chaining**: Fluent interfaces for readable code

## String Manipulation (NString)

The `NString` class provides powerful string manipulation capabilities with an object-oriented approach.

### Basic Operations

```php
use Neuron\Core\NString;

$str = new NString('  Hello World  ');

// Length operations
echo $str->length();              // 15

// Trimming
echo $str->trim();                // 'Hello World'
```

### String Extraction

```php
$str = new NString('The quick brown fox');

// Position-based extraction
echo $str->left(9);               // 'The quick'
echo $str->right(3);              // 'fox'
echo $str->mid(4, 8);             // 'quick'
```

### Case Conversions

```php
$str = new NString('  Hello World  ');

echo $str->toUpper();             // '  HELLO WORLD  '
echo $str->toLower();             // '  hello world  '

$str = new NString('hello_world_example');

// Snake case to PascalCase and camelCase
echo $str->toPascalCase();       // 'HelloWorldExample'
echo $str->toCamelCase();        // 'helloWorldExample'

// PascalCase/camelCase to snake case
$camel = new NString('HelloWorldExample');
echo $camel->toSnakeCase();       // 'hello_world_example'

// Mixed case handling
$mixed = new NString('getUserID');
echo $mixed->toSnakeCase();       // 'get_user_id'
```

### String Formatting

```php
$str = new NString('example text');

// Quote handling
echo $str->quote();                // '"example text"'

$quoted = new NString('"quoted text"');
echo $quoted->deQuote();          // 'quoted text'
```

### Advanced Features

```php
// Property hooks (PHP 8.4+)
$str = new NString('test');
$str->value = 'new value';        // Uses setter hook
echo $str->value;                 // Uses getter hook

// Note: NString methods return strings, not NString objects,
// so method chaining is not directly supported
```

## Array Manipulation (NArray)

The `NArray` class provides comprehensive array manipulation with safe access and functional programming features.

### Basic Operations

```php
use Neuron\Core\NArray;

$arr = new NArray([1, 2, 3, 4, 5]);

// Array information
echo $arr->count();                // 5
echo $arr->isEmpty();              // false
echo $arr->isNotEmpty();          // true

// Element checks
echo $arr->contains(3);           // true
echo $arr->hasKey(2);             // true

// Get first and last elements
echo $arr->first();               // 1
echo $arr->last();                // 5

// Find index of element
echo $arr->indexOf(3);            // 2

// Remove element by value
$arr->remove(3);                  // Removes 3 from array
```

### Safe Element Access

```php
$arr = new NArray(['a' => 1, 'b' => 2, 'c' => 3]);

// Get element with default fallback
$value = $arr->getElement('a', 0);     // 1
$missing = $arr->getElement('d', -1);  // -1 (default)

// Check and get
if ($arr->hasKey('b')) {
    $value = $arr->getElement('b');
}
```

### Transformation Methods

```php
$arr = new NArray([1, 2, 3, 4, 5]);

// Map transformation
$doubled = $arr->map(fn($x) => $x * 2);
// Result: [2, 4, 6, 8, 10]

// Filter operation
$evens = $arr->filter(fn($x) => $x % 2 === 0);
// Result: [2, 4]

// Reduce operation
$sum = $arr->reduce(fn($carry, $item) => $carry + $item, 0);
// Result: 15

// Execute callback for each element
$arr->each(function($value, $key) {
    echo "[$key] => $value\n";
});
```

### Array Operations

```php
$arr1 = new NArray([1, 2, 3]);
$arr2 = new NArray([3, 4, 5]);

// Merge arrays
$merged = $arr1->merge($arr2);
// Result: [1, 2, 3, 3, 4, 5]

// Unique values
$unique = $merged->unique();
// Result: [1, 2, 3, 4, 5]

// Get keys and values
$keys = $arr1->keys();     // [0, 1, 2]
$values = $arr1->values();  // [1, 2, 3]

// Mathematical operations
$numbers = new NArray([1, 2, 3, 4, 5]);
echo $numbers->sum();      // 15
echo $numbers->avg();      // 3
echo $numbers->min();      // 1
echo $numbers->max();      // 5

// Convert to other formats
echo $numbers->toJson();   // "[1,2,3,4,5]"
echo $numbers->implode(', '); // "1, 2, 3, 4, 5"
$raw = $numbers->toArray(); // Get raw PHP array
```

### Collection Operations

```php
// Working with associative arrays
$users = new NArray([
    ['id' => 1, 'name' => 'Alice', 'age' => 30],
    ['id' => 2, 'name' => 'Bob', 'age' => 25],
    ['id' => 3, 'name' => 'Charlie', 'age' => 35]
]);

// Pluck values
$names = $users->pluck('name');
// Result: ['Alice', 'Bob', 'Charlie']

// Find by property
$user = $users->findBy('age', 25);
// Result: ['id' => 2, 'name' => 'Bob', 'age' => 25]

// Filter by property value
$youngUsers = $users->where('age', 25);
// Result: Array containing users with age = 25

// Find first matching element
$adult = $users->find(fn($u) => $u['age'] >= 30);
// Result: ['id' => 1, 'name' => 'Alice', 'age' => 30]
```

### Sorting and Ordering

```php
$arr = new NArray([3, 1, 4, 1, 5, 9]);

// Sort ascending
$sorted = $arr->sort();
// Result: [1, 1, 3, 4, 5, 9]

// Sort by keys
$arr = new NArray(['c' => 3, 'a' => 1, 'b' => 2]);
$sortedKeys = $arr->sortKeys();
// Result: ['a' => 1, 'b' => 2, 'c' => 3]

// Reverse array
$reversed = $arr->reverse();
```

### Array Slicing and Chunking

```php
$arr = new NArray([1, 2, 3, 4, 5, 6, 7, 8, 9]);

// Get slice (offset, length)
$slice = $arr->slice(2, 4);
// Result: [3, 4, 5, 6]

// Split into chunks
$chunks = $arr->chunk(3);
// Result: [[1, 2, 3], [4, 5, 6], [7, 8, 9]]
```

## Exception System

The core component provides a comprehensive exception hierarchy for consistent error handling across the framework.

### Base Exception

```php
use Neuron\Core\Exceptions\Base;

// All Neuron exceptions extend Base
class CustomException extends Base
{
    public function __construct($message = "")
    {
        parent::__construct($message, 0, null);
    }
}

try {
    throw new CustomException("Something went wrong");
} catch (Base $e) {
    // Handle any Neuron exception
    echo $e->getMessage();
}
```

### NotFound Exceptions

```php
use Neuron\Core\Exceptions\NotFound;
use Neuron\Core\Exceptions\PropertyNotFound;
use Neuron\Core\Exceptions\CommandNotFound;
use Neuron\Core\Exceptions\MapNotFound;

// Generic not found
throw new NotFound("Resource not found");

// Property not found on object
throw new PropertyNotFound("Property 'email' not found on User object");

// Command not found in CLI
throw new CommandNotFound("Command 'deploy' not found");

// Route map not found
throw new MapNotFound("Route '/api/users' not found");
```

### Validation Exception

```php
use Neuron\Core\Exceptions\Validation;

// Validation failure with details
$errors = [
    'email' => 'Invalid email format',
    'age' => 'Must be 18 or older'
];

throw new Validation("Validation failed", $errors);
```

### Method and Request Exceptions

```php
use Neuron\Core\Exceptions\MissingMethod;
use Neuron\Core\Exceptions\BadRequestMethod;

// Method missing on class
throw new MissingMethod("Method 'save' not found on class User");

// Invalid HTTP request method
throw new BadRequestMethod("Method DELETE not allowed for this endpoint");
```

### Route Parameter Exception

```php
use Neuron\Core\Exceptions\RouteParam;

// Missing or invalid route parameter
throw new RouteParam("Required parameter 'id' missing from route");
```

### Empty Action Parameter

```php
use Neuron\Core\Exceptions\EmptyActionParameter;

// Action called with empty required parameter
throw new EmptyActionParameter("Parameter 'userId' cannot be empty");
```

## Error Constants

The `H\Error` class provides PHP implementations of standard C error codes:

```php
use Neuron\Core\H\Error;

// File system errors
$code = Error::ENOENT;     // 2 - No such file or directory
$code = Error::EACCES;     // 13 - Permission denied
$code = Error::EEXIST;     // 17 - File exists

// Memory errors
$code = Error::ENOMEM;     // 12 - Out of memory

// I/O errors
$code = Error::EIO;        // 5 - I/O error
$code = Error::EBUSY;      // 16 - Device or resource busy
$code = Error::ENOSPC;     // 28 - No space left on device

// Example usage
function readFile($path) {
    if (!file_exists($path)) {
        return ['error' => Error::ENOENT, 'message' => 'File not found'];
    }
    if (!is_readable($path)) {
        return ['error' => Error::EACCES, 'message' => 'Permission denied'];
    }
    // Read file...
}
```

## Testing

### Running Tests

```bash
# Run all tests
./vendor/bin/phpunit tests

# Run with coverage
./vendor/bin/phpunit tests --coverage-text

# Run specific test
./vendor/bin/phpunit tests/NStringTest.php
```

### Writing Tests

```php
use PHPUnit\Framework\TestCase;
use Neuron\Core\NString;

class NStringTest extends TestCase
{
    public function testCamelCase(): void
    {
        $str = new NString('hello_world');
        $this->assertEquals('HelloWorld', $str->toCamelCase());
        $this->assertEquals('helloWorld', $str->toCamelCase(false));
    }

    public function testStringExtraction(): void
    {
        $str = new NString('Hello World');
        $this->assertEquals('Hello', $str->left(5));
        $this->assertEquals('World', $str->right(5));
        $this->assertEquals('llo W', $str->mid(2, 6));
    }
}
```

## Best Practices

### String Operations

```php
// Use NString for complex string manipulation
$email = new NString('  USER@EXAMPLE.COM  ');
$normalized = $email->trim()->toLower();

// Chain operations for readability
$slug = (new NString('Product Name 2024'))
    ->toLower()
    ->replace(' ', '-')
    ->replace('2024', '');
```

### Array Operations

```php
// Use NArray for safe array access
$config = new NArray($configData);
$dbHost = $config->getElement('host', 'localhost');

// Leverage functional programming
$activeUsers = (new NArray($users))
    ->filter(fn($u) => $u['active'])
    ->map(fn($u) => $u['email'])
    ->unique();
```

### Exception Handling

```php
// Use specific exceptions for clarity
try {
    $property = $object->getProperty('nonexistent');
} catch (PropertyNotFound $e) {
    // Handle missing property
    $property = $defaultValue;
} catch (Base $e) {
    // Handle other Neuron exceptions
    $logger->error($e->getMessage());
}
```

### Type Safety

```php
// Leverage PHP 8.4+ features
function processArray(NArray $data): NArray
{
    return $data->filter(fn($item) => $item !== null)
                ->map(fn($item) => processItem($item));
}
```

## Integration with Other Components

The Core component serves as the foundation for all other Neuron components:

```php
// Used by Validation component
use Neuron\Core\Exceptions\Validation;

class EmailValidator
{
    public function validate($value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new Validation("Invalid email format");
        }
    }
}

// Used by Data component
use Neuron\Core\NArray;

class DataFilter
{
    public function filterData(array $data): NArray
    {
        return (new NArray($data))
            ->filter(fn($item) => $item !== null)
            ->unique();
    }
}
```

## More Information

- **Neuron Framework**: [neuronphp.com](http://neuronphp.com)
- **GitHub**: [github.com/neuron-php/core](https://github.com/neuron-php/core)
- **Packagist**: [packagist.org/packages/neuron-php/core](https://packagist.org/packages/neuron-php/core)

## License

MIT License - see LICENSE file for details
