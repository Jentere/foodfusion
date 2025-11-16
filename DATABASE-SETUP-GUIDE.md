# 🗄️ Database Setup Guide for Production

## 🎯 Overview

This guide helps you configure the database for production deployment. FoodFusion works with any MySQL/MariaDB hosting, but database credentials differ between localhost and production.

## 🚀 Quick Setup (Recommended)

### Use the Automated Setup Wizard

The easiest way to configure your database:

1. **Upload all files** to your production server
2. **Visit**: `https://yourdomain.com/setup.php`
3. **Enter your database credentials** from hosting control panel
4. **Click "Install Database"**
5. **Done!** Configuration is automatic

This method:
- ✅ Tests connection before proceeding
- ✅ Creates database if needed
- ✅ Imports all tables and data
- ✅ Generates config.php automatically
- ✅ Validates everything works

---

## 🔧 Manual Configuration

If you prefer manual setup or need to troubleshoot:

### Step 1: Find Your Database Credentials

#### For Shared Hosting (cPanel/Plesk):

1. Log into your hosting control panel
2. Navigate to **MySQL Databases** or **Database Management**
3. Note your credentials:
   - **Host**: Usually `localhost` or `127.0.0.1`
   - **Database Name**: Your database name
   - **Username**: Your database username
   - **Password**: Your database password

#### For InfinityFree:

1. Log into InfinityFree control panel
2. Go to **MySQL Databases**
3. Find your database information:
   - **Host**: Format like `sql###.infinityfree.com`
   - **Database**: Format like `if0_12345678_foodfusion`
   - **Username**: Format like `if0_12345678`
   - **Password**: The password you set

#### For Cloud/VPS:

1. SSH into your server
2. Access MySQL:
   ```bash
   sudo mysql -u root -p
   ```
3. Create database and user:
   ```sql
   CREATE DATABASE foodfusion_db;
   CREATE USER 'foodfusion'@'localhost' IDENTIFIED BY 'secure_password';
   GRANT ALL PRIVILEGES ON foodfusion_db.* TO 'foodfusion'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```

---

### Step 2: Test Database Connection

Before configuring, test your credentials:

#### Method 1: Use test-config.php
1. Upload files to server
2. Visit: `https://yourdomain.com/test-config.php`
3. Check database connection status

#### Method 2: Use MySQL Command Line
```bash
mysql -h localhost -u username -p database_name
# Enter password when prompted
# If successful, connection works!
```

#### Method 3: Use phpMyAdmin
1. Access phpMyAdmin from hosting control panel
2. Try logging in with your credentials
3. If successful, credentials are correct

---

### Step 3: Configure Database

#### Option A: Use Setup Wizard (Recommended)
1. Visit: `https://yourdomain.com/setup.php`
2. Enter credentials in Step 2
3. Click "Test Connection"
4. If successful, click "Install Database"
5. Done! Config file created automatically

#### Option B: Manual Configuration
1. Edit `includes/config.php` on server:

```php
<?php
// Include path helper system
require_once(__DIR__ . '/paths.php');

// Database Configuration - UPDATE THESE VALUES
define('DB_HOST', 'localhost');              // Your database host
define('DB_USER', 'your_username');          // Your database username
define('DB_PASS', 'your_password');          // Your database password
define('DB_NAME', 'your_database_name');     // Your database name

// Site Configuration (auto-configured by paths.php)
define('UPLOADS_DIR', assetPath('uploads'));
define('RESOURCES_DIR', assetPath('resources'));

// Security Configuration
define('HASH_COST', 10);
define('SESSION_LIFETIME', 3600);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900);

// Email Configuration (optional)
define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('MAIL_FROM', 'noreply@yourdomain.com');
?>
```

2. Save the file
3. Import database:
   - Via phpMyAdmin: Import `database/foodfusion_db.sql`
   - Via command line: `mysql -u username -p database_name < database/foodfusion_db.sql`

---

## 🐛 Common Issues & Solutions

### Issue 1: "No such file or directory"

**Cause**: MySQL socket file not found (common on some hosts)

**Solutions**:
```php
// Try these in order:
define('DB_HOST', '127.0.0.1');              // Option 1
define('DB_HOST', 'localhost:/tmp/mysql.sock'); // Option 2
define('DB_HOST', 'localhost:3306');         // Option 3
```

---

### Issue 2: "Access denied for user"

**Cause**: Wrong username or password

**Solutions**:
1. Double-check credentials in hosting control panel
2. Ensure no extra spaces in username/password
3. Try resetting database password
4. Verify user has privileges:
   ```sql
   SHOW GRANTS FOR 'username'@'localhost';
   ```

---

### Issue 3: "Unknown database"

**Cause**: Database doesn't exist or wrong name

**Solutions**:
1. Verify database name in control panel
2. Check for typos (case-sensitive on Linux)
3. Create database if missing:
   ```sql
   CREATE DATABASE foodfusion_db;
   ```
4. For InfinityFree, use full name with prefix:
   ```php
   define('DB_NAME', 'if0_12345678_foodfusion');
   ```

---

### Issue 4: "Can't connect to MySQL server"

**Cause**: Wrong host or MySQL not running

**Solutions**:
1. Verify MySQL service is running:
   ```bash
   sudo systemctl status mysql
   ```
2. Check if using correct host:
   - Shared hosting: Usually `localhost`
   - InfinityFree: `sql###.infinityfree.com`
   - Remote: Full hostname or IP
3. Check firewall rules (VPS/Cloud)
4. Verify port (default: 3306)

---

### Issue 5: "Too many connections"

**Cause**: Database connection limit reached

**Solutions**:
1. Close unused connections
2. Optimize database queries
3. Use connection pooling
4. Contact hosting support to increase limit

