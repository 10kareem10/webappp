<!DOCTYPE html>
<html lang="en">

<head>
       <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>
<?php

session_start();
$user_id = $_SESSION['user_id'];
$con = mysqli_connect("localhost", "root", "12345678", "project");
$query = "SELECT * FROM company where id=$user_id";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);

$name = $row['name'];
$password = $row['password'];
$photo = $row['photo'];

?>

<body>

        <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-custom">
        <div class="container">
  
    <a class="navbar-brand" href="#">JobSeeker</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav ms-auto  mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link  mr-auto" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active mr-auto"  href="#">Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link mr-auto"  href="#">Logout</a>
        </li>
      </ul>
      
    </div>
  
</div>
</nav>

    <div class="container mt-5"> 
    <form method="POST" enctype="multipart/form-data">
        Change Name: <input class="form-control" type="text" name="name" id="name" value="<?= $name ?>"><br>
        Change Password: <input class="form-control" type="password" name="password" id="password" value="<?= $password ?>"><br>
        Change photo: <input class="form-control" type="file" name="uploaded_img" id="uploaded_img"><br>
        Add Staff: <input  class="form-control" type="text" name="staff" id="staff" placeholder="Enter comma separated staff"><br>
        <input  class="btn btn-success mt-3 col-3" type="submit" name="submit_btn" id="submit_btn" value="Submit">
    </form>
</div>
    <?php

    $name = $_POST['name'];
    $password = $_POST['password'];
    $buttonClicked = $_POST['submit_btn'];
    $staff = explode(",", $_POST['staff']);
    $imageUpdated = false;

    if (!file_exists($_FILES['uploaded_img']['tmp_name']) || !is_uploaded_file($_FILES['uploaded_img']['tmp_name'])) {
        $imageUpdated = false;
    } else {
        $imageUpdated = true;
        $img_name = $_FILES['uploaded_img']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["uploaded_img"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $extensions_arr = array("jpg", "jpeg", "png", "gif");
        if (in_array($imageFileType, $extensions_arr))
            move_uploaded_file($_FILES['uploaded_img']['tmp_name'], $target_dir . $img_name);
    }

    if (isset($buttonClicked)) {

        // insert staff into staff table
        if (count($staff) > 0) {
            foreach ($staff as $s) {
                if (ctype_space($s) || $s == "")
                    continue;
                $query = "INSERT INTO staff (name,companyid) values('$s',$user_id)";
                mysqli_query($con, $query);
            }
        }

        // now update company data
        if ($imageUpdated)
            $query = "UPDATE company set name='$name',password='$password', photo='$img_name' where id=$user_id";
        else
            $query = "UPDATE company set name='$name',password='$password' where id=$user_id";

        mysqli_query($con, $query);

        header("Location: profile_company.php");
    }

    ?>


</body>

</html>