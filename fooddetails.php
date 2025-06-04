<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Details Form</title>
    <link rel="stylesheet" href="fooddetails.css">
</head>
<body>
  <div class="container">
        <button type="button" class="add-food-btn" onclick="addFoodItem()">+ Add Another Food Item</button>
        
        <div class="logo">
            <img src="" alt="Food Details Logo">
        </div>
        <div class="title">Food Details Form</div>
        <div class="subtitle">Thank you for taking time to provide food details. We appreciate your contribution.</div>
        <div class="content">
            <form action="submit_fooddetails.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="food_details">
                
                <div id="food-items-container">
                    <!-- Initial food item -->
                    <div class="food-item" data-item-id="1">
                        <div class="food-item-header">
                            <h3 class="food-item-title">Food Item 1</h3>
                        </div>
                        
                        <div class="food-details">
                            <div class="input-box" style="width: 100%;">
                                <span class="details">Food Name</span>
                                <input type="text" name="food_name[]" placeholder="Enter the food name" required>
                            </div>
                        </div>
                        
                        <div class="category-label">Select Food Type :</div>
                        <div class="category">
                            <div class="cat">
                                <label>
                                    <input type="radio" name="food_type_1" value="vegetarian" checked>
                                    <span style="margin-left: 8px;">Veg</span>
                                </label>
                                <label>
                                    <input type="radio" name="food_type_1" value="non-vegetarian">
                                    <span style="margin-left: 8px;">Non-Veg</span>
                                </label>
                            </div>
                        </div>

                        <div class="category-label">Select the Food Category:</div>
                        <div class="card-wrap">
                            <div class="card">
                                <input type="radio" name="food_category_1" value="raw-food">
                                <div class="img-content">
                                    <div class="card-image">
                                        <img src="img/Raw-food.jpg" alt="Raw Food" class="card-img">
                                    </div>
                                    <div class="card-details">
                                        <h2 class="name">Raw Food</h2>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <input type="radio" name="food_category_1" value="cooked-food" checked>
                                <div class="img-content">
                                    <div class="card-image">
                                        <img src="img/Cooke-food.jpg" alt="Cooked Food" class="card-img">
                                    </div>
                                    <div class="card-details">
                                        <h2 class="name">Cooked Food</h2>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <input type="radio" name="food_category_1" value="packed-food">
                                <div class="img-content">
                                    <div class="card-image">
                                        <img src="img/Packaged-food.jpg" alt="Packaged Food" class="card-img">
                                    </div>
                                    <div class="card-details">
                                        <h2 class="name">Packaged Food</h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="food-details">
                            <div class="input-box">
                                <span class="details">Quantity</span>
                                <input type="text" name="quantity[]" placeholder="Enter the quantity" required>
                            </div>
                            <div class="input-box">
                                <span class="details">Unit</span>
                                <input type="text" name="unit[]" placeholder="Enter the unit (e.g. kg, liters)" required>
                            </div>
                        </div>

                        <div class="food-details">
                            <div class="input-box file-input-container" style="width: 100%;">
                                <span class="details">Upload Image</span>
                                <div class="custom-file-upload">
                                    <input type="file" name="food_image[]" accept="image/*" class="file-input">
                                    <label class="file-label">
                                        <span class="button-text">Choose File</span>
                                        <span class="file-name">No file chosen</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="title-1">Contact Details</div>

                <div class="food-details">
                    <div class="input-box" style="width: 100%;">
                        <span class="details">Donar Name</span>
                        <input type="text" name="donor_name" placeholder="Enter the name of Food Donar" required>
                    </div>
                </div>
                
                <div class="message-box">
                    <span class="details">Address</span>
                    <textarea name="address" placeholder="Add the address from where food will be picked" required></textarea>
                </div>

                <div class="food-details">
                    <div class="input-box">
                        <span class="details">Phone no.</span>
                        <input type="text" name="phone" placeholder="Enter the phone number" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Alternative Phone no.</span>
                        <input type="text" name="altphone" placeholder="Enter the alternative number" required>
                    </div>
                </div>

                <div class="button">
                    <input type="submit" value="Submit Food Details">
                </div>
            </form>
        </div>
    </div>

    <script>
        let foodItemCounter = 1;

