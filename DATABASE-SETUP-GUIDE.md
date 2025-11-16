# 🗄️ Database Setup Guide for Production

## Problem
Getting error: **"Database connection error: No such file or directory"**

This happens because your production server has different database credentials than localhost.

## Solution

### Step 1: Find Your Database Credentials

On **InfinityFree** (or your hosting provider):

1. Log into your hosting control panel
2. Go to **MySQL Databases** section
3. Find your database information:
   - **Database Host**: Usually `localhost` or `sql###.infinityfree.com`
   - **Database Name**: Format like `if0_12345678_foodfusion` or `epiz_12345678_foodfusion`
   - **Database Username**: Format like `if0_12345678` or `epiz_12345678`
   - **Database Password**: The password you set when creating the database

### Step 2: Test Your Connection

Visit: `https://jameschinyamafoodfusion.ct.ws/check-db.php`

1. Enter your database credentials
2. Click "Test Connection"
3. If successful, copy the generated configuration code

### Step 3: Update config.php

Edit `includes/config.php` on your production server:

```php
<?php
// Include path helper system first
require_once(__DIR__ . '/paths.php');

// Database Configuration - UPDATE THESE VALUES
define('DB_HOST', 'localhost');           // or '127.0.0.1' or 'sql###.infinityfree.com'
define('DB_USER', 'if0_12345678');        // Your actual database username
define('DB_PASS', 'your_password_here');  // Your actual database password
define('DB_NAME', 'if0_12345678_foodfusion'); // Your actual database name

// Rest of the file stays the same...
```

### Step 4: Common Fixes

#### Fix 1: Use 127.0.0.1 instead of localhost
```php
define('DB_HOST', '127.0.0.1');  // Instead of 'localhost'
```

#### Fix 2: Use Full Database Host (InfinityFree)
```php
define('DB_HOST', 'sql###.infinityfree.com');  // Check your control panel
```

#### Fix 3: Verify Database Name Format
InfinityFree databases usually have a prefix:
```php
define('DB_NAME', 'if0_12345678_foodfusion');  // Not just 'foodfusion_db'
```

### Step 5: Import Database

If you haven't imported your database yet:

1. Export from localhost:
   - Open phpMyAdmin on localhost
   - Select `foodfusion_db`
   - Click "Export" → "Go"
   - Save the `.sql` file

2. Import to production:
   - Open phpMyAdmin on your hosting
   - Select your database
   - Click "Import"
   - Choose your `.sql` file
   - Click "Go"

### Step 6: Run Setup (If Needed)

If database is empty, run setup:
1. Delete `setup.lock` file from your server
2. Visit: `https://jameschinyamafoodfusion.ct.ws/setup.php`
3. Follow the setup wizard

## Quick Troubleshooting

### Error: "No such file or directory"
**Solution:** Change `DB_HOST` from `'localhost'` to `'127.0.0.1'`

### Error: "Access denied for user"
**Solution:** Check your username and password are correct

### Error: "Unknown database"
**Solution:** Verify database name in hosting control panel

### Error: "Can't connect to MySQL server"
**Solution:** Check if database host is correct (might need full hostname)

## Files to Update

Only update on **production server** (not in git):

```
includes/config.php  ← Update database credentials here
```

**DO NOT** commit `includes/config.php` with production credentials to git!

## Verification

After updating config.php:

1. Visit: `https://jameschinyamafoodfusion.ct.ws/check-db.php`
2. Test connection with your credentials
3. Should see "✓ Success!" message
4. Visit: `https://jameschinyamafoodfusion.ct.ws/`
5. Homepage should load without errors

## Example InfinityFree Configuration

```php
<?php
require_once(__DIR__ . '/paths.php');

// InfinityFree Database Configuration
define('DB_HOST', 'sql###.infinityfree.com');  // From control panel
define('DB_USER', 'if0_12345678');              // From control panel
define('DB_PASS', 'YourSecurePassword123');     // Your password
define('DB_NAME', 'if0_12345678_foodfusion');   // From control panel

// Rest stays the same...
define('UPLOADS_DIR', __DIR__ . '/../uploads');
define('RESOURCES_DIR', __DIR__ . '/../resources');
define('HASH_COST', 10);
define('SESSION_LIFETIME', 3600);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900);
define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('MAIL_FROM', 'noreply@foodfusion.com');
?>
```

## Security Note

⚠️ **IMPORTANT:** Never commit `includes/config.php` with real credentials to git!

Add to `.gitignore`:
```
includes/config.php
```

Keep a template instead:
```
includes/config.sample.php  ← Safe to commit (no real credentials)
```

---

**Need Help?**
1. Use `check-db.php` to test connections
2. Check your hosting control panel for exact credentials
3. Contact your hosting support if still having issues
