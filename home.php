<?php
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 'On');
$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
$con = mysqli_connect("localhost", "root", "12345678", "project");

$table = "";
if ($user_type == "Company")
  $table = "company";
else $table = "jobseeker";

$query = "SELECT * FROM $table where id=$user_id";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);

$name = $row['name'];
$job_title = $row['job_title'];
$bio = $row['bio'];


$image_src = 'uploads/' . $row['photo'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
  <link rel="stylesheet" href="stylee.css">
  <link rel="stylesheet" href="post.css">
  <script src="https://kit.fontawesome.com/04f955b3c6.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="script.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script type="text/javascript" src="script.js"></script>



</head>


<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">

      <a class="navbar-brand" style="color:teal;" href="home.php">JobSeeker</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav  ms-auto ">
          <li class="nav-item">
            <a class="nav-link active mr-auto" aria-current="page" href="home.php">Home</a>
          </li>
          <li class="nav-item">

            <?php

            if (!isset($_SESSION['user_id'])) {
              $URL = "http://localhost/project/login.php";
              echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
              echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
            }

            if ($_SESSION['user_type'] == "Job Seeker")

              echo "<a class='nav-link mr-auto' href='profile_jobseeker.php'>Profile</a>";
            else if ($_SESSION['user_type'] == "Company")
              echo "<a class='nav-link mr-auto' href='profile_company.php'>Profile</a>";
            else
              echo "Something went wrong fe esm el user type";

            ?>
          </li>

          <li class="nav-item">
            <a class="nav-link mr-auto" href="logout.php">Logout</a>
          </li>

        </ul>

      </div>

    </div>
  </nav>


  <div class="container">
    <div class="d-none d-lg-block col-2 mt-4 info">
      <div>
        <div class="row">
          <img class="mt-3 avatar" src="<?php echo $image_src; ?>">
        </div>

        <div class="row">
          <h6 id="name"> <?php echo $name ?> </h6>
        </div>

        <h6 id="job"> <?php echo $job_title ?> </h6>
        <hr>

        <div class="row">
          <p id="bio"> <?php echo $bio ?> </p>
        </div>

      </div>
    </div>

    <div class=" d-flex flex-column-reverse">



      <?php
      //Function tag

      function addLike($con){

        $table = "";
        $user_id = $_SESSION['user_id'];
        $post_id = $_POST['like_post_id'];
        $num_likes = $_POST['like_num_likes']+1;
        if ($_SESSION['user_type'] == "Company")
          $table = "company_id";
        else $table = "jobseeker_id";

        $insert_query = "INSERT INTO post_likes (post_id,$table) VALUES ($post_id,$user_id)";
        mysqli_query($con,$insert_query);

        $update_query = "UPDATE post SET num_likes = $num_likes WHERE id=$post_id";
        mysqli_query($con,$update_query);
        echo "TEEEEST".$num_likes."<br>";
      }

      function deleteLike($con){

        $table = "";
        $user_id = $_SESSION['user_id'];
        $post_id = $_POST['like_post_id'];
        $num_likes = $_POST['like_num_likes']-1;
        if ($_SESSION['user_type'] == "Company")
          $table = "company_id";
        else $table = "jobseeker_id";

        $delete_query = "DELETE FROM post_likes WHERE post_id = $post_id AND $table = $user_id";
        mysqli_query($con,$delete_query);

        $update_query = "UPDATE post SET num_likes = $num_likes WHERE id=$post_id";
        mysqli_query($con,$update_query);
        echo $num_likes."<br>";
      }

      function isLiked($con)
      {

        $table = "";
        $user_id = $_SESSION['user_id'];
        $post_id = $_POST['like_post_id'];
        if ($_SESSION['user_type'] == "Company")
          $table = "company_id";
        else $table = "jobseeker_id";

        $query = "SELECT * FROM post_likes WHERE $table = $user_id AND post_id = $post_id";
        $result = mysqli_query($con, $query);


        if (mysqli_num_rows($result) > 0) {
          return true;
        } else return false;
      }

      function displayUsers($con)
      {

        $query_js = "SELECT * FROM jobseeker";
        $query_comp = "SELECT * FROM company";

        $result_js = mysqli_query($con, $query_js);
        $result_comp = mysqli_query($con, $query_comp);

        while ($row = mysqli_fetch_array($result_js) or $row = mysqli_fetch_array($result_comp)) {
          $id = $row['id'];
          $name = $row['name'];
          $type = $row['type'];
          // if($row['type'] == "Company")
          // $type = "company";
          // else $type="jobseeker";

      ?>
          <form method="POST">
            <input class="contactlistname" type="submit" name="go_to_chat_btn" value="<?php echo "$name"; ?>">
            <input type="hidden" name="go_to_chat_type" value="<?php echo "$type"; ?>">
            <input type="hidden" name="go_to_chat_id" value="<?php echo $id; ?>">
            <input type="hidden" name="go_to_chat_name" value="<?php echo $name; ?>">

          </form>
        <?php
        }
      }

      function displayPosts($con)
      {
        // Data we need for each post
        // js/comp.username - js/comp.img - post.text - post.likes

        $query1 = "SELECT post.image,post.id,post.text,post.num_likes,
          (CASE 
          WHEN post.jobseeker_id IS NULL THEN company.name
          ELSE jobseeker.name
          END) as name,
          (CASE 
          WHEN post.jobseeker_id IS NULL THEN company.photo
          ELSE jobseeker.photo
          END) as photo
          FROM post
          LEFT OUTER JOIN jobseeker ON jobseeker.id = post.jobseeker_id 
          LEFT OUTER JOIN company ON company.id = post.company_id
          WHERE jobseeker.id = post.jobseeker_id OR company.id = post.company_id
          GROUP BY post.id
          ORDER BY post.id ASC;";




        $query2 =
          "SELECT comments.post_id,comments.text,
          (CASE 
          WHEN comments.jobseeker_id IS NULL THEN company.name
          ELSE jobseeker.name
          END) as name,
          (CASE 
          WHEN comments.jobseeker_id IS NULL THEN company.photo
          ELSE jobseeker.photo
          END) as photo
          FROM comments
          LEFT OUTER JOIN jobseeker ON jobseeker.id = comments.jobseeker_id 
          LEFT OUTER JOIN company ON company.id = comments.company_id
          WHERE jobseeker.id = comments.jobseeker_id OR company.id = comments.company_id
          GROUP BY comments.id";




        $query3 = "SELECT post_likes.post_id as post_id,
          (CASE 
          WHEN post_likes.jobseeker_id IS NULL THEN company.name
          ELSE jobseeker.name
          END) as name
          FROM post_likes
          LEFT OUTER JOIN jobseeker ON jobseeker.id = post_likes.jobseeker_id 
          LEFT OUTER JOIN company ON company.id = post_likes.company_id
          WHERE jobseeker.id = post_likes.jobseeker_id OR company.id = post_likes.company_id;
          ";

        $result_posts = mysqli_query($con, $query1);
        $result_comments = mysqli_query($con, $query2);
        $result_likes = mysqli_query($con, $query3);

        while ($post_row = mysqli_fetch_array($result_posts)) {

          $post_id = $post_row['id'];
          $post_text = $post_row['text'];
          $post_num_likes = $post_row['num_likes'];
          $poster_name = $post_row['name'];
          $poster_photo = "uploads/" . $post_row['photo'];
          $post_img = $post_row['image'];
          echo "<div class='col-9 post'>";

          echo "<img class='pstavatar' src='$poster_photo' width=50 height=50> <h6 id='postName'>$poster_name </h6>";
          echo "<p id='postContent'>$post_text </p>";
          if($post_img !== null)
            echo "<img class='postImg' src='$post_img'>";
        ?>
          <hr class='divider' />

          <table class='tablereact'>
            <tr>
              <td>
                <form method='POST'>
                  <i id='liketag' class='fas fa-thumbs-up' id='Tag'>
                    <input class='likebtn' type='submit' value='like' name='like_btn'><?php echo $post_num_likes; ?></i>
                  <input type="hidden" name="like_post_id" value="<?php echo $post_id; ?>">
                  <input type="hidden" name="like_num_likes" value="<?php echo $post_num_likes; ?>">

                </form>
              </td>

              <form method='POST'>
                <td>
                  <i id='commenttag' class='fas fa-comment' id='commentTag'></i>
                  <input class='commentbtn' type='submit' value='comment' name='comment_btn'>

                  <input name='comment_text' id='commentfield' type='text' class='form-control' required>
                  <input type='hidden' value="<?php echo $post_id; ?>" name='comment_post_id'>
                  


                </td>
              </form>

              <div id='clear'></div>
            </tr>
          </table></br>

      <?php
          echo "<h6 id='comments'> comments </h6>";
          /*<input type='text' class='form-control'>*/
          while ($comment_row = mysqli_fetch_array($result_comments)) {
            $comment_post_id = $comment_row['post_id'];
            $comment_text = $comment_row['text'];
            $commenter_name = $comment_row['name'];
            $commenter_photo = "uploads/" . $comment_row['photo'];
            if ($comment_post_id == $post_id) {
              echo "<img id='smAv' src='$commenter_photo' width=50 height=50> <h6 id='pstName'> $commenter_name </h6>";
              echo "<div class='commentDiv'> $comment_text</div> ";
              echo "<hr class='divider' />";

            }
          }

          echo "</div>";  //close bigDiv
          mysqli_data_seek($result_comments, 0);
        }
      }

      function doPost($con)
      {

        $user_id = $_SESSION['user_id'];
        $user_type = $_SESSION['user_type'];
        $table = "";

        if ($user_type == "Company")
          $table = "company_id";
        else $table = "jobseeker_id";
        $post_text = $_POST['write_post'];
        $post_image_id = "uploaded_post_photo";
        include 'uploadImgSimple.php';
        $img_uploaded = uploadImgPost($post_image_id);
        $uploaded_image_path = "postImages/".$_FILES[$post_image_id]['name'];
        $query = "INSERT INTO post (text,num_likes,$table,image)
        VALUES ('$post_text',0,$user_id,'$uploaded_image_path');";

        mysqli_query($con, $query);
      }

      function doComment($con)
      {

        $comment_text = $_POST['comment_text'];
        $post_id = $_POST['comment_post_id'];
        $user_id = $_SESSION['user_id'];
        $user_type = $_SESSION['user_type'];

        $table = "";
        if ($user_type == "Company")
          $table = "company_id";
        else $table = "jobseeker_id";

        echo $post_id;

        $query = "INSERT INTO comments (text,post_id,$table) VALUES ('$comment_text',$post_id,$user_id)";
        mysqli_query($con, $query);
      }



      $con = mysqli_connect("localhost", "root", "12345678", "project");
      displayPosts($con);

      if (isset($_POST['post_btn'])) {
        doPost($con);
        $URL = "http://localhost/project/home.php";
        echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
        exit();
      }

      if (isset($_POST['comment_btn'])) {
        doComment($con);
        $URL = "http://localhost/project/home.php";
        echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
      }
      ?>
      <div class="col-9 rightUp">
        <div id="writeApost">
          <form method="POST" enctype="multipart/form-data">
            <br>
            <input type='text' id="input" placeholder="Write a post" name="write_post" class="form-control" required>
            <label class="custom-file-upload">
              <input type="file" name="uploaded_post_photo">
              <i id="uploadPic" class="material-icons">add_a_photo</i>
            </label>
            <input type="submit" class="btn btn-success mt-3 col-3" name="post_btn" id="post_btn" value="Post">
          </form>
        </div>
      </div>
    </div>
    <div id="clear"></div>
  </div>
  </div>
  <div id="topcorner" class="d-none d-lg-block col-2 mt-4 info">
    <div>

      <div class="row">
        <h5>Contacts</h5>
      </div>

      <hr>
      <div class="contactlist">
        <?php
        displayUsers($con);

        if (isset($_POST['like_btn'])) {
          // To implement like button
          // How it works
          // 1-Check if already liked
          //    if not --> INSERT INTO post_likes
          //           --> increment num_likes in post (another insert?)
          //    if so  --> delete like entry
          //           --> Decrement num_likes
          
          echo "I'm here man";
          if(isLiked($con)){
            deleteLike($con);
            $URL = "http://localhost/project/home.php";
            echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
            echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';

          }else if(!isLiked($con)){
            addLike($con);
            $URL = "http://localhost/project/home.php";
            echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
            echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
          }

        }

        if (isset($_POST['go_to_chat_btn'])) {
          $_SESSION['receiver_type'] = $_POST['go_to_chat_type'];
          $_SESSION['receiver_id'] = $_POST['go_to_chat_id'];
          $_SESSION['receiver_name'] = $_POST['go_to_chat_name'];
          $URL = "http://localhost/project/chat.php";
          echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
          echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
        }
        ?>

      </div>
    </div>
  </div>

</body>

</html>