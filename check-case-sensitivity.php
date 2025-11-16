<?php
/**
 * Case Sensitivity Checker
 * Finds potential case-sensitivity issues in image paths
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$issues = [];
$checked = 0;

// Function to scan PHP files for image references
function scanForImages($dir, &$issues, &$checked) {
    $files = glob($dir . '/*.php');
    
    foreach ($files as $file) {
        $content = file_get_contents($file);
        
        // Find all image references
        preg_match_all('/["\']assets\/images\/([^"\']+)["\']/', $content, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $imagePath) {
                $checked++;
                $fullPath = __DIR__ . '/assets/images/' . $imagePath;
                
                // Check if file exists (case-sensitive check)
                if (!file_exists($fullPath)) {
                    // Try to find the file with different case
                    $dir = dirname($fullPath);
                    $filename = basename($fullPath);
                    
                    if (is_dir($dir)) {
                        $files = scandir($dir);
                        $found = false;
                        
                        foreach ($files as $f) {
                            if (strtolower($f) === strtolower($filename)) {
                                $issues[] = [
                                    'file' => basename($file),
                                    'expected' => $imagePath,
                                    'actual' => str_replace(basename($fullPath), $f, $imagePath),
                                    'status' => 'case_mismatch'
                                ];
                                $found = true;
                                break;
                            }
                        }
                        
                        if (!$found) {
                            $issues[] = [
                                'file' => basename($file),
                                'expected' => $imagePath,
                                'actual' => null,
                                'status' => 'missing'
                            ];
                        }
                    }
                }
            }
        }
    }
}

// Scan root directory
scanForImages(__DIR__, $issues, $checked);

// Scan auth directory
if (is_dir(__DIR__ . '/auth')) {
    scanForImages(__DIR__ . '/auth', $issues, $checked);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Sensitivity Checker - FoodFusion</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        h1 {
            color: #2d3748;
            margin-bottom: 0.5rem;
        }
        
        .subtitle {
            color: #718096;
            margin-bottom: 2rem;
        }
        
        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
        }
        
        .stat-card.success {
            background: #c6f6d5;
            color: #22543d;
        }
        
        .stat-card.warning {
            background: #feebc8;
            color: #7c2d12;
        }
        
        .stat-card.error {
            background: #fed7d7;
            color: #742a2a;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .issues-list {
            margin-top: 2rem;
        }
        
        .issue-item {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            border-left: 4px solid;
        }
        
        .issue-item.case_mismatch {
            background: #feebc8;
            border-color: #ed8936;
        }
        
        .issue-item.missing {
            background: #fed7d7;
            border-color: #e53e3e;
        }
        
        .issue-file {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }
        
        .issue-path {
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            color: #4a5568;
        }
        
        .issue-fix {
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 2rem;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Case Sensitivity Checker</h1>
        <p class="subtitle">Checking for case-sensitivity issues in image paths</p>
        
        <div class="summary">
            <div class="stat-card <?php echo empty($issues) ? 'success' : 'warning'; ?>">
                <div class="stat-number"><?php echo $checked; ?></div>
                <div class="stat-label">Images Checked</div>
            </div>
            
            <div class="stat-card <?php echo empty($issues) ? 'success' : 'error'; ?>">
                <div class="stat-number"><?php echo count($issues); ?></div>
                <div class="stat-label">Issues Found</div>
            </div>
        </div>
        
        <?php if (empty($issues)): ?>
            <div class="stat-card success" style="padding: 2rem;">
                <h2 style="margin-bottom: 1rem;">✓ All Clear!</h2>
                <p>No case-sensitivity issues found. All image paths are correct.</p>
            </div>
        <?php else: ?>
            <div class="issues-list">
                <h2 style="margin-bottom: 1rem; color: #2d3748;">Issues Found:</h2>
                
                <?php foreach ($issues as $issue): ?>
                    <div class="issue-item <?php echo $issue['status']; ?>">
                        <div class="issue-file">
                            📄 <?php echo htmlspecialchars($issue['file']); ?>
                        </div>
                        <div class="issue-path">
                            <strong>Expected:</strong> <?php echo htmlspecialchars($issue['expected']); ?>
                        </div>
                        <?php if ($issue['actual']): ?>
                            <div class="issue-fix">
                                <strong>Fix:</strong> Change to: <?php echo htmlspecialchars($issue['actual']); ?>
                            </div>
                        <?php else: ?>
                            <div class="issue-fix" style="background: #fed7d7;">
                                <strong>Error:</strong> File not found! Please upload the image.
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <a href="index.php" class="back-link">← Back to Homepage</a>
    </div>
</body>
</html>
