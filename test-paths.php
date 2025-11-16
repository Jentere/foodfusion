<?php
/**
 * Path Configuration Test Page
 * Use this to verify that paths are correctly configured
 */

// Enable error display for testing
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('includes/paths.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Path Configuration Test - FoodFusion</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            min-height: 100vh;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        h1 {
            color: #2d3748;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }
        
        .subtitle {
            color: #718096;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        
        .section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f7fafc;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        
        .section h2 {
            color: #2d3748;
            margin-bottom: 1rem;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .status.success {
            background: #c6f6d5;
            color: #22543d;
        }
        
        .status.error {
            background: #fed7d7;
            color: #742a2a;
        }
        
        .info-grid {
            display: grid;
            gap: 1rem;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .info-label {
            font-weight: 600;
            color: #4a5568;
        }
        
        .info-value {
            color: #2d3748;
            font-family: 'Courier New', monospace;
            background: #edf2f7;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            word-break: break-all;
        }
        
        .test-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .test-link {
            display: block;
            padding: 1rem;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            text-decoration: none;
            color: #667eea;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .test-link:hover {
            border-color: #667eea;
            background: #f7fafc;
            transform: translateY(-2px);
        }
        
        .back-btn {
            display: inline-block;
            margin-top: 2rem;
            padding: 0.75rem 1.5rem;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Path Configuration Test</h1>
        <p class="subtitle">FoodFusion Application</p>
        
        <!-- Environment Detection -->
        <div class="section">
            <h2>
                🌍 Environment Detection
                <span class="status success">Active</span>
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Environment:</span>
                    <span class="info-value"><?php echo isLocalhost() ? 'Localhost' : 'Production'; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">HTTP Host:</span>
                    <span class="info-value"><?php echo htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'N/A'); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Server Name:</span>
                    <span class="info-value"><?php echo htmlspecialchars($_SERVER['SERVER_NAME'] ?? 'N/A'); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Protocol:</span>
                    <span class="info-value"><?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'HTTPS' : 'HTTP'; ?></span>
                </div>
            </div>
        </div>
        
        <!-- Path Configuration -->
        <div class="section">
            <h2>
                📁 Path Configuration
                <span class="status success">Configured</span>
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">BASE_PATH:</span>
                    <span class="info-value"><?php echo htmlspecialchars(BASE_PATH); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">SITE_URL:</span>
                    <span class="info-value"><?php echo htmlspecialchars(SITE_URL); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Script Name:</span>
                    <span class="info-value"><?php echo htmlspecialchars($_SERVER['SCRIPT_NAME'] ?? 'N/A'); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Document Root:</span>
                    <span class="info-value"><?php echo htmlspecialchars($_SERVER['DOCUMENT_ROOT'] ?? 'N/A'); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Sample URLs -->
        <div class="section">
            <h2>
                🔗 Sample URL Generation
                <span class="status success">Working</span>
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Homepage:</span>
                    <span class="info-value"><?php echo htmlspecialchars(url('index.php')); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">CSS File:</span>
                    <span class="info-value"><?php echo htmlspecialchars(url('assets/css/style.css')); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">JS File:</span>
                    <span class="info-value"><?php echo htmlspecialchars(url('assets/js/main.js')); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Image:</span>
                    <span class="info-value"><?php echo htmlspecialchars(url('assets/images/logo.png')); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Login Page:</span>
                    <span class="info-value"><?php echo htmlspecialchars(url('auth/login.php')); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Test Links -->
        <div class="section">
            <h2>🧪 Test Navigation</h2>
            <div class="test-links">
                <a href="<?php echo url('index.php'); ?>" class="test-link">Homepage</a>
                <a href="<?php echo url('about.php'); ?>" class="test-link">About</a>
                <a href="<?php echo url('recipes.php'); ?>" class="test-link">Recipes</a>
                <a href="<?php echo url('community.php'); ?>" class="test-link">Community</a>
                <a href="<?php echo url('contact.php'); ?>" class="test-link">Contact</a>
                <a href="<?php echo url('auth/login.php'); ?>" class="test-link">Login</a>
            </div>
        </div>
        
        <!-- PHP Info -->
        <div class="section">
            <h2>ℹ️ PHP Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">PHP Version:</span>
                    <span class="info-value"><?php echo phpversion(); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Server Software:</span>
                    <span class="info-value"><?php echo htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'N/A'); ?></span>
                </div>
            </div>
        </div>
        
        <a href="<?php echo url('index.php'); ?>" class="back-btn">← Back to Homepage</a>
    </div>
</body>
</html>
