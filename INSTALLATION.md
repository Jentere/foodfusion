# FoodFusion Installation Guide

## 📋 Prerequisites

Before installation, ensure you have:

### Required
- **PHP 7.4 or higher** (PHP 8.x recommended)
- **MySQL 5.7+** or **MariaDB 10.2+**
- **Web Server**: Apache (with mod_rewrite) or Nginx
- **PHP Extensions**:
  - `mysqli` - Database connectivity
  - `pdo` - Database abstraction
  - `gd` - Image processing

### Recommended
- **HTTPS/SSL** certificate (for production)
- **PHP 8.0+** for better performance
- **Composer** (optional, for future extensions)

### Check Your Environment
Create a file `phpinfo.php` with:
```php
<?php phpinfo(); ?>
```
Upload it and visit in browser to verify PHP version and extensions.

---

## 🎯 Quick Installation (3 Steps)

### Step 1: Upload Files

**For Localhost (XAMPP/WAMP/MAMP)**:
```
Extract to: C:\xampp\htdocs\foodfusion\
Access at: http://localhost/foodfusion/
```

**For Shared Hosting (cPanel/Plesk)**:
```
Upload to: /public_html/ (root) or /public_html/foodfusion/ (subdirectory)
Access at: https://yourdomain.com/ or https://yourdomain.com/foodfusion/
```

**For Cloud/VPS**:
```
Upload to: /var/www/html/
Access at: https://yourserver.com/
```

### Step 2: Set Permissions

Ensure these directories are writable:
```bash
chmod 755 uploads/
chmod 755 resources/
chmod 755 includes/
```

### Step 3: Run Setup Wizard

1. **Navigate to setup**:
   ```
   http://your-domain.com/setup.php
   ```
   (Or `http://localhost/foodfusion/setup.php` for localhost)

2. **Follow the wizard**:
   - ✅ **Step 1**: System requirements check
   - ✅ **Step 2**: Database configuration
   - ✅ **Step 3**: Database installation
   - ✅ **Step 4**: Installation complete!

3. **Done!** Access your site:
   ```
   http://your-domain.com/
   ```

---

## 🌍 Installation Scenarios

### Scenario 1: Localhost with XAMPP

**Installation Path**: `C:\xampp\htdocs\foodfusion\`

**Steps**:
1. Extract files to `C:\xampp\htdocs\foodfusion\`
2. Start Apache and MySQL in XAMPP Control Panel
3. Visit: `http://localhost/foodfusion/setup.php`
4. Database settings:
   - Host: `localhost`
   - Username: `root`
   - Password: (leave empty)
   - Database: `foodfusion_db`
5. Click "Install Database"
6. Access: `http://localhost/foodfusion/`

**Result**: ✅ Works automatically with `/foodfusion/` base path

---

### Scenario 2: Shared Hosting (Root Directory)

**Installation Path**: `/public_html/` or `/htdocs/`

**Steps**:
1. Upload all files to root directory via FTP/File Manager
2. Visit: `https://yourdomain.com/setup.php`
3. Enter your hosting database credentials:
   - Host: `localhost` or `127.0.0.1`
   - Username: (from hosting control panel)
   - Password: (from hosting control panel)
   - Database: (create in control panel first)
4. Click "Install Database"
5. Access: `https://yourdomain.com/`

**Result**: ✅ Works automatically with `/` base path

---

### Scenario 3: Shared Hosting (Subdirectory)

**Installation Path**: `/public_html/foodfusion/`

**Steps**:
1. Create `foodfusion` folder in `public_html`
2. Upload all files to `/public_html/foodfusion/`
3. Visit: `https://yourdomain.com/foodfusion/setup.php`
4. Enter database credentials
5. Click "Install Database"
6. Access: `https://yourdomain.com/foodfusion/`

**Result**: ✅ Works automatically with `/foodfusion/` base path

---

### Scenario 4: InfinityFree / Free Hosting

**Installation Path**: `/htdocs/`

**Steps**:
1. Upload files via File Manager or FTP
2. Create MySQL database in control panel
3. Note your database credentials:
   - Host: Usually `sql###.infinityfree.com`
   - Username: Format like `if0_12345678`
   - Database: Format like `if0_12345678_foodfusion`
4. Visit: `https://yoursubdomain.infinityfreeapp.com/setup.php`
5. Enter credentials and install
6. Access your site

**Result**: ✅ Works automatically

**Note**: Some free hosts may have limitations. See [DATABASE-SETUP-GUIDE.md](DATABASE-SETUP-GUIDE.md) for troubleshooting.

---

### Scenario 5: Cloud/VPS (Ubuntu/Debian)

**Installation Path**: `/var/www/html/`

**Steps**:
1. SSH into your server
2. Install prerequisites:
   ```bash
   sudo apt update
   sudo apt install apache2 mysql-server php php-mysqli php-gd
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```
3. Upload files to `/var/www/html/`
4. Set permissions:
   ```bash
   sudo chown -R www-data:www-data /var/www/html/
   sudo chmod -R 755 /var/www/html/
   ```
