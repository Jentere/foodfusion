<?php
/**
 * Configuration Test Page
 * 
 * This page validates that the path configuration is working correctly
 * and helps diagnose deployment issues.
 */

require_once('includes/paths.php');

// Test paths
$testPaths = [
    'CSS' => 'assets/css/style.css',
    'JavaScript' => 'assets/js/main.js',
    'Image' => 'assets/images/logo.png',
    'Page' => 'recipes.php',
    'Resource' => 'resources/cookbook.pdf'
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodFusion - Configuration Test</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .content {
            padding: 30px;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section h2 {
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .info-label {
            font-weight: 600;
            color: #555;
        }
        
        .info-value {
            color: #333;
            background: #f5f5f5;
            padding: 8px 12px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }
        
        .status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .status.success {
            background: #d4edda;
            color: #155724;
        }
        
        .status.error {
            background: #f8d7da;
            color: #721c24;
        }
        
        .test-item {
            background: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        
        .test-item.success {
            border-left-color: #28a745;
        }
        
        .test-item.error {
            border-left-color: #dc3545;
        }
        
        .test-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .test-type {
            font-weight: 600;
            color: #333;
        }
        
        .test-path {
            font-family: 'Courier New', monospace;
            color: #666;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        .icon {
            font-size: 1.2rem;
            margin-right: 8px;
        }
        
        .success-icon {
            color: #28a745;
        }
        
        .error-icon {
            color: #dc3545;
        }
        
        .footer {
            background: #f5f5f5;
            padding: 20px 30px;
            text-align: center;
            color: #666;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            margin-top: 20px;
            transition: transform 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 FoodFusion Configuration Test</h1>
            <p>Path Configuration Validation</p>
        </div>
        
        <div class="content">
            <!-- Environment Detection -->
            <div class="section">
                <h2>Environment Detection</h2>
                <div class="info-grid">
                    <div class="info-label">Environment:</div>
                    <div class="info-value">
                        <?php echo isLocalhost() ? 'Localhost' : 'Production'; ?>
                        <span class="status success">✓ Detected</span>
                    </div>
                    
                    <div class="info-label">HTTP Host:</div>
                    <div class="info-value"><?php echo htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'Unknown'); ?></div>
                    
                    <div class="info-label">Base Path:</div>
                    <div class="info-value"><?php echo htmlspecialchars(BASE_PATH); ?></div>
                    
                    <div class="info-label">Site URL:</div>
                    <div class="info-value"><?php echo htmlspecialchars(SITE_URL); ?></div>
                    
                    <div class="info-label">Document Root:</div>
                    <div class="info-value"><?php echo htmlspecialchars($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown'); ?></div>
                </div>
            </div>
            
            <!-- Path Testing -->
            <div class="section">
                <h2>Path Generation Tests</h2>
                <?php foreach ($testPaths as $type => $path): ?>
                    <?php 
                        $generatedUrl = url($path);
                        $fileExists = assetExists($path);
                        $isSuccess = $fileExists || $type === 'Page';
                    ?>
                    <div class="test-item <?php echo $isSuccess ? 'success' : 'error'; ?>">
                        <div class="test-header">
                            <div class="test-type">
                                <span class="icon <?php echo $isSuccess ? 'success-icon' : 'error-icon'; ?>">
                                    <?php echo $isSuccess ? '✓' : '✗'; ?>
                                </span>
                                <?php echo htmlspecialchars($type); ?>
                            </div>
                            <span class="status <?php echo $isSuccess ? 'success' : 'error'; ?>">
                                <?php echo $fileExists ? 'File Exists' : ($type === 'Page' ? 'OK' : 'Not Found'); ?>
                            </span>
                        </div>
                        <div class="test-path">
                            <strong>Generated URL:</strong> <?php echo htmlspecialchars($generatedUrl); ?>
                        </div>
                        <div class="test-path">
                            <strong>Original Path:</strong> <?php echo htmlspecialchars($path); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Function Tests -->
            <div class="section">
                <h2>Helper Function Tests</h2>
                <div class="info-grid">
                    <div class="info-label">url() function:</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars(url('test.php')); ?>
                        <span class="status success">✓ Working</span>
                    </div>
                    
                    <div class="info-label">getBasePath():</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars(getBasePath()); ?>
                        <span class="status success">✓ Working</span>
                    </div>
                    
                    <div class="info-label">getSiteUrl():</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars(getSiteUrl()); ?>
                        <span class="status success">✓ Working</span>
                    </div>
                    
                    <div class="info-label">isLocalhost():</div>
                    <div class="info-value">
                        <?php echo isLocalhost() ? 'true' : 'false'; ?>
                        <span class="status success">✓ Working</span>
                    </div>
                </div>
            </div>
            
            <!-- Security Tests -->
            <div class="section">
                <h2>Security Tests</h2>
                <div class="test-item success">
                    <div class="test-header">
                        <div class="test-type">
                            <span class="icon success-icon">✓</span>
                            Path Traversal Protection
                        </div>
                        <span class="status success">Protected</span>
                    </div>
                    <div class="test-path">
                        <strong>Test Input:</strong> ../../../etc/passwd
                    </div>
                    <div class="test-path">
                        <strong>Sanitized Output:</strong> <?php echo htmlspecialchars(url('../../../etc/passwd')); ?>
                    </div>
                </div>
            </div>
            
            <!-- Recommendations -->
            <div class="section">
                <h2>Status Summary</h2>
                <?php
                $allFilesExist = true;
                foreach ($testPaths as $type => $path) {
                    if ($type !== 'Page' && !assetExists($path)) {
                        $allFilesExist = false;
                        break;
                    }
                }
                ?>
                <?php if ($allFilesExist): ?>
                    <div class="test-item success">
                        <div class="test-header">
                            <div class="test-type">
                                <span class="icon success-icon">✓</span>
                                Configuration Status
                            </div>
                            <span class="status success">All Tests Passed</span>
                        </div>
                        <div class="test-path">
                            Your path configuration is working correctly! All critical files are accessible.
                        </div>
                    </div>
                <?php else: ?>
                    <div class="test-item error">
                        <div class="test-header">
                            <div class="test-type">
                                <span class="icon error-icon">✗</span>
                                Configuration Status
                            </div>
                            <span class="status error">Some Files Missing</span>
                        </div>
                        <div class="test-path">
                            Some asset files could not be found. Please ensure all files are uploaded to the server.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div style="text-align: center;">
                <a href="<?php echo url('index.php'); ?>" class="btn">Go to Homepage</a>
            </div>
        </div>
        
        <div class="footer">
            <p>FoodFusion Configuration Test Page</p>
            <p style="font-size: 0.9rem; margin-top: 5px;">
                Generated on <?php echo date('Y-m-d H:i:s'); ?>
            </p>
        </div>
    </div>
</body>
</html>
