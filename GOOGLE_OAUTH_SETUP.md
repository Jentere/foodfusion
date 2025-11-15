# Google OAuth Setup Instructions

## Step 1: Create Google Cloud Project

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Click on "Select a project" dropdown at the top
3. Click "NEW PROJECT"
4. Enter project name: "FoodFusion" (or your preferred name)
5. Click "CREATE"

## Step 2: Enable Google+ API

1. In the left sidebar, go to "APIs & Services" > "Library"
2. Search for "Google+ API"
3. Click on it and press "ENABLE"

## Step 3: Create OAuth 2.0 Credentials

1. Go to "APIs & Services" > "Credentials"
2. Click "CREATE CREDENTIALS" > "OAuth client ID"
3. If prompted, configure the OAuth consent screen first:
   - Choose "External" user type
   - Fill in:
     - App name: FoodFusion
     - User support email: your email
     - Developer contact: your email
   - Click "SAVE AND CONTINUE"
   - Skip "Scopes" section (click "SAVE AND CONTINUE")
   - Skip "Test users" section (click "SAVE AND CONTINUE")
   - Click "BACK TO DASHBOARD"

4. Now create OAuth client ID:
   - Application type: "Web application"
   - Name: "FoodFusion Web Client"
   
5. Add Authorized JavaScript origins:
   ```
   http://localhost
   http://localhost:80
   http://127.0.0.1
   ```
   
6. Add Authorized redirect URIs:
   ```
   http://localhost/foodfusion/auth/google-callback.php
   http://127.0.0.1/foodfusion/auth/google-callback.php
   ```

7. Click "CREATE"

## Step 4: Copy Your Credentials

After creating, you'll see a popup with:
- **Client ID**: Something like `123456789-abc123def456.apps.googleusercontent.com`
- **Client Secret**: Something like `GOCSPX-abc123def456`

**IMPORTANT**: Copy these values!

## Step 5: Update Configuration File

1. Open `c:\wamp64\www\foodfusion\auth\google-config.php`

2. Replace the placeholder values:
   ```php
   define('GOOGLE_CLIENT_ID', 'YOUR_ACTUAL_CLIENT_ID_HERE');
   define('GOOGLE_CLIENT_SECRET', 'YOUR_ACTUAL_CLIENT_SECRET_HERE');
   ```

3. For local development, the redirect URI is already set to:
   ```php
   define('GOOGLE_REDIRECT_URI', 'http://localhost/foodfusion/auth/google-callback.php');
   ```

## Step 6: Update Database Schema

Run the SQL script to add Google authentication support:

```sql
-- Open phpMyAdmin or your MySQL client
-- Select your foodfusion database
-- Run the following SQL:

ALTER TABLE users 
ADD COLUMN IF NOT EXISTS google_id VARCHAR(255) NULL UNIQUE AFTER email,
ADD COLUMN IF NOT EXISTS profile_picture VARCHAR(500) NULL AFTER google_id,
ADD COLUMN IF NOT EXISTS email_verified TINYINT(1) DEFAULT 0 AFTER profile_picture,
ADD COLUMN IF NOT EXISTS login_method ENUM('email', 'google', 'both') DEFAULT 'email' AFTER email_verified;

CREATE INDEX IF NOT EXISTS idx_google_id ON users(google_id);
CREATE INDEX IF NOT EXISTS idx_email_verified ON users(email_verified);

UPDATE users SET login_method = 'email' WHERE login_method IS NULL;
```

Or simply run the file:
```bash
mysql -u root -p foodfusion_db < database/add_google_auth.sql
```

## Step 7: Test the Integration

1. Start your WAMP server
2. Go to `http://localhost/foodfusion/`
3. Click "Join Us" or "Login"
4. Click "Continue with Google"
5. You should be redirected to Google's login page
6. After logging in, you'll be redirected back to your site

## Troubleshooting

### Error: "redirect_uri_mismatch"
- Make sure the redirect URI in Google Console exactly matches the one in your config
- Check for trailing slashes
- Ensure you're using the correct protocol (http vs https)

### Error: "invalid_client"
- Double-check your Client ID and Client Secret
- Make sure there are no extra spaces when copying

### Error: "Access blocked: This app's request is invalid"
- Make sure you've enabled the Google+ API
- Check that your OAuth consent screen is configured

### Users can't log in
- Verify the database columns were added successfully
- Check PHP error logs: `C:\wamp64\logs\php_error.log`
- Check Apache error logs: `C:\wamp64\logs\apache_error.log`

## For Production Deployment

When deploying to a live server:

1. Update the redirect URI in `google-config.php`:
   ```php
   define('GOOGLE_REDIRECT_URI', 'https://yourdomain.com/foodfusion/auth/google-callback.php');
   ```

2. Add your production domain to Google Console:
   - Authorized JavaScript origins: `https://yourdomain.com`
   - Authorized redirect URIs: `https://yourdomain.com/foodfusion/auth/google-callback.php`

3. Update OAuth consent screen with your production domain

4. Consider moving sensitive credentials to environment variables

## Security Notes

- Never commit `google-config.php` with real credentials to version control
- Use HTTPS in production
- Regularly rotate your Client Secret
- Monitor OAuth usage in Google Console
- Implement rate limiting for authentication endpoints

## Additional Resources

- [Google OAuth 2.0 Documentation](https://developers.google.com/identity/protocols/oauth2)
- [Google Sign-In Best Practices](https://developers.google.com/identity/sign-in/web/sign-in)
- [OAuth 2.0 Security Best Practices](https://tools.ietf.org/html/rfc6749#section-10)
