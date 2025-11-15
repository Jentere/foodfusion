<?php
session_start();
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$error = '';
$success = '';

// Function to check system requirements
function checkRequirements() {
    $requirements = array();
    
    // PHP Version
    $requirements['php_version'] = version_compare(PHP_VERSION, '7.4.0', '>=');
    
    // Extensions
    $requirements['mysqli'] = extension_loaded('mysqli');
    $requirements['pdo'] = extension_loaded('pdo');
    $requirements['gd'] = extension_loaded('gd');
    
    // Directory Permissions
    $requirements['uploads'] = is_writable('../uploads');
    $requirements['resources'] = is_writable('../resources');
    
    return $requirements;
}

// Function to test database connection
function testDatabaseConnection($host, $username, $password, $database) {
    try {
        $conn = new mysqli($host, $username, $password);
        if ($conn->connect_error) {
            return false;
        }
        return true;
    } catch(Exception $e) {
        return false;
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['check_requirements'])) {
        $requirements = checkRequirements();
        if (!in_array(false, $requirements, true)) {
            $_SESSION['requirements_met'] = true;
            header('Location: ?step=2');
            exit;
        } else {
            $error = 'Please fix the requirements before continuing.';
        }
    }
    
    if (isset($_POST['database_setup'])) {
        $host = $_POST['db_host'];
        $username = $_POST['db_username'];
        $password = $_POST['db_password'];
        $database = $_POST['db_name'];
        
        if (testDatabaseConnection($host, $username, $password, $database)) {
            // Save database config
            $config_content = "<?php\n";
            $config_content .= "define('DB_HOST', '" . addslashes($host) . "');\n";
            $config_content .= "define('DB_USER', '" . addslashes($username) . "');\n";
            $config_content .= "define('DB_PASS', '" . addslashes($password) . "');\n";
            $config_content .= "define('DB_NAME', '" . addslashes($database) . "');\n";
            
            if (file_put_contents('../includes/config.php', $config_content)) {
                $_SESSION['db_configured'] = true;
                header('Location: ?step=3');
                exit;
            } else {
                $error = 'Could not write configuration file.';
            }
        } else {
            $error = 'Could not connect to database.';
        }
    }
    
    if (isset($_POST['finalize_installation'])) {
        // Import database schema and initial data
        require_once('../includes/config.php');
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
        
        if ($conn->select_db(DB_NAME)) {
            // Import the SQL file
            $sql = file_get_contents('../database/schema.sql');
            if ($conn->multi_query($sql)) {
                do {
                    if ($result = $conn->store_result()) {
                        $result->free();
                    }
                } while ($conn->next_result());
            }
            
            // Import initial data
            require_once('../database/seed.php');
            seedInitialData($conn);
            
            // Create installation lock file
            file_put_contents('../install/.lock', date('Y-m-d H:i:s'));
            
            header('Location: ?step=4');
            exit;
        } else {
            $error = 'Could not select database.';
        }
    }
}

// Check if already installed
if (file_exists('../install/.lock')) {
    die('FoodFusion is already installed. Remove the install/.lock file to reinstall.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodFusion Installation</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .install-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .step {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="install-container">
        <h1>FoodFusion Installation</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($step === 1): ?>
            <div class="step">
                <h2>Step 1: System Requirements</h2>
                <?php $requirements = checkRequirements(); ?>
                <ul>
                    <li>PHP Version >= 7.4: <?php echo $requirements['php_version'] ? '✅' : '❌'; ?></li>
                    <li>MySQLi Extension: <?php echo $requirements['mysqli'] ? '✅' : '❌'; ?></li>
                    <li>PDO Extension: <?php echo $requirements['pdo'] ? '✅' : '❌'; ?></li>
                    <li>GD Extension: <?php echo $requirements['gd'] ? '✅' : '❌'; ?></li>
                    <li>Uploads Directory Writable: <?php echo $requirements['uploads'] ? '✅' : '❌'; ?></li>
                    <li>Resources Directory Writable: <?php echo $requirements['resources'] ? '✅' : '❌'; ?></li>
                </ul>
                <form method="post">
                    <input type="submit" name="check_requirements" value="Continue">
                </form>
            </div>
            
        <?php elseif ($step === 2): ?>
            <div class="step">
                <h2>Step 2: Database Configuration</h2>
                <form method="post">
                    <div>
                        <label>Database Host:</label>
                        <input type="text" name="db_host" value="localhost" required>
                    </div>
                    <div>
                        <label>Database Username:</label>
                        <input type="text" name="db_username" required>
                    </div>
                    <div>
                        <label>Database Password:</label>
                        <input type="password" name="db_password">
                    </div>
                    <div>
                        <label>Database Name:</label>
                        <input type="text" name="db_name" value="foodfusion_db" required>
                    </div>
                    <input type="submit" name="database_setup" value="Continue">
                </form>
            </div>
            
        <?php elseif ($step === 3): ?>
            <div class="step">
                <h2>Step 3: Installation</h2>
                <p>Ready to install FoodFusion. This will:</p>
                <ul>
                    <li>Create database tables</li>
                    <li>Import initial data</li>
                    <li>Set up the admin account</li>
                </ul>
                <form method="post">
                    <input type="submit" name="finalize_installation" value="Install Now">
                </form>
            </div>
            
        <?php elseif ($step === 4): ?>
            <div class="step">
                <h2>Installation Complete!</h2>
                <p class="success">FoodFusion has been successfully installed.</p>
                <p><a href="../index.php">Go to homepage</a></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>