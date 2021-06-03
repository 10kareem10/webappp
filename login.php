<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="stylee.css"> -->


</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">JobSeeker</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        </div>
    </nav>

    <div>
    </div>

    <div class="box" id="box">
        <div class="container">
            <h1 class="hd mt-5">JOBSEEKER</h1>
            <p class="p">Looking to hire? Find the best candidate. <br>
                Looking for job? Find the most suitable.</p>

            <h6 class="mb-3"> Login </h6>
            <div id="data_div">
                <div>
                    <img style="position:fixed;bottom:80px;left:600px;z-index:1;" src="3081781.png" width="600px">
                </div>
                <form method="POST" enctype="multipart/form-data">


                    <div class="mb-2 col-3  ">
                        <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="E-mail" style="height:35px;">
                    </div>


                    <div class="mb-1 col-3 ">
                        <input type="password" class="form-control" id="password" placeholder="Password" name="password" style="height:35px;">

                    </div>



                    <div>


                        <input type="submit" id="rlbtn" class="btn btn-success mt-3 col-3" value="Login"><br>
                        <p class="p ms-5 mt-3 mb-0"> Not already a member? </p>
                        <a class="ms-5 ps-4" href="register.php"> Register Now</a>
                </form>






            </div>
            </form>
        </div>
    </div>
    </div>
    </div>
</body>

</html>


<?php
session_start();
$email = $_POST['email'];
$password = $_POST['password'];
$con = mysqli_connect("localhost", "root", "12345678", "project");

$query1 = "SELECT * from jobseeker where email='$email' AND password='$password'";
$query2 = "SELECT * from company where email='$email' AND password='$password'";

$result1 = mysqli_query($con, $query1);
$result2 = mysqli_query($con, $query2);
$num_of_rows1 = mysqli_num_rows($result1);
$num_of_rows2 = mysqli_num_rows($result2);



if ($num_of_rows1 > 0) {
    $row = mysqli_fetch_array($result1);
    $_SESSION["user_id"] = $row["id"];
    $_SESSION["user_type"] = $row["type"];
    header("Location: home.php");
} else if ($num_of_rows2 > 0) {
    $row = mysqli_fetch_array($result2);
    $_SESSION["user_id"] = $row["id"];
    $_SESSION["user_type"] = $row["type"];
    header("Location: home.php");
} else {
}

?>