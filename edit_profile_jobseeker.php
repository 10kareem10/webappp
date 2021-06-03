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
$query = "SELECT * FROM jobseeker where id=$user_id";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);

$name = $row['name'];
$password = $row['password'];
$job_title = $row['job_title'];
$photo = $row['photo'];
$bio = $row['bio'];

?>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-custom">
        <div class="container">

            <a class="navbar-brand" href="#">JobSeeker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle=pse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class=pse navbarpse" id="navbarText">
                <ul class="navbar-nav ms-auto  mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link  mr-auto" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active mr-auto" href="#">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mr-auto" href="#">Logout</a>
                    </li>
                </ul>

            </div>

        </div>
    </nav>
    <div class="container mt-5">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-1 ">
                Change Name: <input class="form-control" type="text" name="name" id="name" value="<?= $name ?>"><br>
            </div>
            <div class="mb-1 ">
                Change Password: <input class="form-control" type="password" name="password" id="password" value="<?= $password ?>"><br>
            </div>
            <div class="mb-1 ">
                Change photo: <input class="form-control" type="file" name="uploaded_img" id="uploaded_img"><br>
            </div>
            <div class="mb-1 ">
                Change Job Title: <input class="form-control" type="text" name="job_title" id="job_title" value="<?= $job_title ?>"><br>
            </div>
            <div class="mb-1 ">
                Change Bio: <input class="form-control" type="text" name="bio" id="bio" value="<?= $bio ?>"><br>
            </div>
            <div class="mb-1 ">
                Add Skills: <input class="form-control" type="text" name="skills" id="skills" placeholder="Enter space separated skills">
            </div>
            <br>
            <input class="btn btn-success mt-3" type="submit" name="submit_btn" id="submit_btn" value="Submit">
        </form>
    </div>
    <?php

    function checkThatSkillNotInSkillsTable($skill)
    {

        $con = mysqli_connect("localhost", "root", "12345678", "project");
        $query = "SELECT * from skill where name='$skill'";
        $result = mysqli_query($con, $query);
        $num_of_rows = mysqli_num_rows($result);
        mysqli_close($con);
        if ($num_of_rows > 0)
            return true;
        return false;
    }

    // Now to update the table 
    $name = $_POST['name'];
    $password = $_POST['password'];
    $job_title = $_POST['job_title'];
    $bio = $_POST['bio'];
    $skills_string = $_POST['skills'];
    $skills = explode(" ", $skills_string);
    $buttonClicked = $_POST['submit_btn'];
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
        // In this loop we check if a space-separated skill is NOT in 
        // the skill table... if its not then we add it 
        foreach ($skills as $skill) {
            if (!checkThatSkillNotInSkillsTable($skill)) {
                if (ctype_space($skill) || $skill == "")
                    continue;
                $query = "INSERT INTO skill (name) values ('$skill')";
                mysqli_query($con, $query);
            }
        }




        // if($imageUpdated)
        //     $query = substr_replace($query," photo='$img_name' ",89,0);
        // echo $query."<br>";
        if ($imageUpdated) {
            $query = "UPDATE jobseeker set name='$name',password='$password',job_title='$job_title',bio='$bio', photo='$img_name' where id=$user_id";
        } else {
            $query = "UPDATE jobseeker set name='$name',password='$password',job_title='$job_title',bio='$bio' where id=$user_id";
        }

        mysqli_query($con, $query);

        // Now we need another query to add the skills to the 
        // jobseeker_skill table
        foreach ($skills as $skill) {
            // Now I want to insert the jobseeker and skill into the
            // jobseeker_skill table
            // to do this I need to first check that the current jobseeker
            // does not have this skill already listed with his id in the
            // jobseeker_skill table. so wee need to:
            // 1- get this skill's id (we already have the jobseeker's id)
            // 2- check if the entry is already made in the table
            //      -> if not... add it (insert)
            //      -> if so... skip

            // step 1
            $query = "SELECT id from skill where name='$skill'";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_array($result);
            $skillid = $row['id'];

            // step 2
            $query = "SELECT * from jobseeker_skill where jobseeker_id=$user_id AND skill_id=$skillid";
            $num_of_rows = mysqli_num_rows(mysqli_query($con, $query));
            if ($num_of_rows == 0) {
                $query = "INSERT into jobseeker_skill (jobseeker_id,skill_id) values ($user_id,$skillid)";
                mysqli_query($con, $query);
            }
        }

        header("Location: profile_jobseeker.php");
    }

    ?>

</body>

</html>