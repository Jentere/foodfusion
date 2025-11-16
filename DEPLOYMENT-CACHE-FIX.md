# 🚀 Deployment Cache Fix Guide

## Problem Identified

The production site is experiencing cache issues where:
1. **Old JavaScript files are cached** with hardcoded `/foodfusion/` paths
2. **Service Worker is trying to register** with the old path
3. **Fetch requests are failing** because they're using cached old paths

### Error Details
```
Access to fetch at 'https://errors.infinityfree.net/errors/404/' 
(redirected from 'https://jameschinyamafoodfusion.ct.ws/foodfusion/auth/register.php')
```

The browser is trying to access `/foodfusion/auth/register.php` instead of `/auth/register.php`.

## Solutions Implemented

### 1. Cache Busting (✅ Completed)
Added version parameters to all JavaScript and CSS files to force browser to reload:

**Files Updated:**
- `includes/header.php` - Added `?v=<?php echo time(); ?>` to all CSS and JS files
- `index.php` - Added version parameters to homepage-specific scripts

**Example:**
```php
<script src="<?php echo url('assets/js/main.js'); ?>?v=<?php echo time(); ?>"></script>
```

### 2. Service Worker Cleanup (✅ Completed)
Created two files to handle service worker issues:

**sw.js** - Self-unregistering service worker
- Automatically unregisters itself when loaded
- Clears all caches
- Prevents future service worker issues

**unregister-sw.html** - Manual cleanup page
- Visit: `https://jameschinyamafoodfusion.ct.ws/unregister-sw.html`
- Unregisters all service workers
- Clears all browser caches
- Provides detailed log of cleanup process

### 3. Enhanced Error Handling (✅ Completed)
Updated `assets/js/homepage.js` with:
- Detailed console logging for debugging
- Better error messages
- CORS mode enabled
- Proper URL formatting (removes double slashes)
- Network error detection

### 4. Path Configuration Test (✅ Completed)
Created `test-paths.php` diagnostic page:
- Visit: `https://jameschinyamafoodfusion.ct.ws/test-paths.php`
- Shows environment detection
- Displays BASE_PATH and SITE_URL
- Tests sample URL generation
- Provides test navigation links

## Deployment Steps

### Step 1: Commit and Push Changes
```bash
git add .
git commit -m "Fix: Add cache busting and service worker cleanup"
git push origin main
```

### Step 2: Deploy to Production
Upload the following new/modified files:
- ✅ `includes/header.php` (cache busting)
- ✅ `index.php` (cache busting)
- ✅ `assets/js/homepage.js` (enhanced error handling)
- ✅ `sw.js` (service worker cleanup)
- ✅ `unregister-sw.html` (manual cleanup tool)
- ✅ `test-paths.php` (diagnostic tool)

### Step 3: Clear Service Worker Cache
**Option A: Automatic (Recommended)**
1. Visit: `https://jameschinyamafoodfusion.ct.ws/unregister-sw.html`
2. Wait for "Cleanup complete!" message
3. Close the tab

**Option B: Manual**
1. Open DevTools (F12)
2. Go to Application tab → Service Workers
3. Click "Unregister" on any service workers
4. Go to Application tab → Storage
5. Click "Clear site data"

### Step 4: Clear Browser Cache
1. Hard refresh: `Ctrl + Shift + R` (Windows) or `Cmd + Shift + R` (Mac)
2. Or clear browser cache completely:
   - Chrome: Settings → Privacy → Clear browsing data
   - Select "Cached images and files"
   - Time range: "All time"
   - Click "Clear data"

### Step 5: Verify Fix
1. Visit: `https://jameschinyamafoodfusion.ct.ws/test-paths.php`
   - Verify BASE_PATH shows: `/`
   - Verify SITE_URL shows: `https://jameschinyamafoodfusion.ct.ws`

2. Visit: `https://jameschinyamafoodfusion.ct.ws/`
   - Open DevTools Console (F12)
   - Look for: `window.BASE_PATH` should be `/`
   - No service worker errors should appear

3. Test Registration Popup:
   - Click "Join Us" button
   - Fill out the form
   - Submit
   - Check console for detailed logs
   - Should see: "Attempting registration with URL: /auth/register.php"
   - Should successfully register

## Troubleshooting

### If Registration Still Fails

**Check Console Logs:**
The new code logs detailed information:
```
Attempting registration with URL: /auth/register.php
Base path: /
Full URL: https://jameschinyamafoodfusion.ct.ws/auth/register.php
Response status: 200
Content-Type: application/json
```

**Common Issues:**

1. **Still seeing `/foodfusion/` in URLs**
   - Browser cache not cleared
   - Solution: Visit `unregister-sw.html` and hard refresh

2. **"Failed to fetch" error**
   - Network connectivity issue
   - CORS policy blocking
   - Solution: Check network tab in DevTools

3. **404 errors**
   - File paths incorrect
   - Solution: Run `test-paths.php` to verify configuration

4. **Service Worker errors**
   - Old service worker still registered
   - Solution: Visit `unregister-sw.html`

### If Standalone Page Works But Popup Doesn't

This indicates a JavaScript issue:
1. Check console for errors
2. Verify `window.BASE_PATH` is set correctly
3. Check Network tab to see actual fetch URL
4. Ensure `homepage.js` is loaded (check Sources tab)

## Files Modified Summary

### Core Path System
- ✅ `includes/paths.php` - Dynamic path detection
- ✅ `includes/config.php` - Includes paths.php
- ✅ `includes/header.php` - Cache busting, BASE_PATH injection
- ✅ `includes/footer.php` - Dynamic paths

### JavaScript Files
- ✅ `assets/js/homepage.js` - Enhanced error handling, logging
- ✅ `assets/js/main.js` - Service worker commented out

### New Utility Files
- ✅ `sw.js` - Self-unregistering service worker
- ✅ `unregister-sw.html` - Manual cleanup tool
- ✅ `test-paths.php` - Diagnostic tool

### Page Files
- ✅ `index.php` - Cache busting
- ✅ All other PHP pages - Using url() helper

## Testing Checklist

- [ ] Visit `test-paths.php` - Verify paths are correct
- [ ] Visit `unregister-sw.html` - Clear service workers
- [ ] Hard refresh homepage (Ctrl+Shift+R)
- [ ] Check console - No service worker errors
- [ ] Check console - `window.BASE_PATH` is `/`
- [ ] Test registration popup - Should work
- [ ] Test standalone registration - Should work
- [ ] Test login - Should work
- [ ] Test navigation - All links work
- [ ] Test recipe rating - Should work

## Success Criteria

✅ No service worker errors in console
✅ No `/foodfusion/` paths in URLs
✅ Registration popup works correctly
✅ All navigation links work
✅ All assets load correctly (CSS, JS, images)
✅ Recipe rating system works
✅ Comments system works

## Support

If issues persist after following all steps:
1. Check browser console for specific errors
2. Check Network tab for failed requests
3. Verify all files were uploaded correctly
4. Check server error logs
5. Try a different browser to rule out browser-specific issues

## Notes

- Cache busting uses `time()` which changes every second
- This ensures browsers always fetch the latest version
- Service worker is permanently disabled (commented out)
- All paths now use dynamic `url()` helper function
- System works on any directory structure (localhost or production)

---

**Last Updated:** November 16, 2025
**Status:** Ready for deployment
