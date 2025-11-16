# FoodFusion Deployment Guide

## 🌍 Universal Deployment - Works Anywhere!

Your FoodFusion application is now configured to work in **ANY environment** without hardcoded URLs. The path system automatically detects:

- ✅ Localhost installations
- ✅ Subdirectory installations (e.g., `/foodfusion/`, `/myapp/`)
- ✅ Root directory installations
- ✅ Different domain names
- ✅ HTTP and HTTPS protocols
- ✅ Different ports (80, 8080, 443, etc.)

---

## 📋 Pre-Deployment Checklist

### 1. Test Your Paths
Visit: `http://yourdomain.com/test-paths.php`

This will show you:
- Current server configuration
- Generated URLs
- Asset existence verification
- Live link tests
- Image loading tests

### 2. Verify Files
Ensure these files exist:
- ✅ `includes/paths.php` - Path helper system
- ✅ `includes/config.php` - Database configuration (created by setup)
- ✅ `setup.lock` - Setup completion marker
- ✅ `.htaccess` - Server configuration

---

## 🚀 Deployment Scenarios

### Scenario 1: Localhost (XAMPP/WAMP/MAMP)

**Installation Path:** `C:\xampp\htdocs\foodfusion\`

**Access URL:** `http://localhost/foodfusion/`

**What Happens:**
- `getBasePath()` returns: `/foodfusion/`
- `getSiteUrl()` returns: `http://localhost/foodfusion`
- All URLs automatically include `/foodfusion/` prefix

**No configuration needed!** ✓

---

### Scenario 2: Localhost Root Directory

**Installation Path:** `C:\xampp\htdocs\`

**Access URL:** `http://localhost/`

**What Happens:**
- `getBasePath()` returns: `/`
- `getSiteUrl()` returns: `http://localhost`
- All URLs work from root

**No configuration needed!** ✓

---

### Scenario 3: Shared Hosting (Subdirectory)

**Installation Path:** `/home/username/public_html/foodfusion/`

**Access URL:** `https://yourdomain.com/foodfusion/`

**What Happens:**
- `getBasePath()` returns: `/foodfusion/`
- `getSiteUrl()` returns: `https://yourdomain.com/foodfusion`
- Automatically detects HTTPS

**No configuration needed!** ✓

---

### Scenario 4: Shared Hosting (Root)

**Installation Path:** `/home/username/public_html/`

**Access URL:** `https://yourdomain.com/`

**What Happens:**
- `getBasePath()` returns: `/`
- `getSiteUrl()` returns: `https://yourdomain.com`
- Works from domain root

**No configuration needed!** ✓

---

### Scenario 5: Cloud Hosting (AWS, Azure, DigitalOcean)

**Installation Path:** `/var/www/html/`

**Access URL:** `https://yourserver.com/`

**What Happens:**
- Automatically detects server configuration
- Works with load balancers (detects `X-Forwarded-Proto`)
- Handles reverse proxies correctly

**No configuration needed!** ✓

---

## 🔧 How It Works

### Automatic Path Detection

The `getBasePath()` function:
```php
function getBasePath(): string {
    // Gets current script location
    $scriptName = $_SERVER['SCRIPT_NAME'];
    
    // Extracts directory path
    $scriptDir = dirname($scriptName);
    
    // Removes subdirectories like /auth, /includes
    $basePath = preg_replace('#/(auth|includes|assets|database|api).*$#', '', $scriptDir);
    
    // Returns normalized path
    return $basePath . '/';
}
```

### Examples:
- Script: `/foodfusion/index.php` → Base: `/foodfusion/`
- Script: `/index.php` → Base: `/`
- Script: `/myapp/auth/login.php` → Base: `/myapp/`

### Protocol Detection

The `getSiteUrl()` function detects HTTPS from:
- `$_SERVER['HTTPS']` (standard)
- `$_SERVER['HTTP_X_FORWARDED_PROTO']` (load balancers)
- `$_SERVER['SERVER_PORT']` (port 443)

---

## 📝 Usage in Your Code

### Linking to Pages
```php
<a href="<?php echo url('recipes.php'); ?>">Recipes</a>
<a href="<?php echo url('auth/login.php'); ?>">Login</a>
```

### Loading Assets
```php
<link rel="stylesheet" href="<?php echo url('assets/css/style.css'); ?>">
<script src="<?php echo url('assets/js/script.js'); ?>"></script>
<img src="<?php echo url('assets/images/logo.png'); ?>" alt="Logo">
```

### Redirecting
```php
redirect('index.php');  // Internal redirect
redirectExternal('https://google.com');  // External redirect
```

