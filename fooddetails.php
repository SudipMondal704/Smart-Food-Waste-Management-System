<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate</title>
    <link rel="stylesheet" href="fooddetails.css">
</head>
<body>
    <div class="container">
        <div class="name" >
            <form action="" method="post">
            <p class="logo">Food <b style="color: #06C167; ">Donate</b></p>
        
        <div class="input">
            <label for="foodname"><b>Food Name :</b></label>
                <input type="text" id="foodname" name="foodname" required/>
        </div><br>
      
      
        <div class="radio">
            <label for="meal" ><b>Food Type :</b></label>  
                <input type="radio" name="meal" id="veg" value="veg" required >Veg
                <input type="radio" name="meal" id="Non-veg" value="Non-veg" > Non-veg
        </div>
        <br>
    
        <div class="category">
            <label for="food"><b>Category :</b></label>
            <br>
            <div class="foodimg">
                <input type="radio" id="raw-food" name="image-choice" value="raw-food">
                    <label for="raw-food">
                        <img src="img/raw.jpg" alt="raw-food" width="400">
                    </label>
                <input type="radio" id="cooked-food" name="image-choice" value="cooked-food"checked>
                    <label for="cooked-food">
                        <img src="img/cooked.jpg" alt="cooked-food" width="400">
                    </label>
                <input type="radio" id="packed-food" name="image-choice" value="packed-food">
                    <label for="packed-food">
                        <img src="img/packaged.jpg" alt="packed-food" width="400">
                    </label>
             </div>
        </div><br>
        
        <div class="input">
            <label for="quantity"><b>Quantity :</b></label>
                <input type="text" id="quantity" name="quantity" required/>
        </div>

        
        <b>Contact Details :</b>
        <br><br>
        <div class="input">
            <div>
                <label for="name">Name :</label>
                    <input type="text" id="name" name="name" required/>
            </div>
            <div>
                <label for="phoneno" >Phone-No :</label>
                    <input type="text" id="phoneno" name="phoneno" maxlength="10" pattern="[0-9]{10}" required />
            </div>
        </div>

        <!-- <div class="input">
        <label for="location"></label>
        <label for="district">District :</label>
<select id="district" name="district" style="padding:10px;">
  <option value="chennai">Chennai</option>
  <option value="kancheepuram">Kancheepuram</option>
  <option value="thiruvallur">Thiruvallur</option>
  <option value="vellore">Vellore</option>
  <option value="tiruvannamalai">Tiruvannamalai</option>
  <option value="tiruvallur">Tiruvallur</option>
  <option value="tiruppur">Tiruppur</option>
  <option value="coimbatore">Coimbatore</option>
  <option value="erode">Erode</option>
  <option value="salem">Salem</option>
  <option value="namakkal">Namakkal</option>
  <option value="tiruchirappalli">Tiruchirappalli</option>
  <option value="thanjavur">Thanjavur</option>
  <option value="pudukkottai">Pudukkottai</option>
  <option value="karur">Karur</option>
  <option value="ariyalur">Ariyalur</option>
  <option value="perambalur">Perambalur</option>
  <option value="madurai" selected>Madurai</option>
  <option value="virudhunagar">Virudhunagar</option>
  <option value="dindigul">Dindigul</option>
  <option value="ramanathapuram">Ramanathapuram</option>
  <option value="sivaganga">Sivaganga</option>
  <option value="thoothukkudi">Thoothukkudi</option>
  <option value="tirunelveli">Tirunelveli</option>
  <option value="tiruppur">Tiruppur</option>
  <option value="tenkasi">Tenkasi</option>
  <option value="kanniyakumari">Kanniyakumari</option>
</select> -->

        <label for="address" style="padding-left: 10px;">Address:</label>
        <input type="text" id="address" name="address" required/><br>
        
      
       
       
        </div>
        <div class="btn">
            <button type="submit" name="submit"> Submit</button>
     
        </div>
     </form>
     </div>
   </div>
     
    
</body>
</html>
