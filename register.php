<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="stylee.css">


</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">JobSeeker</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="container mt-5">
        <h5 class="mb-3"> Register </h5>
        <div id="data_div">
            <form method="POST" enctype="multipart/form-data">

                <div class="mb-2 col-3 ">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Name">
                </div>
                <div class="mb-2 col-3 ">
                    <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="E-mail">
                </div>

                <div class="mb-2 col-3 ">
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                </div>
                <img style="position:fixed;bottom:80px;left:600px;z-index:1;" src="3081623.png" width="600px">



                <div class="col-3">
                    <select class="form-select " name="type" id="type">

                        <option>Job Seeker</option>
                        <option>Company</option>
                    </select><br>
                </div>
                <h6>Date:</h6>


                <div class="sl col-3 ml-2">
                    <select class="form-select" name="day" id="day">
                        <?php
                        for ($i = 1; $i <= 31; $i++)
                            echo "<option>$i</option>";
                        ?>
                    </select>

                    <select class="form-select" name="month" id="month">
                        <?php
                        for ($i = 1; $i <= 12; $i++)
                            echo "<option>$i</option>";
                        ?>
                    </select>

                    <select class="form-select" name="year" id="year">
                        <?php
                        for ($i = 1950; $i <= 1999; $i++)
                            echo "<option>$i</option>";
                        ?>
                        <option selected="selected">2000</option>
                        <?php
                        for ($i = 2001; $i <= 2020; $i++)
                            echo "<option>$i</option>";
                        ?>
                    </select>
                </div>



                <input type="file" class="form-control mt-3" id="uploaded_img" name="uploaded_img" style="width:280px;">
                <br>

                <input type="submit" class="btn btn-success mt-3 col-3" name="register_btn" id="register_btn rlbtn" value="Register">



            </form>


            <p class="p ms-5 ps-3 mt-2 mb-0"> Already a member? </p>
            <a class="ms-5 ps-5" href="login.php"> Login Now</a>
        </div>
    </div>
</body>
</body>

</html>

<?php
$buttonClicked = $_POST["register_btn"];
if (isset($buttonClicked)) {
    $name =  $_POST['name'];
    $password =  $_POST['password'];
    $email =  $_POST['email'];
    $type = $_POST['type'];

    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $date = "$day/$month/$year";

    // ------------------------------------------
    // Here we add the part for image uploading
    $img_name = $_FILES['uploaded_img']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["uploaded_img"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $extensions_arr = array("jpg", "jpeg", "png", "gif");
    if (in_array($imageFileType, $extensions_arr))
        move_uploaded_file($_FILES['uploaded_img']['tmp_name'], $target_dir . $img_name);
    // ----------------------------
    $con = mysqli_connect("localhost", "root", "12345678", "project");
    $query = null;

    if ($type == "Job Seeker")
        $query = "INSERT INTO jobseeker (name,password,email,type,date_of_birth,photo) VALUES ('$name','$password','$email','$type',STR_TO_DATE('$date','%d/%m/%Y'),'$img_name');";
    else if ($type == "Company")
        $query = "INSERT INTO company (name,password,email,type,date_of_establishment,photo) VALUES ('$name','$password','$email','$type',STR_TO_DATE('$date','%d/%m/%Y'),'$img_name');";
    if (mysqli_query($con, $query)) {
        echo "New record created successfully";
        echo "<script type='text/javascript'> document.location = 'login.php'; </script>";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($con);
    }

    mysqli_close($con);
    unset($_POST["register_btn"]);
}
?>