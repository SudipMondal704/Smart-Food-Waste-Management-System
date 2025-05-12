
<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Login Form</title>
    <link rel="stylesheet" href="delivery.css">
  </head>
  <body>
    <div class="center">
      <h1>Delivery Login</h1>
      <form method="post">
        <div class="txt_field">
          <input type="email" name="email" required/>
          <span></span>
          <label>Email</label>
        </div>
        <div class="txt_field">
          <input type="password" name="password" required/>
          <span></span>
          <label>Password</label>
          
        </div>
        
        
                    <br>
        <!-- <div class="pass">Forgot Password?</div> -->
        <input type="submit" value="Login" name="sign">
        <div class="signup_link">
          Not a member? <a href="deliverysignup.php">Signup</a>
        </div>
      </form>
    </div>

  </body>
</html>