---

## 📊 Environment-Specific Configurations

### Localhost (XAMPP/WAMP/MAMP)
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Usually empty
define('DB_NAME', 'foodfusion_db');
```

### Shared Hosting (cPanel)
```php
define('DB_HOST', 'localhost');  // or '127.0.0.1'
define('DB_USER', 'cpanel_username_dbuser');
define('DB_PASS', 'your_secure_password');
define('DB_NAME', 'cpanel_username_foodfusion');
```

### InfinityFree
```php
define('DB_HOST', 'sql###.infinityfree.com');
define('DB_USER', 'if0_12345678');
define('DB_PASS', 'your_password');
define('DB_NAME', 'if0_12345678_foodfusion');
```

### Cloud/VPS (Ubuntu/Debian)
```php
define('DB_HOST', 'localhost');  // or '127.0.0.1'
define('DB_USER', 'foodfusion');
define('DB_PASS', 'secure_random_password');
define('DB_NAME', 'foodfusion_db');
```

### Remote Database
```php
define('DB_HOST', 'db.example.com:3306');
define('DB_USER', 'remote_user');
define('DB_PASS', 'remote_password');
define('DB_NAME', 'foodfusion_db');
```

---

## 🔐 Security Best Practices

### 1. Use Strong Passwords
```php
// Bad
define('DB_PASS', 'password123');

// Good
define('DB_PASS', 'xK9#mP2$vL8@qR5!');
```

### 2. Limit Database User Privileges
```sql
-- Don't give unnecessary privileges
GRANT SELECT, INSERT, UPDATE, DELETE ON foodfusion_db.* TO 'user'@'localhost';

-- Avoid using root user in production
```

### 3. Protect config.php
```bash
# Set restrictive permissions
chmod 644 includes/config.php

# Ensure not accessible via web
# (Already protected by .htaccess)
```

### 4. Don't Commit Credentials
```bash
# Add to .gitignore
echo "includes/config.php" >> .gitignore

# Use config.sample.php as template
cp includes/config.php includes/config.sample.php
# Remove real credentials from sample
```

### 5. Use Environment Variables (Advanced)
```php
// For cloud deployments
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'foodfusion_db');
```

---

## ✅ Verification Steps

After configuration, verify everything works:

### 1. Test Connection
Visit: `https://yourdomain.com/test-config.php`
- Should show "Database: Connected ✓"

### 2. Test Homepage
Visit: `https://yourdomain.com/`
- Should load without database errors
- Should display sample recipes

### 3. Test Registration
Visit: `https://yourdomain.com/auth/register.php`
- Create a test account
- Should successfully save to database

### 4. Check Error Logs
```bash
# Check for database errors
tail -f /var/log/apache2/error.log  # Apache
tail -f /var/log/nginx/error.log    # Nginx
```

---

## 📦 Database Migration

### From Localhost to Production

#### Method 1: Export/Import via phpMyAdmin
1. **Export from localhost**:
   - Open phpMyAdmin
   - Select `foodfusion_db`
   - Click "Export" → "Quick" → "Go"
   - Save `.sql` file

2. **Import to production**:
   - Open phpMyAdmin on hosting
   - Select your database
   - Click "Import"
   - Choose `.sql` file
   - Click "Go"

#### Method 2: Command Line
```bash
# Export from localhost
mysqldump -u root -p foodfusion_db > foodfusion_backup.sql

# Import to production
mysql -h hostname -u username -p database_name < foodfusion_backup.sql
```

#### Method 3: Use Setup Wizard
1. Upload files to production
2. Visit `setup.php`
3. Let it create fresh database with sample data

---

## 🔄 Database Updates

### Applying Schema Changes

If database structure changes:

1. **Check for migration files**:
   ```bash
   ls database/migrations/
   ```

2. **Apply migrations**:
   ```bash
   mysql -u username -p database_name < database/migrations/001_add_views_column.sql
   ```

3. **Or use setup wizard**:
   - Delete `setup.lock`
   - Run `setup.php` again
   - **Warning**: This deletes existing data!

---

## 📞 Getting Help

### Diagnostic Checklist

If database connection fails:

- [ ] Verified credentials in hosting control panel
- [ ] Tested connection with MySQL command line
- [ ] Checked MySQL service is running
- [ ] Tried `127.0.0.1` instead of `localhost`
- [ ] Verified database exists
- [ ] Checked user has proper privileges
- [ ] Reviewed error logs
- [ ] Tested with `test-config.php`

### Where to Find Help

1. **Hosting Support**: Contact your hosting provider
2. **Error Logs**: Check server error logs
3. **phpMyAdmin**: Test connection directly
4. **Documentation**: Review this guide
5. **Test Tools**: Use `test-config.php`

---

## 📝 Configuration Template

Save this as `includes/config.sample.php` (without real credentials):

```php
<?php
/**
 * FoodFusion Configuration Template
 * Copy this file to config.php and update with your credentials
 */

// Include path helper system
require_once(__DIR__ . '/paths.php');

// Database Configuration - UPDATE THESE
define('DB_HOST', 'localhost');        // Database host
define('DB_USER', 'your_username');    // Database username
define('DB_PASS', 'your_password');    // Database password
define('DB_NAME', 'your_database');    // Database name

// Site Configuration (auto-configured)
define('UPLOADS_DIR', assetPath('uploads'));
define('RESOURCES_DIR', assetPath('resources'));

// Security Configuration
define('HASH_COST', 10);
define('SESSION_LIFETIME', 3600);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900);

// Email Configuration (optional)
define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('MAIL_FROM', 'noreply@yourdomain.com');
?>
```

---

**Database Setup Guide Version**: 2.0  
**Last Updated**: November 2025  
**Status**: ✅ Production Ready


