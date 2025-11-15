<?php
/**
 * Insert Sample Recipes Script
 * Run this file once to populate the community_posts table with sample recipes
 */

require_once(__DIR__ . '/../includes/db.php');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Insert Sample Recipes - FoodFusion</title>
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
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border: 1px solid #f5c6cb;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border: 1px solid #bee5eb;
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
            background: #d00c0c;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Insert Sample Recipes</h1>";

// Check if recipes already exist
$check = $conn->query("SELECT COUNT(*) as count FROM community_posts");
$existing = $check->fetch_assoc()['count'];

if ($existing > 0) {
    echo "<div class='info'>
            <strong>Note:</strong> There are already {$existing} recipes in the database. 
            This script will add 12 more sample recipes.
          </div>";
}

// Define sample recipes as array
$recipes = [
    [
        'title' => 'Classic Chocolate Chip Cookies',
        'content' => "Ingredients:\n- 2 1/4 cups all-purpose flour\n- 1 tsp baking soda\n- 1 tsp salt\n- 1 cup butter, softened\n- 3/4 cup granulated sugar\n- 3/4 cup packed brown sugar\n- 2 large eggs\n- 2 tsp vanilla extract\n- 2 cups chocolate chips\n\nInstructions:\n1. Preheat oven to 375°F (190°C).\n2. Mix flour, baking soda, and salt in a bowl.\n3. Beat butter and sugars until creamy.\n4. Add eggs and vanilla, beat well.\n5. Gradually blend in flour mixture.\n6. Stir in chocolate chips.\n7. Drop rounded tablespoons onto ungreased cookie sheets.\n8. Bake 9-11 minutes or until golden brown.\n9. Cool on baking sheet for 2 minutes, then transfer to wire rack.\n\nTips: For chewier cookies, slightly underbake them. Store in an airtight container for up to a week!",
        'category' => 'dessert',
        'difficulty' => 'easy',
        'image_path' => 'assets/images/cookies.jpg'
    ],
    [
        'title' => 'Homemade Pancakes',
        'content' => "Ingredients:\n- 1 1/2 cups all-purpose flour\n- 3 1/2 tsp baking powder\n- 1 tsp salt\n- 1 tbsp white sugar\n- 1 1/4 cups milk\n- 1 egg\n- 3 tbsp butter, melted\n\nInstructions:\n1. In a large bowl, sift together flour, baking powder, salt, and sugar.\n2. Make a well in the center and pour in milk, egg, and melted butter.\n3. Mix until smooth (some lumps are okay).\n4. Heat a lightly oiled griddle or frying pan over medium-high heat.\n5. Pour or scoop the batter onto the griddle (about 1/4 cup for each pancake).\n6. Brown on both sides (flip when bubbles form on surface).\n7. Serve hot with maple syrup, butter, or your favorite toppings!\n\nPro tip: Don't overmix the batter - lumps are fine and make fluffier pancakes!",
        'category' => 'breakfast',
        'difficulty' => 'easy',
        'image_path' => 'assets/images/pancakes.jpg'
    ],
    [
        'title' => 'Creamy Mac and Cheese',
        'content' => "Ingredients:\n- 1 lb elbow macaroni\n- 1/2 cup butter\n- 1/2 cup all-purpose flour\n- 4 cups milk\n- 4 cups shredded cheddar cheese\n- 2 cups shredded mozzarella\n- 1 tsp salt\n- 1/2 tsp black pepper\n- 1/2 tsp paprika\n\nInstructions:\n1. Cook macaroni according to package directions, drain and set aside.\n2. In a large pot, melt butter over medium heat.\n3. Whisk in flour and cook for 1 minute.\n4. Gradually add milk, whisking constantly until smooth.\n5. Cook until sauce thickens (about 5 minutes).\n6. Remove from heat and stir in cheeses until melted.\n7. Add salt, pepper, and paprika.\n8. Fold in cooked macaroni.\n\nThis is the ultimate comfort food - creamy, cheesy, and absolutely delicious!",
        'category' => 'dinner',
        'difficulty' => 'medium',
        'image_path' => 'assets/images/mac_and_cheese.jpg'
    ],
    [
        'title' => 'Fresh Garden Salad',
        'content' => "Ingredients:\n- 4 cups mixed greens\n- 1 cup cherry tomatoes, halved\n- 1 cucumber, sliced\n- 1/2 red onion, thinly sliced\n- 1 bell pepper, diced\n- 1/4 cup sunflower seeds\n- 1/4 cup crumbled feta cheese\n\nDressing:\n- 1/4 cup olive oil\n- 2 tbsp balsamic vinegar\n- 1 tsp Dijon mustard\n- Salt and pepper to taste\n\nInstructions:\n1. Wash and dry all vegetables thoroughly.\n2. In a large bowl, combine all vegetables.\n3. Whisk together dressing ingredients.\n4. Drizzle dressing over salad just before serving.\n5. Top with sunflower seeds and feta cheese.\n\nPerfect as a light lunch or side dish!",
        'category' => 'lunch',
        'difficulty' => 'easy',
        'image_path' => 'assets/images/recipe1.jpg'
    ],
    [
        'title' => 'Spicy Chicken Tacos',
        'content' => "Ingredients:\n- 1 lb chicken breast, diced\n- 2 tbsp olive oil\n- 1 tbsp chili powder\n- 1 tsp cumin\n- 1 tsp paprika\n- 8 small tortillas\n- 1 cup shredded lettuce\n- 1 cup diced tomatoes\n- 1/2 cup sour cream\n- 1 cup shredded cheese\n\nInstructions:\n1. Mix spices and coat chicken pieces.\n2. Heat oil in a skillet over medium-high heat.\n3. Cook chicken for 6-8 minutes until fully cooked.\n4. Warm tortillas.\n5. Assemble tacos with chicken and toppings.\n\nThese tacos are bursting with flavor!",
        'category' => 'dinner',
        'difficulty' => 'easy',
        'image_path' => 'assets/images/recipe2.jpg'
    ],
    [
        'title' => 'Homemade Pizza Margherita',
        'content' => "Ingredients:\n- 3 cups all-purpose flour\n- 1 packet active dry yeast\n- 1 cup warm water\n- 2 tbsp olive oil\n- 1 cup tomato sauce\n- 2 cups fresh mozzarella\n- Fresh basil leaves\n\nInstructions:\n1. Dissolve yeast in warm water, let sit 5 minutes.\n2. Mix flour, yeast mixture and olive oil.\n3. Knead dough for 10 minutes.\n4. Let rise for 1 hour.\n5. Roll out dough, add sauce and mozzarella.\n6. Bake at 475°F for 12-15 minutes.\n7. Top with fresh basil.\n\nNothing beats homemade pizza!",
        'category' => 'dinner',
        'difficulty' => 'medium',
        'image_path' => 'assets/images/recipe3.jpg'
    ],
    [
        'title' => 'Banana Smoothie Bowl',
        'content' => "Ingredients:\n- 2 frozen bananas\n- 1/2 cup Greek yogurt\n- 1/4 cup milk\n- 1 tbsp honey\n- Fresh berries\n- Granola\n- Chia seeds\n\nInstructions:\n1. Blend frozen bananas, yogurt, milk, and honey until smooth.\n2. Pour into a bowl.\n3. Arrange toppings in sections.\n4. Serve immediately.\n\nHealthy breakfast that tastes like dessert!",
        'category' => 'breakfast',
        'difficulty' => 'easy',
        'image_path' => 'assets/images/recipe4.jpg'
    ],
    [
        'title' => 'Classic Spaghetti Carbonara',
        'content' => "Ingredients:\n- 1 lb spaghetti\n- 6 slices bacon, diced\n- 4 large eggs\n- 1 cup grated Parmesan\n- 3 cloves garlic, minced\n- Salt and black pepper\n\nInstructions:\n1. Cook spaghetti, reserve 1 cup pasta water.\n2. Fry bacon until crispy, add garlic.\n3. Whisk eggs and Parmesan together.\n4. Toss hot pasta with bacon.\n5. Quickly stir in egg mixture.\n6. Add pasta water to create creamy sauce.\n\nWork quickly so eggs don't scramble!",
        'category' => 'dinner',
        'difficulty' => 'medium',
        'image_path' => 'assets/images/spaghetti.jpg'
    ],
    [
        'title' => 'Chocolate Brownies',
        'content' => "Ingredients:\n- 1 cup butter, melted\n- 2 cups sugar\n- 4 large eggs\n- 1 1/2 cups flour\n- 1 cup cocoa powder\n- 1 tsp salt\n- 1 cup chocolate chips\n\nInstructions:\n1. Preheat oven to 350°F.\n2. Mix melted butter and sugar.\n3. Beat in eggs one at a time.\n4. Fold in dry ingredients.\n5. Stir in chocolate chips.\n6. Bake for 25-30 minutes.\n\nFudgy, rich, and irresistible!",
        'category' => 'dessert',
        'difficulty' => 'easy',
        'image_path' => 'assets/images/recipe5.jpg'
    ],
    [
        'title' => 'Vegetable Stir-Fry',
        'content' => "Ingredients:\n- 2 cups broccoli florets\n- 1 bell pepper, sliced\n- 1 cup snap peas\n- 1 carrot, julienned\n- 3 cloves garlic, minced\n- 3 tbsp soy sauce\n- 1 tbsp sesame oil\n- Sesame seeds\n\nInstructions:\n1. Heat oil in wok over high heat.\n2. Add garlic, stir-fry 30 seconds.\n3. Add vegetables, cook 5 minutes.\n4. Pour sauce over vegetables.\n5. Garnish with sesame seeds.\n\nQuick, healthy, and delicious!",
        'category' => 'lunch',
        'difficulty' => 'easy',
        'image_path' => 'assets/images/recipe6.jpg'
    ],
    [
        'title' => 'Crispy Fried Chicken',
        'content' => "Ingredients:\n- 8 chicken pieces\n- 2 cups buttermilk\n- 2 cups flour\n- 2 tsp paprika\n- 2 tsp garlic powder\n- 1 tsp cayenne pepper\n- Oil for frying\n\nInstructions:\n1. Marinate chicken in buttermilk for 2 hours.\n2. Mix flour with spices.\n3. Heat oil to 350°F.\n4. Dredge chicken in flour mixture.\n5. Fry for 12-15 minutes until golden.\n\nCrispy outside, juicy inside!",
        'category' => 'dinner',
        'difficulty' => 'hard',
        'image_path' => 'assets/images/recipe7.jpg'
    ],
    [
        'title' => 'Fresh Lemonade',
        'content' => "Ingredients:\n- 1 cup fresh lemon juice\n- 1 cup sugar\n- 6 cups cold water\n- Ice cubes\n- Lemon slices\n- Fresh mint\n\nInstructions:\n1. Juice lemons and strain seeds.\n2. Make simple syrup with sugar and 1 cup water.\n3. Combine lemon juice, syrup, and remaining water.\n4. Refrigerate until cold.\n5. Serve over ice with lemon slices.\n\nPerfect for summer!",
        'category' => 'beverage',
        'difficulty' => 'easy',
        'image_path' => 'assets/images/recipe8.jpg'
    ]
];

