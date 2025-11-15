# Design Document

## Overview

This design document outlines the solution for fixing deployment path issues in the FoodFusion application. The core problem is that the application uses hardcoded absolute paths (e.g., `/foodfusion/`) that work on localhost but fail on production servers where the application is deployed at the root level.

The solution implements a centralized path management system that automatically detects the deployment environment and generates correct URLs for all assets, links, and redirects.

## Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    Application Layer                     │
│  (PHP Pages: index.php, culinary.php, recipes.php, etc.)│
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                  Path Helper System                      │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │ Environment  │  │  Base Path   │  │ URL Builder  │  │
│  │  Detection   │─▶│  Constants   │─▶│  Functions   │  │
│  └──────────────┘  └──────────────┘  └──────────────┘  │
└─────────────────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              Configuration Layer                         │
│         (includes/config.php, includes/paths.php)        │
└─────────────────────────────────────────────────────────┘
```

### Design Principles

1. **Single Source of Truth**: All path configuration is centralized in one file
2. **Environment Agnostic**: Code works identically on localhost and production
3. **Minimal Changes**: Existing code structure is preserved where possible
4. **Backward Compatible**: Changes don't break existing functionality
5. **Easy to Maintain**: Clear, simple helper functions

## Components and Interfaces

### 1. Path Configuration File (`includes/paths.php`)

**Purpose**: Centralized path management and helper functions

**Key Functions**:

```php
// Detect if running on localhost
function isLocalhost(): bool

// Get the base path for the application
function getBasePath(): string

// Generate a full URL for an asset or page
function url(string $path): string

// Generate an absolute file system path
function assetPath(string $path): string
```

**Constants**:
- `BASE_PATH`: The root URL path (e.g., `/foodfusion/` or `/`)
- `SITE_URL`: The full site URL including protocol and domain

**Environment Detection Logic**:
```php
if (in_array($_SERVER['HTTP_HOST'] ?? 'localhost', ['localhost', '127.0.0.1'])) {
    // Localhost environment
    define('BASE_PATH', '/foodfusion/');
} else {
    // Production environment
    define('BASE_PATH', '/');
}
```

### 2. Updated Configuration File (`includes/config.php`)

**Changes**:
- Include `paths.php` at the top
- Update `SITE_URL` to use dynamic base path
- Ensure all path-related constants use the path helper

### 3. Updated Header File (`includes/header.php`)

**Changes**:
- Replace hardcoded `/foodfusion/` paths with `url()` helper calls
- Update CSS link tags: `<link rel="stylesheet" href="<?php echo url('assets/css/style.css'); ?>">`
- Update navigation links: `<a href="<?php echo url('index.php'); ?>">`
- Update logo image: `<img src="<?php echo url('assets/images/logo.png'); ?>">`
- Update JavaScript includes: `<script src="<?php echo url('assets/js/main.js'); ?>"></script>`

### 4. Updated Footer File (`includes/footer.php`)

**Changes**:
- Replace hardcoded paths in footer links with `url()` helper calls
- Update social media links if they reference internal pages

### 5. Updated Page Files

**Files to Update**:
- `index.php`
- `culinary.php`
- `recipes.php`
- `community.php`
- `educational.php`
- `about.php`
- `contact.php`
- `recipe.php`

**Changes per File**:
- Replace inline CSS/JS includes with `url()` helper
- Update image sources: `<img src="<?php echo url('assets/images/recipe1.jpg'); ?>">`
- Update download links: `<a href="<?php echo url('resources/cookbook.pdf'); ?>">`
- Update form actions: `<form action="<?php echo url('actions/submit_recipe.php'); ?>">`
- Update redirect URLs in PHP: `header('Location: ' . url('index.php'));`

### 6. Updated Authentication Files

**Files to Update**:
- `auth/login.php`
- `auth/register.php`
- `auth/logout.php`
- `auth/forgot_password.php`
- `auth/reset_password.php`

**Changes**:
- Update form actions
- Update redirect URLs after successful login/registration
- Update links to other auth pages

### 7. Updated Action Files

**Files to Update**:
- `actions/contact_submit.php`
- `actions/submit_recipe.php`
- `actions/toggle_like.php`

**Changes**:
- Update redirect URLs after form processing
- Update any asset references

### 8. Configuration Test Page (`test-config.php`)

**Purpose**: Validate that paths are correctly configured

**Features**:
- Display detected environment (localhost/production)
- Display base path
- Test sample URLs for assets
- Show color-coded status (green = working, red = issues)
- Provide diagnostic information

**Sample Output**:
```
Environment Detection
✓ Environment: Production
✓ Base Path: /
✓ Site URL: https://jameschinyamafoodfusion.ct.ws

