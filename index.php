<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, ">
    <title>GEHU</title>
    <!-- I have used fontawesome bootstrap CDN for some icons -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <header>
        <a href="#" class="logo">GEHU</a>
        <div class="toggle" onclick="toggleMenu();"></div>
        <ul class="menu">
            <li><a href="#home" onclick="toggleMenu();">Home</a></li>
            <li><a href="#about" onclick="toggleMenu();" >About</a></li>
            <li><a href="#education" onclick="toggleMenu();">Instagram</a></li>
            <li><a href="#Myskills" onclick="toggleMenu();">Clubs</a></li>
            <li><a href="#contact" onclick="toggleMenu();">Contact</a></li>
        </ul>

    </header>

    <section class="banner" id="home">
        <div class="textbx">
           <br><span style=" font-size: 40px;  font-family: Monotype Corsiva; ">Graphic Era Hill University</span>
            <h3 style=" font-size: 30px;  font-family: Courier New,Monotype Corsiva;">Turning dreams into reality..</h3>
            <a href="#about"class="btn">About </a>
        </div>
        <div class="wrapper">
            <h2 style=" font-family:Arial Narrow, Courier New; font-style: bold;">Login</h2>
            <p style=" font-family: Arial Narrow,Courier New;">Please fill in your credentials to login.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <label>University ID</label>
                    <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Login">
                </div>
                <p>Don't have an account? <a href="register.php">Register now</a>.</p>
            </form>
        </div>

    </section>

    <div class="midbg">
    <section class="About" id="about">
        <div class="heading">
            <h1>About</h1>
        </div>
        <div class="content">
            <div class="contentbx w50">
                <h3 style="text-align:center;">Celebrating 27 Years</h3>
                <p>
                  The Graphic Era Educational Society, established in 1993, is a non-profit organization that aims to mobilize world class education and generate resources for providing and supporting quality education for all. The society recognizes the right of every individual to lead a life of dignity and self-respect in a just and equitable manner. At the initial phase Graphic Era Society established in 1997 Graphic Era Institute of Technology. Graphic Era Institute of Technology had the distinction of being first Self- financed educational institute in North India, offer engineering courses The Institute was the culmination of the dream of its visionary founder Prof. (Dr) Kamal Ghanshala to change the destiny of thousands of youth by providing an excellent and holistic professional education. He had visualized an educational hub that would cater to academic aspirations of innumerable young man and women and his vision took a concrete shape in the form of Graphic Era Institute.
               </p>
            </div>
            <div class="w50">
                <img src="https://scontent.fdel1-3.fna.fbcdn.net/v/t1.0-9/43062505_1965953220118204_1622816112477470720_o.jpg?_nc_cat=106&ccb=2&_nc_sid=8bfeb9&_nc_ohc=bOlDmsAjdlYAX-mmodO&_nc_ht=scontent.fdel1-3.fna&oh=4bfa778f34699ecae97b0525e63c8e7b&oe=5FF5D31D" class="img"  >

            </div>
        </div>
    </section>
    <section class="eduction" id="education">
        <!-- THE OLD CLUB 
        <div class="heading" style="background-color:grey; position:relative;;top:20px;">
            <h2>Various Clubs we have...</h2>
        </div>
       <div class="content">
            <div class="contentbx w50" style="width : 50%;height:600px;  text-align:center;">
                <h3>Clubs</h3>
            </div>
            <div class="w50">
                <img src="images/uni.jpg" class="img"  >

            </div>

      


        <div id="carouselExampleInterval" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner" style="height:600px; opacity:0.8; color:black;">
    <div class="carousel-item active" data-interval="10000" style="height:600px;">
      <img src="https://securityintelligence.com/wp-content/uploads/2020/03/internal_player-vs.-hacker-cyberthreats-to-gaming-companies-and-gamers.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption" >
        <h3>Gaming</h3>
        <p>Kill em all!</p>
      </div>
    </div>
    <div class="carousel-item" data-interval="2000" style="height:600px;">
      <img src="https://wallpaper-house.com/data/out/7/wallpaper2you_176945.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption" >
        <h3>Sports</h3>
        <p>Be fit and have fun!</p>
      </div>
    </div>
    <div class="carousel-item" style="height:600px;">
      <img src="https://wallpapercave.com/wp/wp1828920.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption" >
        <h3>Coders Club</h3>
        <p>Eat Sleep Code! Repeat</p>
      </div>
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleInterval" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleInterval" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
-->
    </section>
