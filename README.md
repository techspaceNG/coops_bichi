# FCET Bichi Staff Multipurpose Cooperative Society Portal

A web-based portal for managing the FCET Bichi Staff Multipurpose Cooperative Society operations including loan management, household purchase management, and savings tracking.

## Technology Stack
- PHP 8.1+
- MySQL
- HTML5
- Tailwind CSS
- JavaScript

## Installation & Setup
1. Clone the repository to your local machine
2. Set up a web server (Apache/Nginx) with PHP support
3. Import the database schema from `app/config/database.sql`
4. Copy `.env.example` to `.env` and configure your environment variables
5. Access the portal through your web browser

## Environment Configuration
The application uses environment variables for configuration management:

### Database Configuration
- `DB_HOST`: Database host (default: localhost)
- `DB_NAME`: Database name (default: coops_bichi)
- `DB_USER`: Database username (default: root)
- `DB_PASS`: Database password
- `DB_CHARSET`: Database charset (default: utf8mb4)

### Application Configuration
- `APP_NAME`: Application name
- `APP_URL`: Base URL for the application
- `APP_ENV`: Environment (development, testing, production)
- `APP_DEBUG`: Enable debugging (true/false)

### Mail Configuration
- `MAIL_HOST`: SMTP host
- `MAIL_PORT`: SMTP port
- `MAIL_USERNAME`: SMTP username
- `MAIL_PASSWORD`: SMTP password
- `MAIL_ENCRYPTION`: Encryption method (tls, ssl)
- `MAIL_FROM_ADDRESS`: Default from email address
- `MAIL_FROM_NAME`: Default from name

## Features
- Multi-role user system (Superadmin, Admin, Member)
- Loan management and application
- Household purchase management (with 5% admin charge)
- Savings tracking
- Transaction history
- Bulk data upload functionality
- Audit logging
- Responsive design for mobile, tablet, and desktop

## Directory Structure
```
/COOPS_BICHI/
│
├── /public/               # Publicly accessible files
│   ├── /css/              # CSS stylesheets
│   ├── /js/               # JavaScript files
│   └── /images/           # Image assets
│
├── /app/                  # Application logic
│   ├── /controllers/      # Controller files
│   ├── /models/           # Database models
│   ├── /views/            # HTML/PHP view templates
│   ├── /helpers/          # Utility functions
│   └── /config/           # Configuration files
│
├── /logs/                 # Log files for audit and error logging
├── /uploads/              # Folder for bulk data upload files
└── README.md              # Project documentation
```

## User Roles
- **Superadmin**: Full system control, user management, and data management
- **Admin**: View records, access dashboards, and audit logs
- **Member**: View personal financial details, apply for loans, and download reports

## Security Features
- Password hashing with bcrypt
- Account lockout mechanism
- Secure session management
- Input validation and sanitization
- Environment-based configuration
- Secure credential management

## Important Notes
- Never commit the `.env` file to version control
- Always backup the database before major updates
- Regular security audits are recommended
- Keep PHP and all dependencies up to date 