$success_count = 0;
$error_count = 0;
$errors = [];

// Get first user ID
$user_result = $conn->query("SELECT user_id FROM users ORDER BY user_id ASC LIMIT 1");
if ($user_result && $user_result->num_rows > 0) {
    $user_id = $user_result->fetch_assoc()['user_id'];
    
    // Insert each recipe
    foreach ($recipes as $recipe) {
        $stmt = $conn->prepare("INSERT INTO community_posts (user_id, title, content, category, difficulty, image_path, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        
        if ($stmt) {
            $stmt->bind_param("isssss", 
                $user_id, 
                $recipe['title'], 
                $recipe['content'], 
                $recipe['category'], 
                $recipe['difficulty'], 
                $recipe['image_path']
            );
            
            if ($stmt->execute()) {
                $success_count++;
                echo "<div class='success'>✓ Added: " . htmlspecialchars($recipe['title']) . "</div>";
            } else {
                $error_count++;
                $errors[] = "Failed to insert " . $recipe['title'] . ": " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error_count++;
            $errors[] = "Failed to prepare statement: " . $conn->error;
        }
    }
} else {
    echo "<div class='error'>
            <strong>Error:</strong> No users found in database. Please register a user first.
          </div>";
}

// Display results
if ($success_count > 0) {
    echo "<div class='success'>
            <strong>Success!</strong> {$success_count} recipes added successfully.
          </div>";
}

if ($error_count > 0) {
    echo "<div class='error'>
            <strong>Errors:</strong> {$error_count} recipes failed.<br>";
    foreach ($errors as $error) {
        echo "- " . htmlspecialchars($error) . "<br>";
    }
    echo "</div>";
}

// Show final count
$final_check = $conn->query("SELECT COUNT(*) as count FROM community_posts");
$final_count = $final_check->fetch_assoc()['count'];

echo "<div class='info'>
        <strong>Total recipes in database:</strong> {$final_count}
      </div>";

echo "<a href='../community.php' class='btn'>View Community Cookbook</a>
      <a href='../index.php' class='btn' style='background: #666;'>Go to Homepage</a>
    </div>
</body>
</html>";

$conn->close();
?>
