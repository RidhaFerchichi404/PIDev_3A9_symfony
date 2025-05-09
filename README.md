# Gym Management System

A comprehensive web-based gym management system built with Symfony 6.4, designed to streamline gym operations and enhance member experience.

## Features

- User Authentication and Authorization
- Member Management
- Class Scheduling
- Equipment Tracking
- Payment Processing
- QR Code Generation for Members
- Google Charts Integration for Analytics
- PDF Generation for Reports
- reCAPTCHA Integration for Security
- SMS Notifications via Twilio
- Email Notifications via SendGrid

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL/MariaDB
- Symfony CLI
- Node.js and npm (for frontend assets)

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd [project-directory]
```

2. Install PHP dependencies:
```bash
composer install
```

3. Configure your environment:
```bash
cp .env .env.local
```
Edit `.env.local` and set your database credentials and other environment variables.

4. Create the database:
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. Install frontend assets:
```bash
npm install
npm run build
```

6. Start the development server:
```bash
symfony server:start
```

## Usage

1. Access the application at `http://localhost:8000`
2. Log in with your credentials
3. Navigate through the dashboard to access different features:
   - Member Management
   - Class Scheduling
   - Equipment Management
   - Reports and Analytics
   - Payment Processing

## Security Features

- reCAPTCHA integration for form submissions
- Secure password hashing
- CSRF protection
- XSS prevention
- Input validation

## Technologies Used

- Symfony 6.4
- Doctrine ORM
- Twig Templates
- Bootstrap (for frontend)
- Google Charts
- Twilio (for SMS)
- SendGrid (for emails)
- DomPDF (for PDF generation)
- Endroid QR Code

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is proprietary software. All rights reserved.

## Support

For support, please contact the development team or create an issue in the repository. 
