# php-hypothesis

[![CI](https://github.com/fr3on/php-hypothesis/actions/workflows/ci.yml/badge.svg)](https://github.com/fr3on/php-hypothesis/actions/workflows/ci.yml)
[![License](https://img.shields.io/github/license/fr3on/php-hypothesis)](https://github.com/fr3on/php-hypothesis/blob/main/LICENSE)

Property-based testing for PHP 8.4+, inspired by the Python `Hypothesis` library.

## Features

- **Property-Based Testing**: Define properties that should always be true, and let the library find counter-examples.
- **Smart Shrinking**: When a failure is found, the library automatically shrinks the input to the smallest possible failing case.
- **PHP 8.4+ Attributes**: Use the `#[Given]` attribute for clean, declarative test definitions.
- **PHPUnit Integration**: Seamlessly integrates with PHPUnit 11+.
- **Zero Runtime Dependencies**: Lightweight and fast.

## Installation

```bash
composer require fr3on/php-hypothesis
```

## Quick Start

```php
use Fr3on\Hypothesis\PHPUnit\PropertyTestCase;
use Fr3on\Hypothesis\Attribute\Given;
use Fr3on\Hypothesis\Shape\ListShape;
use Fr3on\Hypothesis\Shape\IntegerShape;

class SortingTest extends PropertyTestCase
{
    #[Given(new ListShape(new IntegerShape()))]
    public function prop_sort_is_idempotent(array $list): void
    {
        $sorted = $list;
        sort($sorted);
        
        $doubleSorted = $sorted;
        sort($doubleSorted);
        
        $this->assertSame($sorted, $doubleSorted);
    }
}
```

## How it Works

1. **Generation**: The library generates random data based on the "Shapes" you define (Integer, String, List, etc.).
2. **Execution**: Your test runs multiple times (default 100) with different inputs.
3. **Shrinking**: If a failure occurs, the library iteratively simplifies the input to find the "minimal reproducible case".
4. **Result**: You get a clear report of exactly what caused the property to fail.

## License

MIT