function addFoodItem() {
    // Get current number of food items and increment from there
    const currentItems = document.querySelectorAll('.food-item').length;
    const newItemNumber = currentItems + 1;
    
    const container = document.getElementById('food-items-container');
    const newItem = document.createElement('div');
    newItem.className = 'food-item';
    newItem.setAttribute('data-item-id', newItemNumber);
    
    newItem.innerHTML = `
        <div class="food-item-header">
            <h3 class="food-item-title">Food Item ${newItemNumber}</h3>
            <button type="button" class="remove-food-btn" onclick="removeFoodItem(this)">Remove</button>
        </div>
        
        <div class="food-details">
            <div class="input-box" style="width: 100%;">
                <span class="details">Food Name</span>
                <input type="text" name="food_name[]" placeholder="Enter the food name" required>
            </div>
        </div>
        
        <div class="category-label">Select Food Type :</div>
        <div class="category">
            <div class="cat">
                <label>
                    <input type="radio" name="food_type_${newItemNumber}" value="vegetarian" checked>
                    <span style="margin-left: 8px;">Veg</span>
                </label>
                <label>
                    <input type="radio" name="food_type_${newItemNumber}" value="non-vegetarian">
                    <span style="margin-left: 8px;">Non-Veg</span>
                </label>
            </div>
        </div>

        <div class="category-label">Select the Food Category:</div>
        <div class="card-wrap">
            <div class="card">
                <input type="radio" name="food_category_${newItemNumber}" value="raw-food">
                <div class="img-content">
                    <div class="card-image">
                        <img src="img/Raw-food.jpg" alt="Raw Food" class="card-img">
                    </div>
                    <div class="card-details">
                        <h2 class="name">Raw Food</h2>
                    </div>
                </div>
            </div>

            <div class="card">
                <input type="radio" name="food_category_${newItemNumber}" value="cooked-food" checked>
                <div class="img-content">
                    <div class="card-image">
                        <img src="img/Cooke-food.jpg" alt="Cooked Food" class="card-img">
                    </div>
                    <div class="card-details">
                        <h2 class="name">Cooked Food</h2>
                    </div>
                </div>
            </div>

            <div class="card">
                <input type="radio" name="food_category_${newItemNumber}" value="packed-food">
                <div class="img-content">
                    <div class="card-image">
                        <img src="img/Packaged-food.jpg" alt="Packaged Food" class="card-img">
                    </div>
                    <div class="card-details">
                        <h2 class="name">Packaged Food</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="food-details">
            <div class="input-box">
                <span class="details">Quantity</span>
                <input type="text" name="quantity[]" placeholder="Enter the quantity" required>
            </div>
            <div class="input-box">
                <span class="details">Unit</span>
                <input type="text" name="unit[]" placeholder="Enter the unit (e.g. kg, liters)" required>
            </div>
        </div>

        <div class="food-details">
            <div class="input-box file-input-container" style="width: 100%;">
                <span class="details">Upload Image</span>
                <div class="custom-file-upload">
                    <input type="file" name="food_image[]" accept="image/*" class="file-input">
                    <label class="file-label">
                        <span class="button-text">Choose File</span>
                        <span class="file-name">No file chosen</span>
                    </label>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(newItem);
    
    // Reinitialize event listeners for the new item
    initializeEventListeners(newItem);
}

function removeFoodItem(button) {
    const foodItem = button.closest('.food-item');
    foodItem.remove();
    
    // Renumber remaining items to maintain sequential numbering
    renumberFoodItems();
}

function renumberFoodItems() {
    const remainingItems = document.querySelectorAll('.food-item');
    remainingItems.forEach((item, index) => {
        const itemNumber = index + 1;
        
        // Update title
        const title = item.querySelector('.food-item-title');
        title.textContent = `Food Item ${itemNumber}`;
        
        // Update data attribute
        item.setAttribute('data-item-id', itemNumber);
        
        // Update radio button names to ensure they're unique
        const foodTypeRadios = item.querySelectorAll('input[name^="food_type_"]');
        foodTypeRadios.forEach(radio => {
            radio.name = `food_type_${itemNumber}`;
        });
        
        const foodCategoryRadios = item.querySelectorAll('input[name^="food_category_"]');
        foodCategoryRadios.forEach(radio => {
            radio.name = `food_category_${itemNumber}`;
        });
    });
}

function initializeEventListeners(container = document) {
    // Script to make the entire card clickable for radio buttons
    container.querySelectorAll('.card').forEach(card => {
        card.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
        });
    });

    // Script to show the file name when a file is selected
    container.querySelectorAll('.file-input').forEach(input => {
        input.addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : 'No file chosen';
            const fileNameSpan = this.nextElementSibling.querySelector('.file-name');
            fileNameSpan.textContent = fileName;
        });
    });
}

// Initialize event listeners on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
});
    </script>
</body>
</html>  