Path Testing
✓ CSS Path: /assets/css/style.css
✓ JS Path: /assets/js/main.js
✓ Image Path: /assets/images/logo.png
✓ Page Path: /recipes.php
```

## Data Models

No database schema changes are required. This is purely a configuration and URL generation update.

## Error Handling

### Missing Path Configuration

**Scenario**: `paths.php` is not included
**Handling**: 
- Display clear error message
- Prevent page from loading
- Log error to PHP error log

```php
if (!defined('BASE_PATH')) {
    die('Error: Path configuration not loaded. Please ensure includes/paths.php is included.');
}
```

### Invalid Environment Detection

**Scenario**: Unable to detect environment
**Handling**:
- Default to production settings (safer)
- Log warning
- Continue execution

### File Not Found

**Scenario**: Referenced asset doesn't exist
**Handling**:
- Browser will show 404 for missing assets
- No special handling needed (standard web behavior)
- Can add optional file existence check in development mode

## Testing Strategy

### Manual Testing Checklist

1. **Localhost Testing**:
   - Verify all pages load with CSS
   - Verify all navigation links work
   - Verify all images display
   - Verify forms submit correctly
   - Verify redirects work

2. **Production Testing**:
   - Deploy to InfinityFree
   - Run configuration test page
   - Verify all pages load with CSS
   - Verify all navigation links work
   - Verify all images display
   - Verify forms submit correctly
   - Verify redirects work

3. **Cross-Page Testing**:
   - Test navigation from every page to every other page
   - Test form submissions from different pages
   - Test logout and login flows

### Test Cases

**Test Case 1: CSS Loading**
- Navigate to homepage
- Expected: All styles applied correctly
- Verify: Inspect network tab shows CSS files loaded with 200 status

**Test Case 2: Navigation Links**
- Click each navigation menu item
- Expected: Correct page loads without 404 error
- Verify: URL in browser matches expected path

**Test Case 3: Image Loading**
- Navigate to pages with images (recipes, culinary)
- Expected: All images display correctly
- Verify: Inspect network tab shows images loaded with 200 status

**Test Case 4: Form Submission**
- Submit contact form
- Expected: Form processes and redirects correctly
- Verify: Data is saved and user sees success message

**Test Case 5: Authentication Flow**
- Register new account
- Login with credentials
- Logout
- Expected: All redirects work correctly
- Verify: User session is managed properly

### Automated Testing

While full automated testing is optional, a simple PHP script can validate paths:

```php
// test-paths.php
$paths_to_test = [
    'assets/css/style.css',
    'assets/js/main.js',
    'assets/images/logo.png',
    'index.php',
    'recipes.php'
];

foreach ($paths_to_test as $path) {
    $full_path = url($path);
    echo "Testing: $full_path - ";
    // Check if file exists
    $file_path = __DIR__ . '/' . $path;
    echo file_exists($file_path) ? "✓ EXISTS" : "✗ MISSING";
    echo "\n";
}
```

## Implementation Notes

### Order of Implementation

1. Create `includes/paths.php` with helper functions
2. Update `includes/config.php` to include paths.php
3. Update `includes/header.php` (affects all pages)
4. Update `includes/footer.php` (affects all pages)
5. Update individual page files (index.php, culinary.php, etc.)
6. Update authentication files
7. Update action files
8. Create test-config.php for validation
9. Test on localhost
10. Deploy and test on production

### Migration Strategy

To minimize risk, implement changes incrementally:

1. **Phase 1**: Create path helper system (no breaking changes)
2. **Phase 2**: Update header and footer (affects all pages but easy to rollback)
3. **Phase 3**: Update individual pages one at a time
4. **Phase 4**: Update authentication and action files
5. **Phase 5**: Final testing and validation

### Rollback Plan

If issues occur:
1. Keep backup of original files
2. Path helper is additive, so removing it won't break old code
3. Can revert individual files without affecting others
4. Git version control recommended for easy rollback

## Performance Considerations

- **Minimal Overhead**: Helper functions are simple string operations
- **No Database Impact**: Changes are purely URL generation
- **Caching**: Browser caching of assets unaffected
- **No Additional HTTP Requests**: Same number of requests as before

## Security Considerations

- **Path Traversal**: Helper functions should not allow `../` in paths
- **XSS Prevention**: All output should be HTML-escaped when used in attributes
- **Input Validation**: Validate paths before using in file operations

```php
function url(string $path): string {
    // Remove any path traversal attempts
    $path = str_replace(['../', '..\\'], '', $path);
    // Remove leading slash if present
    $path = ltrim($path, '/');
    return BASE_PATH . $path;
}
```

## Deployment Checklist

Before deploying to production:

- [ ] All files updated with path helpers
- [ ] Tested on localhost
- [ ] Configuration test page created
- [ ] Database credentials updated in config.php
- [ ] File permissions set correctly on server
- [ ] .htaccess file configured (if needed)
- [ ] Error reporting disabled in production
- [ ] Backup of current production files created

After deploying to production:

- [ ] Run configuration test page
- [ ] Test all major pages
- [ ] Test authentication flow
- [ ] Test form submissions
- [ ] Check browser console for errors
- [ ] Verify CSS and JS loading in network tab
