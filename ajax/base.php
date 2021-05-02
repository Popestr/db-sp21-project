<?php
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        /* Modify the background color */
          
        .navbar-custom {
            background-color: black;
        }
        /* Modify brand and text color */
        a:hover {
            color: white;
        }
        .navbar-text1 {
            color: white;
            padding-right: 10px;
            font-size: 20px;
        }
        .navbar-text2 {
            color: #b23b3b;
            padding-right: 10px;
            font-size: 20px;
        }
    </style>
    <title>Pixels for Humanity</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="home.php">
            <img src="static/images/logo2.png" height="100" width="200" alt="">
            </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
            <a class="navbar-text1" href="home.php">Home
                <span class="sr-only">(current)</span>
            </a>
            </li>
            <li class="nav-item">
                <a class="navbar-text1" href="profile.php">Profile</a>
            </li>
            <?php
                // If the user is not logged in redirect to the login page...
                if (isset($_SESSION['userrole']) && $_SESSION['userrole'] == 'hs2fw_a') {
                    echo '<li class="nav-item"><a class="navbar-text1" href="admin.php"><i></i>Admin</a></li>';
                }elseif(isset($_SESSION['userrole']) && $_SESSION['userrole'] == 'hs2fw_b'){
                    echo '<li class="nav-item"><a class="navbar-text1" href="feedbackform.php"><i></i>Feedback</a></li>';
                }
            ?>
            <li class="nav-item">
                <a class="navbar-text2" href="logout.php">Logout</a>
            </li>
        </ul>
        </div>
    </div>
    </nav>
  </body>
</html>