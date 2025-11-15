# FoodFusion

A community-driven recipe sharing and culinary education platform.

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- The following PHP extensions:
  - mysqli
  - pdo
  - gd

## Installation

1. Clone or download this repository to your web server's directory
2. Make sure the following directories are writable by the web server:
   - uploads/
   - resources/
3. Navigate to `http://your-domain/foodfusion/install` in your web browser
4. Follow the installation wizard which will:
   - Check system requirements
   - Set up database configuration
   - Create necessary database tables
   - Import initial recipes and data
5. After installation is complete, you can access the site at `http://your-domain/foodfusion`

## Manual Installation

If you prefer to install manually:

1. Create a new MySQL database
2. Import the database schema from `database/schema.sql`
3. Copy `includes/config.sample.php` to `includes/config.php`
4. Edit `config.php` with your database credentials
5. Run `php database/seed.php` to import initial data

## Features

- Recipe sharing and browsing
- Community posts and discussions
- Culinary education resources
- User authentication system
- Recipe rating system
- Contact form
- Responsive design

## Security

- Password hashing with bcrypt
- Prepared statements for database queries
- XSS protection
- CSRF protection
- Input validation and sanitization

## License

This project is licensed under the MIT License - see the LICENSE file for details.