5. Create database:
   ```bash
   sudo mysql -u root -p
   CREATE DATABASE foodfusion_db;
   CREATE USER 'foodfusion'@'localhost' IDENTIFIED BY 'secure_password';
   GRANT ALL PRIVILEGES ON foodfusion_db.* TO 'foodfusion'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```
6. Visit: `https://yourserver.com/setup.php`
7. Enter database credentials and install

**Result**: ✅ Works automatically

---

## 🔧 What the Setup Does

The automated setup wizard:

1. ✅ **Checks System Requirements**
   - Verifies PHP version (7.4+)
   - Checks required extensions (mysqli, pdo, gd)
   - Tests directory permissions

2. ✅ **Tests Database Connection**
   - Validates credentials
   - Ensures database accessibility
   - Creates database if needed

3. ✅ **Creates Database Structure**
   - Creates all required tables
   - Sets up indexes and relationships
   - Configures default settings

4. ✅ **Imports Sample Data**
   - Adds 11 sample recipes
   - Creates sample categories
   - Sets up initial content

5. ✅ **Generates Configuration**
   - Creates `includes/config.php`
   - Sets secure defaults
   - Configures path system

6. ✅ **Locks Installation**
   - Creates `setup.lock` file
   - Prevents re-installation
   - Secures setup process

---

## 🧪 Verify Installation

### Test 1: Configuration Check
Visit: `http://your-domain.com/test-config.php`

Should show:
- ✅ Environment detected correctly
- ✅ Base path configured
- ✅ All test URLs working
- ✅ Assets loading correctly

### Test 2: Homepage
Visit: `http://your-domain.com/`

Should display:
- ✅ Styled homepage with CSS
- ✅ Logo and images
- ✅ Working navigation
- ✅ Sample recipes

### Test 3: Registration
Visit: `http://your-domain.com/auth/register.php`

Should allow:
- ✅ Creating new account
- ✅ Logging in
- ✅ Accessing user features

---

## 🔄 Reinstallation

If you need to reinstall:

### Method 1: Delete Lock File
1. Delete `setup.lock` from root directory
2. Visit `setup.php` again
3. **Warning**: This will delete all existing data!

### Method 2: Manual Database Reset
1. Drop database tables via phpMyAdmin
2. Delete `setup.lock`
3. Run setup again

---

## 🛠️ Manual Installation (Advanced)

If you prefer manual setup:

### Step 1: Create Database
```sql
CREATE DATABASE foodfusion_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 2: Import Schema
```bash
mysql -u username -p foodfusion_db < database/foodfusion_db.sql
```

### Step 3: Configure
1. Copy `includes/config.sample.php` to `includes/config.php`
2. Edit database credentials:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'foodfusion_db');
```

### Step 4: Set Permissions
```bash
chmod 755 uploads/ resources/
chmod 644 includes/config.php
```

### Step 5: Create Lock File
```bash
touch setup.lock
```

---

## 🐛 Troubleshooting

### Issue: "Requirements Not Met"

**Symptoms**: Setup shows missing requirements

**Solutions**:
```bash
# Check PHP version
php -v  # Must be 7.4 or higher

# Install missing extensions (Ubuntu/Debian)
sudo apt install php-mysqli php-pdo php-gd

# Install missing extensions (CentOS/RHEL)
sudo yum install php-mysqli php-pdo php-gd

# Restart web server
sudo systemctl restart apache2  # or nginx
```

---

### Issue: "Database Connection Failed"

**Symptoms**: Cannot connect to MySQL

**Solutions**:

1. **Verify MySQL is running**:
   ```bash
   # Check MySQL status
   sudo systemctl status mysql
   
   # Start MySQL if stopped
   sudo systemctl start mysql
   ```

2. **Test credentials**:
   ```bash
   mysql -u username -p
   # Enter password when prompted
   ```

3. **Try different host**:
   - Change from `localhost` to `127.0.0.1`
   - Or use full hostname (e.g., `sql###.infinityfree.com`)

4. **Check user permissions**:
   ```sql
   GRANT ALL PRIVILEGES ON foodfusion_db.* TO 'username'@'localhost';
   FLUSH PRIVILEGES;
   ```

---

### Issue: "Permission Denied"

**Symptoms**: Cannot write to directories

**Solutions**:
```bash
# Set correct ownership (Linux)
sudo chown -R www-data:www-data /var/www/html/foodfusion/

# Set correct permissions
chmod 755 uploads/ resources/ includes/
chmod 644 *.php

# For localhost (Windows)
# Right-click folder → Properties → Security → Edit → Add write permissions
```

---

### Issue: CSS/JavaScript Not Loading

**Symptoms**: Page loads but no styling

**Solutions**:

1. **Check .htaccess**:
   - Verify `.htaccess` file exists in root
   - Ensure `mod_rewrite` is enabled

