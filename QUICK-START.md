# 🚀 FoodFusion - Quick Start Guide

## 30-Second Setup

### 1. Upload Files
Upload all files to your web server

### 2. Run Setup
Visit: `http://yourdomain.com/setup.php`

### 3. Enter Database Info
- Host: `localhost` (or from hosting panel)
- Username: Your database username
- Password: Your database password
- Database: Your database name

### 4. Click Install
Wait for installation to complete

### 5. Done!
Visit: `http://yourdomain.com/`

---

## Works Everywhere! 🌍

✅ Localhost (XAMPP, WAMP, MAMP)  
✅ Shared Hosting (cPanel, Plesk)  
✅ Cloud (AWS, Azure, DigitalOcean)  
✅ Free Hosting (InfinityFree, 000webhost)  
✅ Any subdirectory or root installation  
✅ HTTP or HTTPS  

**No configuration changes needed!**

---

## Common Hosting Setups

### XAMPP (Localhost)
```
Path: C:\xampp\htdocs\foodfusion\
URL: http://localhost/foodfusion/
Database Host: localhost
Database User: root
Database Pass: (empty)
```

### cPanel (Shared Hosting)
```
Path: /public_html/
URL: https://yourdomain.com/
Database Host: localhost
Database User: cpanel_user
Database Pass: your_password
```

### InfinityFree
```
Path: /htdocs/
URL: https://yoursite.infinityfreeapp.com/
Database Host: sql###.infinityfree.com
Database User: if0_12345678
Database Pass: your_password
```

---

## Troubleshooting

### CSS Not Loading?
- Visit: `http://yourdomain.com/test-config.php`
- Check if paths are correct
- Clear browser cache (Ctrl+Shift+R)

### Database Error?
- Verify credentials in hosting control panel
- Try `127.0.0.1` instead of `localhost`
- Ensure database exists

### Page 404?
- Check if `.htaccess` file uploaded
- Verify `mod_rewrite` enabled
- Check file permissions (755 for folders, 644 for files)

---

## Need Help?

📖 **Full Documentation**:
- [INSTALLATION.md](INSTALLATION.md) - Detailed setup
- [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) - Deployment help
- [DATABASE-SETUP-GUIDE.md](DATABASE-SETUP-GUIDE.md) - Database issues

🧪 **Test Tools**:
- `test-config.php` - Verify configuration
- Browser Console (F12) - Check for errors

---

## Features Included

✅ Recipe browsing and sharing  
✅ Community posts  
✅ User authentication  
✅ Recipe ratings  
✅ Comments and likes  
✅ Educational resources  
✅ Contact form  
✅ Responsive design  

---

**That's it! Your site is ready to use! 🎉**

For detailed instructions, see [INSTALLATION.md](INSTALLATION.md)
