// Recipe Preview Popup Handler
document.addEventListener('DOMContentLoaded', function() {
    // Sample recipe data (in production, this would come from the server)
    const recipeData = {
        'recipe1': {
            title: 'Spicy Chicken Ramen',
            description: 'Hot, rich, and full of flavor. A FoodFusion favorite! This authentic Japanese-inspired ramen features tender chicken, perfectly cooked noodles, and a spicy broth that will warm your soul.',
            image: 'assets/images/recipe1.jpg',
            difficulty: 'Medium',
            time: '30 min',
            rating: 4.8,
            ratingCount: 120,
            prepTime: '15 min',
            cookTime: '15 min',
            servings: '4',
            ingredients: [
                '500g chicken breast, sliced',
                '4 packs ramen noodles',
                '6 cups chicken broth',
                '2 tbsp soy sauce',
                '1 tbsp sesame oil',
                '2 cloves garlic, minced',
                '1 inch ginger, grated',
                '2 eggs, soft boiled',
                'Green onions for garnish',
                'Chili oil to taste'
            ],
            instructions: 'Heat sesame oil in a large pot. Add garlic and ginger, sauté until fragrant. Add chicken and cook until browned. Pour in chicken broth and soy sauce, bring to a boil. Add ramen noodles and cook for 3-4 minutes. Serve hot with soft boiled eggs, green onions, and chili oil.'
        },
        'recipe2': {
            title: 'Vegan Creamy Pasta',
            description: 'A healthy twist to your favorite creamy classic. This plant-based pasta dish is rich, satisfying, and guilt-free!',
            image: 'assets/images/recipe2.jpg',
            difficulty: 'Easy',
            time: '25 min',
            rating: 4.5,
            ratingCount: 85,
            prepTime: '10 min',
            cookTime: '15 min',
            servings: '3',
            ingredients: [
                '300g pasta of choice',
                '1 cup cashews, soaked',
                '2 cloves garlic',
                '1/2 cup nutritional yeast',
                '1 cup vegetable broth',
                'Fresh basil',
                'Salt and pepper to taste',
                'Cherry tomatoes for garnish'
            ],
            instructions: 'Cook pasta according to package directions. Blend soaked cashews, garlic, nutritional yeast, and vegetable broth until smooth. Heat the sauce in a pan, add cooked pasta and toss. Garnish with fresh basil and cherry tomatoes.'
        },
        'pancakes': {
            title: 'Fluffy Pancakes',
            description: 'Perfect for breakfast or brunch. Light, fluffy, and absolutely delicious!',
            image: 'assets/images/pancakes.jpg',
            difficulty: 'Easy',
            time: '15 min',
            rating: 4.9,
            ratingCount: 95,
            prepTime: '5 min',
            cookTime: '10 min',
            servings: '4',
            ingredients: [
                '2 cups all-purpose flour',
                '2 tbsp sugar',
                '2 tsp baking powder',
                '1/2 tsp salt',
                '2 eggs',
                '1 3/4 cups milk',
                '1/4 cup melted butter',
                'Maple syrup for serving'
            ],
            instructions: 'Mix dry ingredients in a bowl. In another bowl, whisk eggs, milk, and melted butter. Combine wet and dry ingredients. Heat a griddle and pour batter. Cook until bubbles form, flip and cook until golden. Serve with maple syrup.'
        },
        'cookies': {
            title: 'Chocolate Chip Cookies',
            description: 'Classic cookies with gooey chocolate chips. The perfect treat for any occasion!',
            image: 'assets/images/cookies.jpg',
            difficulty: 'Easy',
            time: '20 min',
            rating: 4.7,
            ratingCount: 150,
            prepTime: '10 min',
            cookTime: '10 min',
            servings: '24 cookies',
            ingredients: [
                '2 1/4 cups flour',
                '1 tsp baking soda',
                '1 cup butter, softened',
                '3/4 cup sugar',
                '3/4 cup brown sugar',
                '2 eggs',
                '2 tsp vanilla extract',
                '2 cups chocolate chips'
            ],
            instructions: 'Preheat oven to 375°F. Mix flour and baking soda. Cream butter and sugars, add eggs and vanilla. Gradually blend in flour mixture. Stir in chocolate chips. Drop spoonfuls onto baking sheet. Bake 9-11 minutes until golden.'
        },
        'mac_and_cheese': {
            title: 'Mac and Cheese',
            description: 'Rich and creamy comfort food. The ultimate mac and cheese experience!',
            image: 'assets/images/mac_and_cheese.jpg',
            difficulty: 'Medium',
            time: '35 min',
            rating: 4.6,
            ratingCount: 110,
            prepTime: '10 min',
            cookTime: '25 min',
            servings: '6',
            ingredients: [
                '1 lb elbow macaroni',
                '4 cups shredded cheddar cheese',
                '3 cups milk',
                '1/4 cup butter',
                '1/4 cup flour',
                '1/2 tsp salt',
                '1/4 tsp black pepper',
                'Breadcrumbs for topping'
            ],
            instructions: 'Cook macaroni according to package. Make a roux with butter and flour, gradually add milk. Stir in cheese until melted. Combine with macaroni. Transfer to baking dish, top with breadcrumbs. Bake at 350°F for 20 minutes.'
        },
        'spaghetti': {
            title: 'Spaghetti Bolognese',
            description: 'Hearty Italian classic. Rich meat sauce with perfectly cooked pasta!',
            image: 'assets/images/spaghetti.jpg',
            difficulty: 'Medium',
            time: '45 min',
            rating: 4.9,
            ratingCount: 200,
            prepTime: '15 min',
            cookTime: '30 min',
            servings: '4',
            ingredients: [
                '400g spaghetti',
                '500g ground beef',
                '1 onion, diced',
                '2 cloves garlic, minced',
                '800g canned tomatoes',
                '2 tbsp tomato paste',
                'Italian herbs',
                'Parmesan cheese',
                'Olive oil'
            ],
            instructions: 'Cook spaghetti. Sauté onion and garlic in olive oil. Add ground beef and brown. Stir in tomatoes, tomato paste, and herbs. Simmer 20 minutes. Serve sauce over spaghetti with parmesan.'
        },
        'nsima': {
            title: 'Traditional Nsima',
            description: 'Authentic Malawian dish. A staple food made from maize flour!',
            image: 'assets/images/nsima.jpg',
            difficulty: 'Medium',
            time: '40 min',
            rating: 4.4,
            ratingCount: 75,
            prepTime: '5 min',
            cookTime: '35 min',
            servings: '6',
            ingredients: [
                '4 cups maize flour',
                '6 cups water',
                'Pinch of salt'
            ],
            instructions: 'Bring water to boil. Add a pinch of salt. Gradually add maize flour while stirring continuously. Reduce heat and continue stirring until thick and smooth. Cook for 20-30 minutes, stirring frequently. Serve hot with your favorite relish.'
        },
        'rice': {
            title: 'Perfect Rice',
            description: 'Simple and versatile staple. Fluffy, perfectly cooked rice every time!',
            image: 'assets/images/rice.jpg',
            difficulty: 'Easy',
            time: '20 min',
            rating: 4.8,
            ratingCount: 180,
            prepTime: '5 min',
            cookTime: '15 min',
            servings: '4',
            ingredients: [
                '2 cups white rice',
                '4 cups water',
                '1 tsp salt',
                '1 tbsp butter (optional)'
            ],
            instructions: 'Rinse rice until water runs clear. Bring water to boil with salt. Add rice, reduce heat to low, cover. Cook for 15 minutes without lifting lid. Remove from heat, let stand 5 minutes. Fluff with fork and serve.'
        }
    };

    // Get all recipe cards
    const recipeCards = document.querySelectorAll('.recipe-card');
    
    recipeCards.forEach((card, index) => {
        const viewBtn = card.querySelector('.btn-view');
        if (viewBtn) {
            viewBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Get recipe key based on index
                const recipeKeys = ['recipe1', 'recipe2', 'pancakes', 'cookies', 'mac_and_cheese', 'spaghetti', 'nsima', 'rice'];
                const recipeKey = recipeKeys[index];
                const recipe = recipeData[recipeKey];
                
                if (recipe) {
                    showRecipePreview(recipe);
                }
            });
        }
    });

    function showRecipePreview(recipe) {
        // Create modal if it doesn't exist
        let modal = document.getElementById('recipePreviewModal');
        if (!modal) {
            modal = createRecipeModal();
            document.body.appendChild(modal);
        }

        // Populate modal with recipe data
        modal.querySelector('.recipe-preview-image img').src = recipe.image;
        modal.querySelector('.recipe-preview-badge').textContent = recipe.difficulty;
        modal.querySelector('.recipe-preview-title').textContent = recipe.title;
        modal.querySelector('.recipe-preview-description').textContent = recipe.description;
        
        // Meta info
        modal.querySelector('.preview-prep-time').textContent = recipe.prepTime;
        modal.querySelector('.preview-cook-time').textContent = recipe.cookTime;
        modal.querySelector('.preview-servings').textContent = recipe.servings;
        
        // Rating
        const starsContainer = modal.querySelector('.preview-rating-stars');
        starsContainer.innerHTML = generateStars(recipe.rating);
        modal.querySelector('.preview-rating-text').textContent = `${recipe.rating} (${recipe.ratingCount} reviews)`;
        
        // Ingredients
        const ingredientsList = modal.querySelector('.preview-ingredients-list');
        ingredientsList.innerHTML = recipe.ingredients.map(ing => `<li>${ing}</li>`).join('');
        
        // Instructions
        modal.querySelector('.preview-instructions-text').textContent = recipe.instructions;
        
        // Show modal
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Check if content needs scroll indicator
        setTimeout(() => {
            const content = modal.querySelector('.recipe-preview-content');
            const hasScroll = content.scrollHeight > content.clientHeight;
            if (hasScroll) {
                content.classList.add('has-scroll');
            }
        }, 100);
    }

    function createRecipeModal() {
        const modal = document.createElement('div');
        modal.id = 'recipePreviewModal';
        modal.className = 'recipe-preview-modal';
        modal.innerHTML = `
            <div class="recipe-preview-backdrop"></div>
            <div class="recipe-preview-container">
                <button class="recipe-preview-close">
                    <i class="fas fa-times"></i>
                </button>
                
                <div class="recipe-preview-image">
                    <img src="" alt="Recipe">
                    <div class="recipe-preview-badge">Medium</div>
                </div>
                
                <div class="recipe-preview-content">
                    <div class="recipe-preview-header">
                        <h2 class="recipe-preview-title">Recipe Title</h2>
                        <p class="recipe-preview-description">Recipe description</p>
                    </div>
                    
                    <div class="recipe-preview-rating">
                        <div class="preview-rating-stars"></div>
                        <span class="preview-rating-text">4.8 (120 reviews)</span>
                    </div>
                    
                    <div class="recipe-preview-meta">
                        <div class="preview-meta-item">
                            <i class="fas fa-clock"></i>
                            <span><strong>Prep:</strong> <span class="preview-prep-time">15 min</span></span>
                        </div>
                        <div class="preview-meta-item">
                            <i class="fas fa-fire"></i>
                            <span><strong>Cook:</strong> <span class="preview-cook-time">15 min</span></span>
                        </div>
                        <div class="preview-meta-item">
                            <i class="fas fa-users"></i>
                            <span><strong>Serves:</strong> <span class="preview-servings">4</span></span>
                        </div>
                    </div>
                    
                    <div class="recipe-preview-section">
                        <h3><i class="fas fa-list"></i> Ingredients</h3>
                        <ul class="recipe-preview-list preview-ingredients-list"></ul>
                    </div>
                    
                    <div class="recipe-preview-section">
                        <h3><i class="fas fa-book-open"></i> Instructions</h3>
                        <p class="recipe-preview-text preview-instructions-text"></p>
                    </div>
                    
                    <div class="recipe-preview-actions">
                        <button class="btn-preview-full">
                            <i class="fas fa-external-link-alt"></i>
                            <span>View Full Recipe</span>
                        </button>
                        <button class="btn-preview-close">Close</button>
                    </div>
                </div>
            </div>
        `;

        // Add event listeners
        const closeBtn = modal.querySelector('.recipe-preview-close');
        const backdrop = modal.querySelector('.recipe-preview-backdrop');
        const closeActionBtn = modal.querySelector('.btn-preview-close');
        
        [closeBtn, backdrop, closeActionBtn].forEach(el => {
            el.addEventListener('click', () => {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            });
        });

        // View full recipe button (placeholder - would navigate to recipe page)
        modal.querySelector('.btn-preview-full').addEventListener('click', () => {
            alert('This would navigate to the full recipe page');
        });

        // Add scroll detection for scroll indicator
        const content = modal.querySelector('.recipe-preview-content');
        
        function checkScroll() {
            const hasScroll = content.scrollHeight > content.clientHeight;
            const isScrolledToBottom = content.scrollHeight - content.scrollTop <= content.clientHeight + 10;
            
            if (hasScroll && !isScrolledToBottom) {
                content.classList.add('has-scroll');
            } else {
                content.classList.remove('has-scroll');
            }
        }
        
        // Check scroll on content load and scroll events
        content.addEventListener('scroll', checkScroll);
        
        // Use MutationObserver to detect when content is loaded
        const observer = new MutationObserver(checkScroll);
        observer.observe(content, { childList: true, subtree: true });
        
        // Initial check after a short delay
        setTimeout(checkScroll, 100);

        return modal;
    }

    function generateStars(rating) {
        const fullStars = Math.floor(rating);
        const hasHalfStar = (rating - fullStars) >= 0.5;
        let starsHTML = '';
        
        for (let i = 1; i <= 5; i++) {
            if (i <= fullStars) {
                starsHTML += '<i class="fas fa-star"></i>';
            } else if (i === fullStars + 1 && hasHalfStar) {
                starsHTML += '<i class="fas fa-star-half-alt"></i>';
            } else {
                starsHTML += '<i class="far fa-star"></i>';
            }
        }
        
        return starsHTML;
    }
});
