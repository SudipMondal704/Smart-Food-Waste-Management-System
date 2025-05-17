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
        <div class="logo">
            <img src="/api/placeholder/80/80" alt="Food Details Logo">
        </div>
        <div class="title">Food Details Form</div>
        <div class="subtitle">Thank you for taking time to provide food details. We appreciate your contribution.</div>
        <div class="content">
            <form action="submit_fooddetails.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="food_details">
                <div class="food-details">
                    <div class="input-box" style="width: 100%;">
                        <span class="details">Food Name</span>
                        <input type="text" name="food_name" placeholder="Enter the food name" required>
                    </div>
                </div>
                
                <div class="category-label">Select Food Type :</div>
                <div class="category">
                    <div class="cat">
                        <label>
                            <input type="radio" name="food_type" value="vegetarian" checked>
                            <span style="margin-left: 8px;">Veg</span>
                        </label>
                        <label>
                            <input type="radio" name="food_type" value="non-vegetarian">
                            <span style="margin-left: 8px;">Non-Veg</span>
                        </label>
                    </div>
                </div>

                <div class="category-label">Select the Food Category:</div>
                <div class="card-wrap">
                    <div class="card">
                        <input type="radio" id="raw-food" name="food_category" value="raw-food">
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
                        <input type="radio" id="cooked-food" name="food_category" value="cooked-food" checked>
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
                        <input type="radio" id="packed-food" name="food_category" value="packed-food">
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
                        <input type="text" name="quantity" placeholder="Enter the quantity" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Unit</span>
                        <input type="text" name="unit" placeholder="Enter the unit (e.g. kg, liters)" required>
                    </div>
                </div>

                <div class="food-details">
                    <div class="input-box file-input-container" style="width: 100%;">
                        <span class="details">Upload Image</span>
                        <div class="custom-file-upload">
                            <input type="file" name="food_image" id="food-image" accept="image/*" class="file-input">
                            <label for="food-image" class="file-label">
                                <span class="button-text">Choose File</span>
                                <span class="file-name">No file chosen</span>
                            </label>
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
        // Script to make the entire card clickable for radio buttons
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
            });
        });

        // Script to show the file name when a file is selected
        document.getElementById('food-image').addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : 'No file chosen';
            this.nextElementSibling.querySelector('.file-name').textContent = fileName;
        });
    </script>
</body>
</html>