</div>
    <section class="skills" id="Myskills">
        <div class="heading">
            <h2>Various Clubs</h2>
            <p>Below are some of the clubs run by the university.</p>
        </div>
        <div class="content">
            <div class="skillsbx">
                <img src="images/icon1.png">
                <h2>Gaming</h2>
                <p>This one adds up to my interest in arts! I have multiple Online certifiction in web designing and I am comfortable with PS,Adobe Illustrater and posess technical capabilites to work with HTML,CSS & Javascript. Project management would be just another plus point!  </p>
                <h4>INTERRESTED??</h4>
                     <p> <a href="club-registration.html">REGISTER HERE</a></p>
             </div>
            <div class="skillsbx">
                <img src="images/icon2.png">
                <h2>Sports</h2>
                <p>I have fluency in programming languages like C\C++,Javascript and familarity with Python,Java & R  </p>
                  <h4>INTERRESTED??</h4>
                <p> <a href="club-registration.html">REGISTER HERE</a></p>
            </div>
            <div class="skillsbx">
                <img src="images/icon3.png">
                <h2>Coders Club</h2>
                <p>Adaptive & Comfortable with SEO,Wordpress</p>
                  <h4>INTERRESTED??</h4>
                <p> <a href="club-registration.html">REGISTER HERE</a></p>
            </div>
              <div class="skillsbx">
                <img src="images/icon2.png">
                <h2>Sports</h2>
                <p>I have fluency in programming languages like C\C++,Javascript and familarity with Python,Java & R  </p>
                  <h4>INTERRESTED??</h4>
                     <p> <a href="club-registration.html">REGISTER HERE</a></p>
            </div>
              <div class="skillsbx">
                <img src="images/icon2.png">
                <h2>Sports</h2>
                <p>I have fluency in programming languages like C\C++,Javascript and familarity with Python,Java & R  </p>
                  <h4>INTERRESTED??</h4>
                    <p> <a href="club-registration.html">REGISTER HERE</a></p>
            </div>
              <div class="skillsbx">
                <img src="images/icon2.png">
                <h2>Sports</h2>
                <p>I have fluency in programming languages like C\C++,Javascript and familarity with Python,Java & R  </p>
                  <h4>INTERRESTED??</h4>
                 <p> <a href="club-registration.html">REGISTER HERE</a></p>
            </div>
        </div>

    </section>
    <section class="contact" id="contact">
        <div class="heading">
            <h2>Contact us</h2>
            <p> Let get in touch!</p>

        </div>
        <div class="content">
            <div class="contactinfo">
                <h3>Contact Info</h3>
              <div class="contactinfobx">
                <div class="box">
                 <div class="icon">
                     <i class="fa fa-map-marker"></i>
                 </div>
                    <div class="text">
                        <h3>Address</h3>
                        <p>Clement Town<br>Dehradun</p>
                    </div>
                  </div>
                <div class="box">
                 <div class="icon">
                      <i class="fa fa-phone"></i>
                 </div>
                    <div class="text">
                        <h3>Phone</h3>
                        <p>9551254792</p>
                    </div>
                  </div>
                  <div class="box">
                 <div class="icon">
                      <i class="fa fa-envelope-o"></i>
                 </div>
                    <div class="text">
                        <h3>Email</h3>
                        <p>gehu@gmail.com</p>
                    </div>
                  </div>

                </div>
             </div>
             <div class="formbx">
                 <form>
                    <h3>Message US</h3>
                    <input type="text" name="" placeholder="Your name">
                    <input type="email" name="" placeholder="Email">
                    <textarea placeholder="Your message"></textarea>
                    <input type="submit" name="" value="SEND">
                </form>
             </div>
        </div>
    </section>

   <div class="copyright">
       <P>copyright © 2020 GEHU</P>
   </div>
   <!-- JS section for controlling the Navbar wHile Scrolling And the toggle menu for responsive behaviour -->
   <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

  <script type="text/javascript">
       window.addEventListener('scroll', function(){
       var header = document.querySelector('header');
        header.classList.toggle('sticky', window.scrollY > 0);
       });

       function toggleMenu(){
        var menuToggle=document.querySelector('.toggle');
        var menu=document.querySelector('.menu');
         menuToggle.classList.toggle('active');
         menu.classList.toggle('active');
       }
    </script>


</body>
</html>
