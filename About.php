<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Home.css">
</head>
<body>
    <header>
        <div class="logo">Food <b style="color: #06abc1;">Donate</b></div>
        <div class="hamburger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
        <nav class="nav-bar">
            <ul>
                <li><a href="Home.php" >Home</a></li>
                <li><a href="#about" class="active" >About</a></li>
                <li><a href="Contact.php" >Contact</a></li>
                <li><a href="profile.php" >Profile</a></li>
            </ul>
        </nav>
    </header>
    <script>
        hamburger=document.querySelector(".hamburger");
        hamburger.onclick =function(){
            navBar=document.querySelector(".nav-bar");
            navBar.classList.toggle("active");
        }
    </script>
    <style>

 body {
    height: 100%;
    margin: 0;
    padding: 0;
    background: linear-gradient(180deg, #6bdb49d2 0%, #1731c2cb 100%); /* Change this to any color you prefer */
}

       
        
        .coverc{
          width: 100%;
          height: 400px;
          background:url('img/about3.jpg')no-repeat;
    background-size: cover;
    display: grid;
    place-items:center;
    padding-top: 8rem;
 
        } 
        .title{
          font-size: 38px;
          text-align: center;
          align-items: center; 
        }
       
        .para p{
            font-size: 23px;
            margin-left: 20px;
            margin-right: 20px;
        }
          @media (max-width: 767px) {
            .para p{
               font-size: 16px;
               /* margin-left: 10px; */
              }
            #pptslide{
                height: 200px;
                width: 300px;

            }
            #map{
              height: 200px;
                width: 300px;


            }
            #overview{
              height: 200px;
                width: 300px;
            }
            
        .title{
          font-size: 28px;
          margin: 10px;
          text-align: center;
          align-items: center; 
        }
       

          }
     
    </style>
    <br>
    <br>
    <!-- <section class="coverc">
        
    
    </section> -->
    <p class="title">"Welcome to <u> Food Donate</u> "</p>
    <br>
    <br>
    <br>
        <p class="heading">About us</p>
        <!-- <p  style=" font-size:30px ; text-align: center;" > ABOUT <span>US</span> </p> -->
      
        <!-- <br> -->
      <div class="para">
        <!-- <p>"Welcome to Food Donate, India's largest and most trusted donating platform that connects 
          donors to verified nonprofits. FoodDonate helps you become a ray of hope for people in need.
           Choose a cause that is close to your heart and join hands with millions of donors
            like you who aim to make this world a better place."</p> -->
      
        <p>We are a team of passionate individuals committed to addressing the issue of food waste in India. 
          Our goal is to create a system that connects food donors with charities and NGOs, while also reducing
           the environmental impact of food waste.</p>
      </div>
      <br>
      <br>
      
  

<p  style=" font-size:30px;" class="heading"> Location  </p>


<div style= "padding-top: 2%;"position: relative;">
  <div style="position: relative; padding-bottom: 75%; height: 0; overflow: hidden;">
  <iframe style="position: absolute; top: 0; left: 15%; width: 70%; height: 50%; border: 2px solid black;" loading="lazy" allowfullscreen src="https://maps.google.com/maps?q=Burdwan+Institute+Management+%26+Computer+Science&output=embed">
  </iframe>
</div><a href="https://embeddablemap.com/" rel="noopener" target="_blank" style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0;">embeddablemap.com</a></div>


    </div>


     <!-- <p class="heading"> Our Story</p>
     <div class="para">
       <p>Our journey began with a realization that food waste is a significant problem in India. According to a report by the United Nations,
         India is the world's second-largest food producer, yet it also has one of the highest rates of food waste. This waste has a significant 
         impact on the environment, as well as on food security in the country.</p>
     </div> -->
     <!-- <div class="overview"  style="  text-align: center; padding-bottom: 50px;" >
      <iframe frameborder="no" border="0" marginwidth="0" marginheight="0" width=1400 height=800 src="https://edrawcloudpublicus.s3.amazonaws.com/viewer/self/3094230/share/2023-3-2/1677763924/main.svg" id="overview"></iframe>
     </div> -->
</body>
</html>