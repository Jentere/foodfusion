# Deploy to InfinityFree - Step by Step Guide

## ✅ Pre-Deployment Checklist

Before uploading to InfinityFree, verify these work on localhost:

- [x] Homepage loads with CSS (`http://localhost/foodfusion/index.php`)
- [x] Culinary page loads (`http://localhost/foodfusion/culinary.php`)
- [ ] Recipes page loads (`http://localhost/foodfusion/recipes.php`)
- [ ] About page loads (`http://localhost/foodfusion/about.php`)
- [ ] Contact page loads (`http://localhost/foodfusion/contact.php`)
- [ ] Community page loads (`http://localhost/foodfusion/community.php`)
- [ ] Educational page loads (`http://localhost/foodfusion/educational.php`)
- [ ] Test config page shows all green (`http://localhost/foodfusion/test-config.php`)

## 📦 Files to Upload

### Critical Files (MUST upload these):
```
includes/paths.php          ← NEW FILE (path helper system)
includes/config.php         ← UPDATED
includes/header.php         ← UPDATED
includes/footer.php         ← UPDATED
test-config.php            ← NEW FILE (for testing)
```

### Updated Page Files:
```
index.php                  ← UPDATED
culinary.php              ← UPDATED
recipes.php               ← UPDATED
about.php                 ← UPDATED
contact.php               ← UPDATED
community.php             ← UPDATED
educational.php           ← UPDATED
logout.php                ← UPDATED
```

### Keep Existing (don't need to re-upload unless changed):
```
assets/                   ← All CSS, JS, images
resources/                ← All PDFs, videos
auth/                     ← Login/register pages
actions/                  ← Form handlers
includes/db.php           ← Database connection
All other files
```

## 🚀 Deployment Steps

### Step 1: Backup Current Site
1. Login to InfinityFree control panel
2. Go to File Manager
3. Download a backup of your current `htdocs` folder
4. Save it somewhere safe

### Step 2: Upload Updated Files

**Option A: Using File Manager (Recommended)**
1. Login to InfinityFree control panel
2. Open File Manager
3. Navigate to `htdocs` folder
4. Upload the files listed above
5. Overwrite when prompted

**Option B: Using FTP (Faster for multiple files)**
1. Use FileZilla or similar FTP client
2. Connect to your InfinityFree FTP:
   - Host: `ftpupload.net`
   - Username: Your InfinityFree username
   - Password: Your FTP password
3. Navigate to `htdocs` folder
4. Upload all updated files
5. Overwrite existing files

### Step 3: Verify Upload
Check that these files exist on the server:
- `htdocs/includes/paths.php` ← Must exist!
- `htdocs/test-config.php` ← Must exist!
- `htdocs/index.php` ← Should be updated
- `htdocs/culinary.php` ← Should be updated

### Step 4: Test Configuration
1. Open browser and navigate to:
   ```
   https://jameschinyamafoodfusion.ct.ws/test-config.php
   ```

2. **Expected Results:**
   - Environment: **Production** ✓
   - Base Path: **/** ✓
   - Site URL: **https://jameschinyamafoodfusion.ct.ws** ✓
   - All path tests show green checkmarks ✓

3. **If you see errors:**
   - Red X marks = Files missing or in wrong location
   - Check file permissions (should be 644)
   - Verify files uploaded correctly

### Step 5: Test Critical Pages

Test each page in this order:

1. **Homepage:**
   ```
   https://jameschinyamafoodfusion.ct.ws/index.php
   ```
   - ✓ CSS loads (page is styled)
   - ✓ Logo appears
   - ✓ Navigation works
   - ✓ Images display

2. **Culinary Page** (was showing 404 before):
   ```
   https://jameschinyamafoodfusion.ct.ws/culinary.php
   ```
   - ✓ Page loads (not 404!)
   - ✓ CSS loads
   - ✓ Images display
   - ✓ PDF downloads work

3. **Recipes Page:**
   ```
   https://jameschinyamafoodfusion.ct.ws/recipes.php
   ```
   - ✓ Page loads
   - ✓ Recipe cards display
   - ✓ Images load

4. **About Page:**
   ```
   https://jameschinyamafoodfusion.ct.ws/about.php
   ```
   - ✓ Team photos display
   - ✓ CSS loads

5. **Contact Page:**
   ```
   https://jameschinyamafoodfusion.ct.ws/contact.php
   ```
   - ✓ Form displays
   - ✓ CSS loads

### Step 6: Browser Console Check
1. Press F12 to open Developer Tools
2. Go to Console tab
3. Check for errors (should be none)
4. Go to Network tab
5. Refresh page
6. Look for any 404 errors (should be none)

## 🔍 Troubleshooting

### Problem: CSS Still Not Loading

**Check:**
1. Browser console for 404 errors
2. File permissions (should be 644 for files, 755 for folders)
3. Clear browser cache (Ctrl+Shift+R)
4. Verify `includes/paths.php` exists on server

**Fix:**
```bash
# Check if paths.php exists
Navigate to: https://jameschinyamafoodfusion.ct.ws/includes/paths.php
# Should show PHP code or blank page (not 404)
```

### Problem: Pages Still Show 404

**Check:**
1. File actually exists on server
2. File name matches exactly (case-sensitive)
3. File is in correct directory (htdocs root)

**Fix:**
- Re-upload the specific page file
- Check file permissions (644)
- Verify no typos in filename

### Problem: Test Config Shows Red X Marks

**Meaning:**
- Files are missing or in wrong location

**Fix:**
1. Check which files show red X
2. Upload those specific files
3. Verify they're in correct directories:
   - CSS files → `htdocs/assets/css/`
   - JS files → `htdocs/assets/js/`
   - Images → `htdocs/assets/images/`

### Problem: "Path configuration not loaded" Error

**Meaning:**
- `includes/paths.php` is missing or not included

**Fix:**
1. Verify `includes/paths.php` exists on server
2. Check file permissions (644)
3. Re-upload if necessary

### Problem: Database Connection Errors

**Note:**
- Database config is already set in `includes/config.php`
- Should work automatically on InfinityFree

**If errors occur:**
1. Verify database credentials in `includes/config.php`
2. Check database exists in InfinityFree control panel
3. Verify database user has permissions

## ✨ Success Indicators

You'll know it's working when:

1. ✅ Test config page shows all green
2. ✅ Homepage loads with full styling
3. ✅ Culinary page loads (was 404 before)
4. ✅ All navigation links work
5. ✅ Images display correctly
6. ✅ No 404 errors in browser console
7. ✅ CSS and JS files load (check Network tab)

## 🎯 Post-Deployment

### Clean Up (Optional)
Once everything works, you can:
1. Delete `test-config.php` from server (or restrict access)
2. Delete `DEPLOYMENT-FIX-README.md` from server
3. Delete `DEPLOY-TO-INFINITYFREE.md` from server

### Monitor
- Check error logs in InfinityFree control panel
- Test all major features (login, register, recipe submission)
- Ask friends to test from different devices

## 📞 Need Help?

If you encounter issues:

1. **Run test-config.php** - Shows exactly what's wrong
2. **Check browser console** - Shows 404 errors and JS errors
3. **Check InfinityFree error logs** - Shows PHP errors
4. **Verify file structure** - Make sure files are in correct locations

## 🎉 Expected Outcome

After successful deployment:
- ✅ All pages load correctly
- ✅ CSS styling works everywhere
- ✅ Navigation works perfectly
- ✅ Images display
- ✅ Forms work
- ✅ No 404 errors
- ✅ Site looks identical to localhost

---

**Ready to deploy?** Follow the steps above and your site will work perfectly on InfinityFree! 🚀