### URL with Parameters
```php
$url = urlWithParams('recipes.php', ['category' => 'italian', 'difficulty' => 'easy']);
// Result: /foodfusion/recipes.php?category=italian&difficulty=easy
```

---

## 🛡️ Security Features

### Path Traversal Prevention
```php
url('../../../etc/passwd');  // Blocked!
url('assets/../../../config.php');  // Blocked!
```

The `url()` function automatically removes:
- `../` (parent directory)
- `..\` (Windows parent directory)
- `\` (backslashes)

### File Existence Checking
```php
if (assetExists('assets/images/logo.png')) {
    echo '<img src="' . url('assets/images/logo.png') . '">';
}
```

---

## 🧪 Testing Your Deployment

### Step 1: Run Path Tests
```
http://yourdomain.com/test-paths.php
```

Check that:
- ✅ Base path is correct
- ✅ Site URL is correct
- ✅ All test links work
- ✅ Images load correctly

### Step 2: Test Navigation
Click through all pages:
- ✅ Home → About → Recipes → Community
- ✅ Login → Register
- ✅ All CSS and JS files load
- ✅ All images display

### Step 3: Test Forms
- ✅ Registration works
- ✅ Login works
- ✅ Contact form submits
- ✅ Recipe submission works

---

## 🐛 Troubleshooting

### Problem: CSS/JS Not Loading

**Symptom:** Page loads but no styling

**Solution:**
1. Check browser console for 404 errors
2. Visit `test-paths.php` to verify paths
3. Ensure `.htaccess` file exists
4. Check file permissions (755 for directories, 644 for files)

### Problem: Images Not Displaying

**Symptom:** Broken image icons

**Solution:**
1. Verify images exist in `assets/images/`
2. Check file names match exactly (case-sensitive on Linux)
3. Test with: `http://yourdomain.com/assets/images/logo.png`

### Problem: 404 on All Pages

**Symptom:** Only index.php works

**Solution:**
1. Check if `.htaccess` is enabled
2. Verify `mod_rewrite` is enabled on server
3. Check Apache configuration allows `.htaccess` overrides

### Problem: Database Connection Failed

**Symptom:** "Database connection failed" error

**Solution:**
1. Run setup again: Delete `setup.lock` and visit `setup.php`
2. Verify database credentials in `includes/config.php`
3. Ensure MySQL service is running
4. Check database user has proper permissions

---

## 📦 Deployment Steps

### For New Installation:

1. **Upload Files**
   - Upload all files to your server
   - Maintain directory structure

2. **Set Permissions**
   ```bash
   chmod 755 uploads/
   chmod 755 resources/
   chmod 644 includes/config.php  # After setup
   ```

3. **Run Setup**
   - Visit: `http://yourdomain.com/setup.php`
   - Enter database credentials
   - Click "Install"

4. **Test Paths**
   - Visit: `http://yourdomain.com/test-paths.php`
   - Verify all paths are correct

5. **Test Application**
   - Register a new account
   - Login
   - Browse recipes
   - Test all features

6. **Secure Installation**
   - Delete `test-paths.php` (optional)
   - Delete `test-video.php` (optional)
   - Keep `setup.lock` file

---

## 🌐 Multi-Environment Support

### Development
```
http://localhost/foodfusion/
```
- Full error reporting
- Debug mode enabled
- Test data

### Staging
```
https://staging.yourdomain.com/
```
- Limited error reporting
- Production-like environment
- Test with real data

### Production
```
https://yourdomain.com/
```
- Error logging only
- Optimized performance
- Live data

**All work with the same codebase!** No configuration changes needed.

---

## ✅ Verification Checklist

Before going live, verify:

- [ ] `test-paths.php` shows all green checkmarks
- [ ] All pages load correctly
- [ ] All images display
- [ ] CSS and JavaScript work
- [ ] Forms submit successfully
- [ ] Login/Registration works
- [ ] Database operations work
- [ ] File uploads work (if applicable)
- [ ] Mobile responsive design works
- [ ] HTTPS is enabled (production)
- [ ] Error logging is configured
- [ ] Backup system is in place

---

## 🎉 Success!

Your FoodFusion application is now ready to deploy anywhere!

The automatic path detection ensures your application will work on:
- ✅ Any localhost setup
- ✅ Any shared hosting
- ✅ Any cloud platform
- ✅ Any subdirectory
- ✅ Any domain name

**No hardcoded URLs. No configuration changes. Just works!** 🚀

---

## 📞 Support

If you encounter issues:
1. Check `test-paths.php` for diagnostics
2. Review browser console for errors
3. Check server error logs
4. Verify file permissions
5. Ensure all files uploaded correctly

---

**Last Updated:** November 15, 2025
**Version:** 1.0.0
