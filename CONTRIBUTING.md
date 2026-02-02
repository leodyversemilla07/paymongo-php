# Contributing to PayMongo PHP SDK

Thank you for considering contributing to the PayMongo PHP SDK! This document outlines the guidelines for contributing to this project.

## Code of Conduct

By participating in this project, you agree to maintain a respectful and inclusive environment for everyone.

## How Can I Contribute?

### Reporting Bugs

Before creating a bug report, please check existing issues to avoid duplicates. When creating a bug report, include:

- A clear and descriptive title
- Steps to reproduce the issue
- Expected behavior vs actual behavior
- PHP version and environment details
- Code samples if applicable

### Suggesting Features

Feature requests are welcome. Please provide:

- A clear description of the feature
- The use case or problem it solves
- Any relevant examples or references

### Pull Requests

1. Fork the repository
2. Create a feature branch from `main`:
   ```bash
   git checkout -b feature/your-feature-name
   ```
3. Make your changes
4. Write or update tests as needed
5. Ensure all tests pass:
   ```bash
   composer test
   ```
6. Commit your changes with a descriptive message:
   ```bash
   git commit -m "Add: description of your changes"
   ```
7. Push to your fork:
   ```bash
   git push origin feature/your-feature-name
   ```
8. Open a Pull Request

## Development Setup

### Requirements

- PHP 8.2 or higher
- Composer

### Installation

```bash
git clone https://github.com/leodyversemilla07/paymongo-php.git
cd paymongo-php
composer install
```

### Running Tests

```bash
# Run all tests
composer test

# Run tests with coverage
vendor/bin/phpunit --coverage-text
```

## Coding Standards

### PHP Style Guide

- Follow PSR-12 coding standards
- Use strict types: `declare(strict_types=1);`
- Add PHPDoc blocks for all public methods
- Use meaningful variable and method names

### Example

```php
<?php

declare(strict_types=1);

namespace Paymongo\Services;

/**
 * Service for managing resources.
 */
class ExampleService extends BaseService
{
    /**
     * Create a new resource.
     *
     * @param array<string, mixed> $params
     * @return Resource
     */
    public function create(array $params): Resource
    {
        // Implementation
    }
}
```

### Commit Messages

Use clear and descriptive commit messages:

- `Add: new feature description`
- `Fix: bug description`
- `Update: what was updated`
- `Remove: what was removed`
- `Refactor: what was refactored`

## Project Structure

```
paymongo-php/
├── src/
│   ├── Entities/       # API resource entities
│   ├── Exceptions/     # Custom exceptions
│   ├── Services/       # API service classes
│   ├── HttpClient.php  # HTTP client
│   └── PaymongoClient.php  # Main client
├── tests/
│   ├── fixtures/       # Test fixtures
│   ├── Support/        # Test helpers
│   └── *Test.php       # Test files
├── docs/               # Documentation
└── ...
```

## Adding a New Service

1. Create the entity in `src/Entities/`:
   ```php
   <?php
   
   declare(strict_types=1);
   
   namespace Paymongo\Entities;
   
   use Paymongo\ApiResource;
   
   class NewEntity extends BaseEntity
   {
       public string $name;
       
       public function __construct(ApiResource $apiResource)
       {
           $this->id = $apiResource->id;
           $this->name = $apiResource->name;
       }
   }
   ```

2. Create the service in `src/Services/`:
   ```php
   <?php
   
   declare(strict_types=1);
   
   namespace Paymongo\Services;
   
   use Paymongo\Entities\NewEntity;
   
   class NewEntityService extends BaseService
   {
       private const URI = '/new_entities';
       
       public function create(array $params): NewEntity
       {
           $apiResource = $this->httpClient->request([
               'method' => 'POST',
               'url'    => $this->buildUrl(self::URI),
               'params' => $params
           ]);
           
           return new NewEntity($apiResource);
       }
   }
   ```

3. Register in `ServiceFactory.php`:
   ```php
   'newEntities' => NewEntityService::class,
   ```

4. Add tests in `tests/`:
   ```php
   <?php
   
   declare(strict_types=1);
   
   use PHPUnit\Framework\TestCase;
   
   final class NewEntityServiceTest extends TestCase
   {
       public function testCreateNewEntityBuildsRequest(): void
       {
           // Test implementation
       }
   }
   ```

## Questions?

If you have questions, feel free to open an issue or start a discussion.

## License

By contributing, you agree that your contributions will be licensed under the MIT License.
