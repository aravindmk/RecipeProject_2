<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recipe Finder</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">
    <!-- Styles -->
    <style>
        body {
            /*font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;*/
            background-image: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
            background-size: cover;
            background-repeat: no-repeat;
            font-family: 'Open Sans', sans-serif;
            color: #343a40;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007bff;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }

        .recipe-search {
            text-align: center;
            padding: 50px 0;
        }

        .food-dropdown {
            width: 60%;
            padding: 10px;
            font-size: 16px;
        }

        .submit-btn {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .featured-recipes {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }

        .recipe-card {
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            margin: 20px;
            width: 250px;
            transition: transform 0.3s ease-in-out;
            position: relative;
            z-index: 1;
            /* Add position relative for absolute positioning of the image */
        }

        .recipe-card:hover {
            transform: scale(1.05);
            z-index: 2;
        }

        .recipe-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            display: block;
            opacity: 0.8;
            /* Set the opacity to control transparency (adjust as needed) */
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
            /* Send the image to the back, behind the card content */
        }

        .recipe-card .recipe-info {
            padding: 20px;
        }

        .recipe-card h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .recipe-card p {
            color: black;
            margin-top: 10px;
            font-size: 14px;
        }

        footer {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        /* Hide images initially */
        .recipe-card img.loading {
            display: none;
        }
    </style>
</head>

<body>
    <header>
        <h1>Recipe Finder</h1>
    </header>

    <div class="recipe-search">
        <form action="/search" method="post" id="recipeForm">
            @csrf
            <select name="food-dropdown" class="food-dropdown">
                <option value="" disabled selected>Select a food</option>
                <option value="egg">Egg</option>
                <option value="tomato">Tomato</option>
                <option value="salad">Salad</option>
            </select>
            <button type="submit" class="submit-btn" id="submit-btn" disabled>Submit</button>
        </form>
    </div>

    <div id="featured-recipes" class="featured-recipes">
        <!-- Loop through the recipes and display them -->
        @foreach($recipes as $recipe)
        <div class="recipe-card">
            <div class="recipe-info">
                <img src="{{ $recipe->image_url }}" alt="{{ $recipe->name }}">
                <h3>{{ $recipe->name }}</h3>
                <p>{{ $recipe->description }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <footer>
        &copy; 2023 Recipe Finder
    </footer>

    <!-- JavaScript for handling the form submission and updating the recipes -->
    <script>
        document.querySelector('.food-dropdown').addEventListener('change', function() {
            // Enable the submit button when an option is selected
            document.getElementById('submit-btn').removeAttribute('disabled');
        });

        document.getElementById('recipeForm').addEventListener('submit', function(event) {
            event.preventDefault();
            // Get the selected raw food material
            var selectedFood = document.querySelector('.food-dropdown').value;

            if (selectedFood) {
                // Fetch recipes based on the selected food
                getRecipes(selectedFood);
            } else {
                alert('Please select a food item.');
            }
        });

        function getRecipes(food) {
            // Fetch recipes from the server based on the selected food
            fetch('/recipes/' + food)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => displayRecipes(data))
                .catch(error => console.error('Error fetching recipes:', error));
        }

        function displayRecipes(recipes) {
            var featuredRecipesContainer = document.getElementById('featured-recipes');
            featuredRecipesContainer.innerHTML = ''; // Clear previous content

            if (recipes.length === 0) {
                // Handle the case where no recipes are available
                featuredRecipesContainer.innerHTML = '<p>No recipes found for the selected food.</p>';
            } else {
                recipes.forEach(function(recipe) {
                    var recipeCard = document.createElement('div');
                    recipeCard.className = 'recipe-card';

                    var recipeInfo = document.createElement('div');
                    recipeInfo.className = 'recipe-info';

                    var recipeImage = document.createElement('img');
                    recipeImage.src = recipe.image_url; // Use the correct property name
                    recipeImage.alt = recipe.name;

                    var recipeTitle = document.createElement('h3');
                    recipeTitle.textContent = recipe.name;

                    var recipeDescription = document.createElement('p');
                    recipeDescription.textContent = recipe.description;

                    recipeInfo.appendChild(recipeTitle);
                    recipeInfo.appendChild(recipeDescription);

                    recipeCard.appendChild(recipeImage);
                    recipeCard.appendChild(recipeInfo);

                    featuredRecipesContainer.appendChild(recipeCard);
                });

                // Display the featured recipes section
                featuredRecipesContainer.style.display = 'flex';
            }
        }

        // Initially hide the featured recipes container
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('featured-recipes').style.display = 'none';
        });
    </script>
</body>

</html>