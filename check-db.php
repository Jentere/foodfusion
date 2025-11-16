<?php
/**
 * Database Connection Checker
 * Use this to test and configure your database connection
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if config file exists
$config_exists = file_exists(__DIR__ . '/includes/config.php');

// Get form data if submitted
$test_host = $_POST['test_host'] ?? 'localhost';
$test_user = $_POST['test_user'] ?? '';
$test_pass = $_POST['test_pass'] ?? '';
$test_name = $_POST['test_name'] ?? '';
$test_result = null;

// Test connection if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($test_user)) {
    $test_result = testConnection($test_host, $test_user, $test_pass, $test_name);
}

function testConnection($host, $user, $pass, $dbname) {
    $result = [
        'success' => false,
        'message' => '',
        'details' => []
    ];
    
    try {
        // Try connecting
        $conn = @new mysqli($host, $user, $pass, $dbname);
        
        if ($conn->connect_error) {
            $result['message'] = 'Connection failed: ' . $conn->connect_error;
            $result['details'][] = 'Error code: ' . $conn->connect_errno;
            
            // Try alternative host
            if ($host === 'localhost') {
                $result['details'][] = 'Trying 127.0.0.1 instead...';
                $conn = @new mysqli('127.0.0.1', $user, $pass, $dbname);
                
                if ($conn->connect_error) {
                    $result['details'][] = '127.0.0.1 also failed: ' . $conn->connect_error;
                } else {
                    $result['success'] = true;
                    $result['message'] = 'Connection successful using 127.0.0.1!';
                    $result['details'][] = 'Use "127.0.0.1" as DB_HOST in your config.php';
                    $conn->close();
                }
            }
        } else {
            $result['success'] = true;
            $result['message'] = 'Connection successful!';
            $result['details'][] = 'Server version: ' . $conn->server_info;
            $result['details'][] = 'Character set: ' . $conn->character_set_name();
            
            // Test if database exists
            $db_check = $conn->select_db($dbname);
            if ($db_check) {
                $result['details'][] = 'Database "' . $dbname . '" exists and is accessible';
                
                // Check for tables
                $tables_result = $conn->query("SHOW TABLES");
                if ($tables_result) {
                    $table_count = $tables_result->num_rows;
                    $result['details'][] = 'Found ' . $table_count . ' tables in database';
                }
            } else {
                $result['details'][] = 'Warning: Database "' . $dbname . '" not found or not accessible';
            }
            
            $conn->close();
        }
    } catch (Exception $e) {
        $result['message'] = 'Exception: ' . $e->getMessage();
    }
    
    return $result;
}

// Try to read current config
$current_config = [];
if ($config_exists) {
    $config_content = file_get_contents(__DIR__ . '/includes/config.php');
    preg_match("/define\('DB_HOST',\s*'([^']*)'\)/", $config_content, $host_match);
    preg_match("/define\('DB_USER',\s*'([^']*)'\)/", $config_content, $user_match);
    preg_match("/define\('DB_NAME',\s*'([^']*)'\)/", $config_content, $name_match);
    
    $current_config = [
        'host' => $host_match[1] ?? 'localhost',
        'user' => $user_match[1] ?? '',
        'name' => $name_match[1] ?? ''
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Checker - FoodFusion</title>
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
            max-width: 800px;
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
        
        .section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f7fafc;
            border-radius: 10px;
        }
        
        .section h2 {
            color: #2d3748;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #4a5568;
            font-weight: 600;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn {
            background: #667eea;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }
        
        .result {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid;
        }
        
        .result.success {
            background: #c6f6d5;
            border-color: #38a169;
            color: #22543d;
        }
        
        .result.error {
            background: #fed7d7;
            border-color: #e53e3e;
            color: #742a2a;
        }
        
        .result h3 {
            margin-bottom: 0.5rem;
        }
        
        .result ul {
            margin-left: 1.5rem;
            margin-top: 0.5rem;
        }
        
        .info-box {
            background: #bee3f8;
            border-left: 4px solid #3182ce;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .info-box h3 {
            color: #2c5282;
            margin-bottom: 0.5rem;
        }
        
        .info-box p {
            color: #2c5282;
            line-height: 1.6;
        }
        
        .code {
            background: #2d3748;
            color: #68d391;
            padding: 1rem;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            overflow-x: auto;
            margin-top: 0.5rem;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 2rem;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Database Connection Checker</h1>
        <p class="subtitle">Test and configure your database connection</p>
        
        <?php if ($config_exists): ?>
        <div class="info-box">
            <h3>Current Configuration</h3>
            <p>
                <strong>Host:</strong> <?php echo htmlspecialchars($current_config['host']); ?><br>
                <strong>User:</strong> <?php echo htmlspecialchars($current_config['user']); ?><br>
                <strong>Database:</strong> <?php echo htmlspecialchars($current_config['name']); ?>
            </p>
        </div>
        <?php endif; ?>
        
        <div class="section">
            <h2>Test Database Connection</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="test_host">Database Host:</label>
                    <input type="text" id="test_host" name="test_host" 
                           value="<?php echo htmlspecialchars($test_host); ?>" 
                           placeholder="localhost or 127.0.0.1">
                </div>
                
                <div class="form-group">
                    <label for="test_user">Database Username:</label>
                    <input type="text" id="test_user" name="test_user" 
                           value="<?php echo htmlspecialchars($test_user); ?>" 
                           placeholder="Your database username" required>
                </div>
                
                <div class="form-group">
                    <label for="test_pass">Database Password:</label>
                    <input type="password" id="test_pass" name="test_pass" 
                           value="<?php echo htmlspecialchars($test_pass); ?>" 
                           placeholder="Your database password">
                </div>
                
                <div class="form-group">
                    <label for="test_name">Database Name:</label>
                    <input type="text" id="test_name" name="test_name" 
                           value="<?php echo htmlspecialchars($test_name); ?>" 
                           placeholder="Your database name" required>
                </div>
                
                <button type="submit" class="btn">Test Connection</button>
            </form>
            
            <?php if ($test_result): ?>
            <div class="result <?php echo $test_result['success'] ? 'success' : 'error'; ?>">
                <h3><?php echo $test_result['success'] ? '✓ Success!' : '✗ Connection Failed'; ?></h3>
                <p><?php echo htmlspecialchars($test_result['message']); ?></p>
                <?php if (!empty($test_result['details'])): ?>
                <ul>
                    <?php foreach ($test_result['details'] as $detail): ?>
                    <li><?php echo htmlspecialchars($detail); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                
                <?php if ($test_result['success']): ?>
                <div class="code">
define('DB_HOST', '<?php echo htmlspecialchars($test_host); ?>');<br>
define('DB_USER', '<?php echo htmlspecialchars($test_user); ?>');<br>
define('DB_PASS', '<?php echo htmlspecialchars($test_pass); ?>');<br>
define('DB_NAME', '<?php echo htmlspecialchars($test_name); ?>');
                </div>
                <p style="margin-top: 1rem;">Copy the above configuration to your <code>includes/config.php</code> file.</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="section">
            <h2>Common Issues & Solutions</h2>
            <ul style="line-height: 2; color: #4a5568;">
                <li><strong>"No such file or directory"</strong> - Try using '127.0.0.1' instead of 'localhost'</li>
                <li><strong>"Access denied"</strong> - Check your username and password</li>
                <li><strong>"Unknown database"</strong> - Verify the database name in your hosting control panel</li>
                <li><strong>On InfinityFree:</strong> Database host is usually 'localhost' or 'sql###.infinityfree.com'</li>
                <li><strong>Database name format:</strong> Often includes your account ID (e.g., 'if0_12345678_dbname')</li>
            </ul>
        </div>
        
        <a href="index.php" class="back-link">← Back to Homepage</a>
    </div>
</body>
</html>
