# FoodFusion Installation Guide

## Quick Setup

FoodFusion now includes a streamlined setup process that handles everything automatically.

### Step 1: Prerequisites

Make sure you have:
- **PHP 7.4 or higher**
- **MySQL/MariaDB server**
- **Web server** (Apache, Nginx, or built-in PHP server)
- **Required PHP extensions**: MySQLi, PDO, GD

### Step 2: Download & Extract

1. Download or clone the FoodFusion project
2. Extract to your web server directory (e.g., `htdocs`, `www`, or `public_html`)

### Step 3: Run Setup

1. Open your web browser
2. Navigate to: `http://your-domain.com/foodfusion/setup.php`
3. Follow the 4-step installation wizard:
   - **Step 1**: System requirements check
   - **Step 2**: Database configuration
   - **Step 3**: Database installation
   - **Step 4**: Installation complete

### Step 4: Access Your Site

After successful installation:
- Visit your homepage: `http://your-domain.com/foodfusion/`
- Create user accounts via: `http://your-domain.com/foodfusion/auth/register.php`

## What the Setup Does

The setup script will:

1. ✅ **Check system requirements** - Verifies PHP version and extensions
2. ✅ **Test database connection** - Ensures MySQL connectivity
3. ✅ **Create database** - Automatically creates the database if it doesn't exist
4. ✅ **Install tables** - Creates all required database tables
5. ✅ **Insert sample data** - Adds sample recipes and content
6. ✅ **Generate config file** - Creates the configuration file automatically
7. ✅ **Clean up old files** - Removes outdated installation files

## Fresh Installation

If you need to reinstall:

1. Delete the `setup.lock` file from the root directory
2. Run `setup.php` again
3. **Warning**: This will delete all existing data!

## Manual Configuration

If you prefer manual setup, you can:

1. Copy `includes/config.sample.php` to `includes/config.php`
2. Edit the database settings in `config.php`
3. Import `database/foodfusion_db.sql` into your MySQL database

## Troubleshooting

### Common Issues:

**"Requirements not met"**
- Install missing PHP extensions
- Check PHP version (must be 7.4+)
- Ensure directories are writable

**"Database connection failed"**
- Verify MySQL server is running
- Check database credentials
- Ensure database user has proper permissions

**"Permission denied"**
- Make sure `uploads/` and `resources/` directories are writable
- Check file permissions (755 for directories, 644 for files)

### File Permissions

```bash
chmod 755 uploads/
chmod 755 resources/
chmod 644 includes/config.php
```

## Features Included

After installation, you'll have access to:

- 🍽️ **Recipe browsing and sharing**
- 👥 **Community posts and interactions**
- 📚 **Educational resources**
- 🎥 **Culinary videos**
- 📞 **Contact forms**
- 🔐 **User authentication**
- ⭐ **Recipe ratings**
- 💬 **Comments and likes**

## Default Sample Data

The installation includes:
- 11 sample recipes across various cuisines
- Sample community posts
- Educational resources
- User authentication system

## Security Notes

- The setup script creates a `setup.lock` file to prevent re-installation
- Database passwords are properly hashed
- SQL injection protection is built-in
- Session security is configured

## Support

If you encounter issues:
1. Check the troubleshooting section above
2. Verify your server meets all requirements
3. Ensure proper file permissions
4. Check PHP error logs for detailed error messages

---

**Enjoy your FoodFusion website! 🎉**