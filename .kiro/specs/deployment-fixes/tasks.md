# Implementation Plan

- [x] 1. Create path helper system


  - Create `includes/paths.php` with environment detection and URL helper functions
  - Implement `isLocalhost()`, `getBasePath()`, `url()`, and `assetPath()` functions
  - Add security measures to prevent path traversal attacks
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 1.6_



- [ ] 2. Update configuration file
  - Modify `includes/config.php` to include `paths.php` at the top
  - Update `SITE_URL` constant to use dynamic base path


  - Ensure all path-related constants use the path helper
  - _Requirements: 1.1, 1.2_

- [ ] 3. Update header file with dynamic paths
  - Replace all hardcoded `/foodfusion/` paths in `includes/header.php` with `url()` helper calls
  - Update CSS link tags to use dynamic paths


  - Update JavaScript script tags to use dynamic paths
  - Update navigation links to use dynamic paths
  - Update logo image source to use dynamic path
  - _Requirements: 2.1, 2.2, 2.5, 2.6, 3.1, 3.2, 3.3_



- [ ] 4. Update footer file with dynamic paths
  - Replace all hardcoded paths in `includes/footer.php` with `url()` helper calls
  - Update footer navigation links to use dynamic paths
  - Update any asset references to use dynamic paths
  - _Requirements: 3.1, 3.4_



- [ ] 5. Update homepage (index.php)
  - Replace inline CSS/JS includes with `url()` helper calls
  - Update all image sources to use `url()` helper
  - Update all internal links to use `url()` helper



  - Update form actions to use `url()` helper
  - Update any JavaScript redirects to use dynamic paths
  - _Requirements: 2.1, 2.2, 2.3, 2.4, 3.1, 3.5, 4.1, 4.2, 5.1, 5.4_


- [x] 6. Update culinary page

  - Replace inline CSS/JS includes with `url()` helper calls
  - Update all image sources to use `url()` helper
  - Update all download links for PDFs and videos to use `url()` helper

  - Update all internal links to use `url()` helper
  - _Requirements: 2.1, 2.2, 2.3, 2.4, 3.1, 3.5_


- [x] 7. Update recipes page

  - Replace inline CSS/JS includes with `url()` helper calls
  - Update all image sources to use `url()` helper
  - Update all recipe links to use `url()` helper
  - Update form actions to use `url()` helper
  - _Requirements: 2.1, 2.2, 2.3, 2.4, 3.1, 3.5, 4.1, 4.2_



- [ ] 8. Update community page
  - Replace inline CSS/JS includes with `url()` helper calls



  - Update all image sources to use `url()` helper
  - Update all internal links to use `url()` helper
  - _Requirements: 2.1, 2.2, 2.3, 2.4, 3.1, 3.5_

- [x] 9. Update educational page


  - Replace inline CSS/JS includes with `url()` helper calls
  - Update all image sources to use `url()` helper
  - Update all internal links to use `url()` helper
  - _Requirements: 2.1, 2.2, 2.3, 2.4, 3.1, 3.5_






- [ ] 10. Update about page
  - Replace inline CSS/JS includes with `url()` helper calls
  - Update all image sources to use `url()` helper
  - Update all internal links to use `url()` helper


  - _Requirements: 2.1, 2.2, 2.3, 2.4, 3.1, 3.5_

- [ ] 11. Update contact page
  - Replace inline CSS/JS includes with `url()` helper calls
  - Update all image sources to use `url()` helper


  - Update form action to use `url()` helper

  - Update any redirect URLs to use `url()` helper
  - _Requirements: 2.1, 2.2, 2.3, 2.4, 3.1, 3.5, 4.1, 4.2_

- [ ] 12. Update recipe detail page
  - Replace inline CSS/JS includes with `url()` helper calls

  - Update all image sources to use `url()` helper
  - Update all internal links to use `url()` helper
  - _Requirements: 2.1, 2.2, 2.3, 2.4, 3.1, 3.5_

- [ ] 13. Update authentication files
- [ ] 13.1 Update login page (auth/login.php)
  - Replace hardcoded paths with `url()` helper calls
  - Update form action to use `url()` helper




  - Update redirect URLs after successful login to use `url()` helper
  - Update links to other auth pages to use `url()` helper


  - _Requirements: 3.1, 4.1, 4.2, 4.3, 5.1, 5.2, 5.3_

- [x] 13.2 Update register page (auth/register.php)


  - Replace hardcoded paths with `url()` helper calls
  - Update form action to use `url()` helper
  - Update redirect URLs after successful registration to use `url()` helper
  - Update links to other auth pages to use `url()` helper
  - _Requirements: 3.1, 4.1, 4.2, 4.3, 5.1, 5.2, 5.3_

- [ ] 13.3 Update logout page (auth/logout.php)
  - Update redirect URL after logout to use `url()` helper
  - _Requirements: 5.1, 5.2, 5.3_

- [ ] 13.4 Update forgot password page (auth/forgot_password.php)
  - Replace hardcoded paths with `url()` helper calls
  - Update form action to use `url()` helper
  - Update links to other auth pages to use `url()` helper
  - _Requirements: 3.1, 4.1, 4.2, 4.3, 5.1, 5.2, 5.3_

- [ ] 13.5 Update reset password page (auth/reset_password.php)
  - Replace hardcoded paths with `url()` helper calls
  - Update form action to use `url()` helper
  - Update redirect URLs after password reset to use `url()` helper
  - _Requirements: 3.1, 4.1, 4.2, 4.3, 5.1, 5.2, 5.3_

- [ ] 14. Update action files
- [ ] 14.1 Update contact submit action (actions/contact_submit.php)
  - Update redirect URLs after form processing to use `url()` helper




  - _Requirements: 5.1, 5.2, 5.3_

- [ ] 14.2 Update recipe submit action (actions/submit_recipe.php)
  - Update redirect URLs after form processing to use `url()` helper
  - Update any asset references to use `url()` helper
  - _Requirements: 5.1, 5.2, 5.3_

- [ ] 14.3 Update toggle like action (actions/toggle_like.php)
  - Update redirect URLs to use `url()` helper
  - _Requirements: 5.1, 5.2, 5.3_

- [ ] 15. Create configuration test page
  - Create `test-config.php` to validate path configuration
  - Display detected environment (localhost/production)
  - Display configured base path and site URL
  - Test sample URLs for CSS, JS, images, and pages
  - Show color-coded status indicators (green = working, red = issues)
  - Provide diagnostic information for troubleshooting
  - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5_
