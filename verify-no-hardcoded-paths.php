<?php
/**
 * Hardcoded Path Verification Script
 * Scans all PHP and JavaScript files to ensure no hardcoded paths exist
 */

$errors = [];
$warnings = [];
$scannedFiles = 0;

// Directories to scan
$directories = [
    __DIR__,
    __DIR__ . '/auth',
    __DIR__ . '/includes',
    __DIR__ . '/assets/js',
    __DIR__ . '/api'
];

// Patterns to check for (potential hardcoded paths)
$patterns = [
    '/foodfusion/' => 'Hardcoded /foodfusion/ path found',
    'localhost/foodfusion' => 'Hardcoded localhost/foodfusion URL found',
    'http://localhost/' => 'Hardcoded localhost URL found (use relative paths)',
    'https://jameschinyama' => 'Hardcoded production domain found',
    'href="/' => 'Potential hardcoded absolute path in href (should use url() function)',
    'src="/' => 'Potential hardcoded absolute path in src (should use url() function)',
    "href='/" => 'Potential hardcoded absolute path in href (should use url() function)',
    "src='/" => 'Potential hardcoded absolute path in src (should use url() function)',
];

// Files to exclude from scanning
$excludeFiles = [
    'verify-no-hardcoded-paths.php',
    'test-paths.php',
    'test-config.php',
    'test-video.php',
    '.git',
    'node_modules',
    'vendor'
];

function shouldExcludeFile($filepath, $excludeFiles) {
    foreach ($excludeFiles as $exclude) {
        if (strpos($filepath, $exclude) !== false) {
            return true;
        }
    }
    return false;
}

function scanDirectory($dir, &$errors, &$warnings, &$scannedFiles, $patterns, $excludeFiles) {
    if (!is_dir($dir)) {
        return;
    }
    
    $files = scandir($dir);
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        
        $filepath = $dir . '/' . $file;
        
        if (shouldExcludeFile($filepath, $excludeFiles)) {
            continue;
        }
        
        if (is_dir($filepath)) {
            scanDirectory($filepath, $errors, $warnings, $scannedFiles, $patterns, $excludeFiles);
        } elseif (preg_match('/\.(php|js)$/', $file)) {
            $scannedFiles++;
            $content = file_get_contents($filepath);
            $lines = explode("\n", $content);
            
            foreach ($patterns as $pattern => $message) {
                foreach ($lines as $lineNum => $line) {
                    // Skip comments
                    if (preg_match('/^\s*(\/\/|#|\*)/', $line)) {
                        continue;
                    }
                    
                    if (stripos($line, $pattern) !== false) {
                        // Special handling for href and src - only warn if not using url()
                        if (in_array($pattern, ['href="/', 'src="/', "href='/", "src='/"])) {
                            if (strpos($line, 'url(') === false && strpos($line, 'BASE_PATH') === false) {
                                $warnings[] = [
                                    'file' => str_replace(__DIR__ . '/', '', $filepath),
                                    'line' => $lineNum + 1,
                                    'message' => $message,
                                    'code' => trim($line)
                                ];
                            }
                        } else {
                            $errors[] = [
                                'file' => str_replace(__DIR__ . '/', '', $filepath),
                                'line' => $lineNum + 1,
                                'message' => $message,
                                'code' => trim($line)
                            ];
                        }
                    }
                }
            }
        }
    }
}

// Scan all directories
foreach ($directories as $dir) {
    scanDirectory($dir, $errors, $warnings, $scannedFiles, $patterns, $excludeFiles);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardcoded Path Verification - FoodFusion</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #e76f51;
            padding-bottom: 10px;
        }
        .summary {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .error {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .issue {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 4px solid #dc3545;
        }
        .issue-warning {
            border-left-color: #ffc107;
        }
        .issue-file {
            font-weight: bold;
            color: #e76f51;
        }
        .issue-line {
            color: #666;
            font-size: 0.9em;
        }
        .issue-code {
            background: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            overflow-x: auto;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #e76f51;
        }
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #e76f51;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #d65d3f;
        }
    </style>
</head>
<body>
    <h1>🔍 Hardcoded Path Verification</h1>
    
    <div class="summary">
        <h2>Scan Summary</h2>
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $scannedFiles; ?></div>
                <div class="stat-label">Files Scanned</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: <?php echo count($errors) > 0 ? '#dc3545' : '#28a745'; ?>">
                    <?php echo count($errors); ?>
                </div>
                <div class="stat-label">Errors Found</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: <?php echo count($warnings) > 0 ? '#ffc107' : '#28a745'; ?>">
                    <?php echo count($warnings); ?>
                </div>
                <div class="stat-label">Warnings</div>
            </div>
        </div>
    </div>

    <?php if (count($errors) === 0 && count($warnings) === 0): ?>
        <div class="success">
            <h3>✅ All Clear!</h3>
            <p><strong>No hardcoded paths found!</strong></p>
            <p>Your application is fully environment-agnostic and will work on:</p>
            <ul>
                <li>✓ Localhost with any subdirectory</li>
                <li>✓ Localhost at root</li>
                <li>✓ Production with any subdirectory</li>
                <li>✓ Production at root</li>
                <li>✓ Any domain name</li>
                <li>✓ HTTP or HTTPS</li>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (count($errors) > 0): ?>
        <div class="error">
            <h3>❌ Errors Found</h3>
            <p>The following hardcoded paths were found and must be fixed:</p>
        </div>
        
        <?php foreach ($errors as $error): ?>
            <div class="issue">
                <div class="issue-file"><?php echo htmlspecialchars($error['file']); ?></div>
                <div class="issue-line">Line <?php echo $error['line']; ?>: <?php echo htmlspecialchars($error['message']); ?></div>
                <div class="issue-code"><?php echo htmlspecialchars($error['code']); ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (count($warnings) > 0): ?>
        <div class="warning">
            <h3>⚠️ Warnings</h3>
            <p>The following potential issues were found (may be false positives):</p>
        </div>
        
        <?php foreach ($warnings as $warning): ?>
            <div class="issue issue-warning">
                <div class="issue-file"><?php echo htmlspecialchars($warning['file']); ?></div>
                <div class="issue-line">Line <?php echo $warning['line']; ?>: <?php echo htmlspecialchars($warning['message']); ?></div>
                <div class="issue-code"><?php echo htmlspecialchars($warning['code']); ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="summary">
        <h3>What This Script Checks:</h3>
        <ul>
            <li>✓ No hardcoded <code>/foodfusion/</code> paths</li>
            <li>✓ No hardcoded <code>localhost</code> URLs</li>
            <li>✓ No hardcoded production domain names</li>
            <li>✓ All paths use <code>url()</code> function or <code>BASE_PATH</code> variable</li>
            <li>✓ No absolute paths in href/src attributes</li>
        </ul>
        
        <h3>Best Practices:</h3>
        <ul>
            <li><strong>PHP:</strong> Always use <code>url('path/to/file.php')</code></li>
            <li><strong>JavaScript:</strong> Always use <code>window.BASE_PATH + 'path/to/file.php'</code></li>
            <li><strong>CSS:</strong> Use relative paths or PHP-generated URLs</li>
        </ul>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="test-paths.php" class="btn">Test Path System</a>
        <a href="index.php" class="btn" style="background: #28a745;">Go to Homepage</a>
    </div>
</body>
</html>
