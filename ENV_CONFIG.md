# Environment Configuration

This application now uses environment variables for configuration management. The main benefits are:

1. **Security**: Sensitive credentials are no longer hardcoded in the source code
2. **Flexibility**: Different environments (development, testing, production) can use different configurations
3. **Maintainability**: Configuration changes don't require code changes

## Setup

1. Copy the `.env.example` file to `.env` in the root directory of the project
2. Modify the values in `.env` to match your environment

```bash
cp .env.example .env
```

## Available Configuration Options

### Database
- `DB_HOST`: Database host (default: localhost)
- `DB_NAME`: Database name (default: coops_bichi)
- `DB_USER`: Database username (default: root)
- `DB_PASS`: Database password (default: empty)
- `DB_CHARSET`: Database charset (default: utf8mb4)

### Application
- `APP_NAME`: Application name
- `APP_URL`: Base URL for the application
- `APP_ENV`: Environment (development, testing, production)
- `APP_DEBUG`: Enable debugging (true/false)

### Mail
- `MAIL_HOST`: SMTP host
- `MAIL_PORT`: SMTP port
- `MAIL_USERNAME`: SMTP username
- `MAIL_PASSWORD`: SMTP password
- `MAIL_ENCRYPTION`: Encryption method (tls, ssl)
- `MAIL_FROM_ADDRESS`: Default from email address
- `MAIL_FROM_NAME`: Default from name

## Usage in Code

To access environment variables in your code, use the `Environment` helper:

```php
use App\Helpers\Environment;

// Get a value with a default fallback
$dbHost = Environment::get('DB_HOST', 'localhost');

// Check if a variable exists
if (Environment::has('APP_DEBUG')) {
    // Enable debugging
}

// Get all environment variables
$allVars = Environment::all();
```

## Security Considerations

- **Never commit the `.env` file to version control**
- Add `.env` to your `.gitignore` file
- Provide an `.env.example` file with default/placeholder values
- In production, consider using actual environment variables instead of a `.env` file 