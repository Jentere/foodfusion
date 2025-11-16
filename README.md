# FoodFusion

A community-driven recipe sharing and culinary education platform built with PHP and MySQL.

## ✨ Features

- 🍽️ **Recipe Sharing** - Browse, search, and share recipes with the community
- 👥 **Community Posts** - Share cooking experiences and interact with other food enthusiasts
- 📚 **Educational Resources** - Access culinary guides, cookbooks, and video tutorials
- 🔐 **User Authentication** - Secure registration and login system
- ⭐ **Recipe Ratings** - Rate and review recipes
- 💬 **Comments & Likes** - Engage with community content
- 📱 **Responsive Design** - Works perfectly on desktop, tablet, and mobile
- 🌍 **Universal Deployment** - Works on any hosting environment without configuration

## 🚀 Quick Start

### Requirements

- **PHP 7.4 or higher**
- **MySQL 5.7 or higher** (or MariaDB)
- **Web server** (Apache/Nginx with mod_rewrite)
- **PHP Extensions**: mysqli, pdo, gd

### Installation (3 Easy Steps)

1. **Upload Files**
   - Download/clone this repository
   - Upload to your web server directory (e.g., `htdocs`, `www`, or `public_html`)

2. **Run Setup**
   - Navigate to: `http://your-domain.com/setup.php`
   - Follow the automated installation wizard
   - Enter your database credentials
   - Click "Install Database"

3. **Done!**
   - Access your site: `http://your-domain.com/`
   - Register your first account
   - Start sharing recipes!

### Works Everywhere! 🌍

FoodFusion automatically adapts to any environment:

- ✅ **Localhost** (XAMPP, WAMP, MAMP, LAMP)
- ✅ **Shared Hosting** (cPanel, Plesk, DirectAdmin)
- ✅ **Cloud Hosting** (AWS, Azure, DigitalOcean, Linode)
- ✅ **Free Hosting** (InfinityFree, 000webhost, etc.)
- ✅ **Subdirectory** (`/foodfusion/`) or **Root** (`/`) installations
- ✅ **HTTP** or **HTTPS** - Auto-detects protocol
- ✅ **Any Domain** - No hardcoded URLs

**No configuration changes needed between environments!**

## 📖 Documentation

- **[Installation Guide](INSTALLATION.md)** - Detailed setup instructions
- **[Deployment Guide](DEPLOYMENT_GUIDE.md)** - Deploy to any hosting environment
- **[Database Setup](DATABASE-SETUP-GUIDE.md)** - Configure database for production
- **[Path System Explanation](HARDCODED-PATHS-EXPLANATION.md)** - Technical details on universal path system

## 🔒 Security Features

- ✅ **Password Hashing** - Bcrypt with configurable cost
- ✅ **SQL Injection Protection** - Prepared statements throughout
- ✅ **XSS Prevention** - Input sanitization and output escaping
- ✅ **CSRF Protection** - Token-based form validation
- ✅ **Path Traversal Protection** - Secure file access
- ✅ **Session Security** - Secure session management
- ✅ **Input Validation** - Server-side validation on all forms

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+ / MariaDB
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Server**: Apache/Nginx with mod_rewrite
- **Architecture**: MVC-inspired structure with dynamic path resolution

## 📁 Project Structure

```
foodfusion/
├── assets/              # Static assets (CSS, JS, images)
├── includes/            # Core PHP files (config, paths, db)
├── auth/                # Authentication pages
├── actions/             # Form processing scripts
├── database/            # Database schema and migrations
├── uploads/             # User-uploaded content
├── resources/           # Downloadable resources (PDFs, videos)
├── setup.php            # Automated installation wizard
├── test-config.php      # Path configuration validator
└── index.php            # Homepage
```

## 🧪 Testing Your Installation

After installation, verify everything works:

1. **Configuration Test**: Visit `http://your-domain.com/test-config.php`
   - Checks environment detection
   - Validates path generation
   - Tests asset loading

2. **Manual Testing**:
   - ✅ Homepage loads with CSS
   - ✅ Navigation links work
   - ✅ Images display correctly
   - ✅ Registration/Login works
   - ✅ Recipe browsing works
   - ✅ Forms submit successfully

## 🌐 Deployment Examples

### Localhost (XAMPP)
```
Installation: C:\xampp\htdocs\foodfusion\
Access URL: http://localhost/foodfusion/
Status: ✅ Works automatically
```

### Shared Hosting (Root)
```
Installation: /public_html/
Access URL: https://yourdomain.com/
Status: ✅ Works automatically
```

### Shared Hosting (Subdirectory)
```
Installation: /public_html/foodfusion/
Access URL: https://yourdomain.com/foodfusion/
Status: ✅ Works automatically
```

### Cloud Server (VPS)
```
Installation: /var/www/html/
Access URL: https://yourserver.com/
Status: ✅ Works automatically
```

## 🔧 Configuration

### Automatic Configuration
The system automatically detects:
- Environment (localhost vs production)
- Base path (subdirectory or root)
- Protocol (HTTP or HTTPS)
- Domain name

### Manual Configuration (Optional)
Only needed for advanced setups. Edit `includes/config.php`:
```php
// Database credentials (set during installation)
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'your_database');
```

## 🐛 Troubleshooting

### CSS Not Loading?
- Run `test-config.php` to verify paths
- Check browser console for 404 errors
- Verify `.htaccess` file exists
- Ensure file permissions are correct (755 for directories, 644 for files)

### Database Connection Failed?
- Verify database credentials in `includes/config.php`
- Ensure MySQL service is running
- Check database user has proper permissions
- Try using `127.0.0.1` instead of `localhost` for DB_HOST

### Pages Show 404?
- Verify `.htaccess` is enabled
- Check if `mod_rewrite` is enabled on server
- Ensure files uploaded correctly

### Need Help?
1. Check the [Troubleshooting Guide](DEPLOYMENT_GUIDE.md#-troubleshooting)
2. Review browser console for errors
3. Check server error logs
4. Verify all files uploaded correctly

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🙏 Acknowledgments

- Font Awesome for icons
- Google Fonts for typography
- The PHP and MySQL communities

## 📞 Support

For issues and questions:
- Check the documentation files
- Review the troubleshooting section
- Test with `test-config.php`
- Check server error logs

---

**Made with ❤️ for food enthusiasts worldwide**

**Version**: 1.0.0  
**Last Updated**: November 2025