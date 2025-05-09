





<!DOCTYPE html>
<html lang="en">


  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Animated Login Form | CodingNepal</title>
    <link rel="stylesheet" href="delivery.css">
  </head>
  <body>
    <div class="center">
      <h1>Register</h1>
      <form method="post" action=" ">
        <div class="txt_field">
          <input type="text" name="username" required/>
          <span></span>
          <label>Username</label>
        </div>
        <div class="txt_field">
          <input type="password" name="password" required/>
          <span></span>
          <label>Password</label>
        </div>
        <div class="txt_field">
            <input type="email" name="email" required/>
            <span></span>
            <label>Email</label>
          </div>
          <div class="">
                           
                           <select id="district" name="district" style="padding:10px; padding-left: 20px;">
                          <option value="Burdwan">Burdwan</option>
                          
                          <option value="Kolkata">Kolkata</option>
                          
                          <option value="Asansol" selected>Asansol</option>
                          
                        </select> 
                        
          </div>
          <br>
        <!-- <div class="pass">Forgot Password?</div> -->
        <input type="submit" name="sign" value="Register">
        <div class="signup_link">
          Already a member? <a href="deliverylogin.php">Sigin</a>
        </div>
      </form>
    </div>

  </body>
</html>
