<?php
/**
 * Database Update Script
 * This script adds missing columns to the recipes table
 */

require_once('../includes/db.php');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #e76f51;
            border-bottom: 3px solid #e76f51;
            padding-bottom: 10px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #28a745;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #dc3545;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #17a2b8;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #e76f51;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #d65d3f;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>FoodFusion Database Update</h1>";

try {
    // Check if columns already exist
    $checkQuery = "SHOW COLUMNS FROM recipes LIKE 'views'";
    $result = $conn->query($checkQuery);
    
    if ($result->num_rows > 0) {
        echo "<div class='info'>✓ Database columns already exist. No update needed.</div>";
    } else {
        echo "<div class='info'>Starting database update...</div>";
        
        // Add ingredients column
        $sql1 = "ALTER TABLE recipes ADD COLUMN ingredients TEXT AFTER image";
        if ($conn->query($sql1)) {
            echo "<div class='success'>✓ Added 'ingredients' column</div>";
        }
        
        // Add instructions column
        $sql2 = "ALTER TABLE recipes ADD COLUMN instructions TEXT AFTER ingredients";
        if ($conn->query($sql2)) {
            echo "<div class='success'>✓ Added 'instructions' column</div>";
        }
        
        // Add prep_time column
        $sql3 = "ALTER TABLE recipes ADD COLUMN prep_time VARCHAR(50) AFTER instructions";
        if ($conn->query($sql3)) {
            echo "<div class='success'>✓ Added 'prep_time' column</div>";
        }
        
        // Add cook_time column
        $sql4 = "ALTER TABLE recipes ADD COLUMN cook_time VARCHAR(50) AFTER prep_time";
        if ($conn->query($sql4)) {
            echo "<div class='success'>✓ Added 'cook_time' column</div>";
        }
        
        // Add servings column
        $sql5 = "ALTER TABLE recipes ADD COLUMN servings VARCHAR(20) AFTER cook_time";
        if ($conn->query($sql5)) {
            echo "<div class='success'>✓ Added 'servings' column</div>";
        }
        
        // Add nutritional_info column
        $sql6 = "ALTER TABLE recipes ADD COLUMN nutritional_info TEXT AFTER servings";
        if ($conn->query($sql6)) {
            echo "<div class='success'>✓ Added 'nutritional_info' column</div>";
        }
        
        // Add views column
        $sql7 = "ALTER TABLE recipes ADD COLUMN views INT DEFAULT 0 AFTER nutritional_info";
        if ($conn->query($sql7)) {
            echo "<div class='success'>✓ Added 'views' column</div>";
        }
        
        // Update existing recipes with default values
        $sql8 = "UPDATE recipes SET views = 0 WHERE views IS NULL";
        if ($conn->query($sql8)) {
            echo "<div class='success'>✓ Updated existing recipes with default values</div>";
        }
        
        echo "<div class='success'><strong>✓ Database update completed successfully!</strong></div>";
    }
    
    echo "<div class='info'>
            <strong>Next Steps:</strong><br>
            1. Your recipes table now has all required columns<br>
            2. You can now view individual recipes<br>
            3. The view counter will track recipe views<br>
            4. You can add ingredients, instructions, and other details to recipes
          </div>";
    
} catch (Exception $e) {
    echo "<div class='error'>✗ Error: " . $e->getMessage() . "</div>";
}

echo "<a href='../recipes.php' class='btn'>Go to Recipes</a>
      <a href='../index.php' class='btn' style='background: #6c757d;'>Go to Home</a>
    </div>
</body>
</html>";

$conn->close();
?>