2. **Enable mod_rewrite** (Apache):
   ```bash
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```

3. **Test paths**:
   - Visit: `http://your-domain.com/test-config.php`
   - Verify all paths show green checkmarks

4. **Clear browser cache**:
   - Press `Ctrl+Shift+R` (Windows/Linux)
   - Press `Cmd+Shift+R` (Mac)

---

### Issue: "Page Not Found" (404)

**Symptoms**: All pages except index.php show 404

**Solutions**:

1. **Check .htaccess**:
   ```apache
   # Ensure .htaccess contains:
   RewriteEngine On
   RewriteBase /
   ```

2. **Verify mod_rewrite**:
   ```bash
   apache2ctl -M | grep rewrite
   # Should show: rewrite_module (shared)
   ```

3. **Check Apache config**:
   ```apache
   # In /etc/apache2/sites-available/000-default.conf
   <Directory /var/www/html>
       AllowOverride All
   </Directory>
   ```

---

### Issue: Setup Keeps Redirecting

**Symptoms**: Can't access site, always redirects to setup

**Solutions**:

1. **Check setup.lock exists**:
   ```bash
   ls -la setup.lock
   # If missing, create it:
   touch setup.lock
   ```

2. **Verify config.php exists**:
   ```bash
   ls -la includes/config.php
   # If missing, run setup again
   ```

---

## 📊 Post-Installation Checklist

After installation, verify:

- [ ] Homepage loads with CSS styling
- [ ] Navigation links work
- [ ] Images display correctly
- [ ] Registration page works
- [ ] Login functionality works
- [ ] Recipe browsing works
- [ ] Search functionality works
- [ ] Forms submit successfully
- [ ] No errors in browser console (F12)
- [ ] No errors in server logs

---

## 🎯 What You Get

After successful installation:

### Content
- ✅ 11 sample recipes (Italian, Asian, American, etc.)
- ✅ Recipe categories and tags
- ✅ Sample community posts
- ✅ Educational resources (PDFs, videos)
- ✅ Cooking tips and guides

### Features
- ✅ User registration and authentication
- ✅ Recipe browsing with search and filters
- ✅ Community cookbook with posts
- ✅ Recipe rating and reviews
- ✅ Comments and likes
- ✅ Contact form
- ✅ Responsive mobile design
- ✅ Admin capabilities

### Security
- ✅ Password hashing (bcrypt)
- ✅ SQL injection protection
- ✅ XSS prevention
- ✅ CSRF protection
- ✅ Secure session management
- ✅ Path traversal protection

---

## 🔐 Security Best Practices

### After Installation:

1. **Remove test files** (optional):
   ```bash
   rm test-config.php
   rm test-paths.php
   rm phpinfo.php
   ```

2. **Secure config.php**:
   ```bash
   chmod 644 includes/config.php
   ```

3. **Keep setup.lock**:
   - Never delete this file in production
   - Prevents unauthorized re-installation

4. **Use HTTPS**:
   - Install SSL certificate
   - Force HTTPS in .htaccess:
   ```apache
   RewriteCond %{HTTPS} off
   RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```

5. **Regular backups**:
   - Backup database regularly
   - Backup uploaded files
   - Keep backups off-server

---

## 📚 Next Steps

### For Users:
1. Register your first account
2. Browse sample recipes
3. Create your first recipe
4. Join the community

### For Administrators:
1. Review `includes/config.php` settings
2. Configure email settings (SMTP)
3. Customize site branding
4. Add more recipes and content
5. Monitor user activity

### For Developers:
1. Read [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)
2. Review [HARDCODED-PATHS-EXPLANATION.md](HARDCODED-PATHS-EXPLANATION.md)
3. Understand the path system in `includes/paths.php`
4. Explore the codebase structure

---

## 🆘 Getting Help

If you encounter issues:

1. **Check Documentation**:
   - [README.md](README.md) - Overview
   - [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) - Deployment help
   - [DATABASE-SETUP-GUIDE.md](DATABASE-SETUP-GUIDE.md) - Database issues

2. **Use Diagnostic Tools**:
   - `test-config.php` - Path configuration
   - `phpinfo.php` - PHP configuration
   - Browser console (F12) - JavaScript errors

3. **Check Logs**:
   - PHP error log
   - Apache/Nginx error log
   - MySQL error log

4. **Common Solutions**:
   - Clear browser cache
   - Check file permissions
   - Verify database credentials
   - Test with `test-config.php`

---

## 🎉 Success!

Your FoodFusion installation is complete!

**Access your site**:
- Homepage: `http://your-domain.com/`
- Register: `http://your-domain.com/auth/register.php`
- Login: `http://your-domain.com/auth/login.php`

**Start sharing recipes and building your culinary community!** 🍽️

---

**Installation Guide Version**: 2.0  
**Last Updated**: November 2025  
**Compatible With**: FoodFusion 1.0.0+