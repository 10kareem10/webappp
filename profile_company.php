    <?php
    session_start();
    $user_id = $_SESSION['user_id'];
    $con = mysqli_connect("localhost", "root", "12345678", "project");
    $query = "SELECT * FROM company where id=$user_id";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_array($result);

    $name = $row['name'];
    $date_of_establishment = $row['date_of_establishment'];
    $image_src = 'uploads/' . $row['photo'];

    // echo "Name: " . $name . "<br>";
    // echo "Date of Establishment: " . $date_of_establishment . "<br>";
    // echo "<img style='width:70px;height:70px;' src='$image_src' alt='Profile pic'><br>";
    // echo "<a href='edit_profile_company.php'>Edit Profile</a>";

    $query = "SELECT * from staff WHERE companyid=$user_id ";
    $result = mysqli_query($con, $query);
    $num_of_rows = mysqli_num_rows($result);
    $staff_arr = array();

    if ($num_of_rows > 0) {
      while ($row = mysqli_fetch_array($result)) {
        // id  |  name  |  companyid
        // echo $row['name']."<br>";
        array_push($staff_arr, $row['name']);
      }
    }

    // This is the part for adding a user
    if ($_SESSION['user_type'] == "Job Seeker") {
      // echo "Like";
    }

    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Profile</title>

      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="stylee.css"> -->
    
  <link rel="stylesheet" href="stylee.css">
  <script src="https://kit.fontawesome.com/04f955b3c6.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="script.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script type="text/javascript" src="script.js"></script>
    </head>

    <body>

      <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-custom">
        <div class="container">

          <a class="navbar-brand" href="home.php">JobSeeker</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav ms-auto  mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link  mr-auto" aria-current="page" href="home.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active mr-auto" href="#">Profile</a>
              </li>
              <li class="nav-item">
                <a class="nav-link mr-auto" href="logout.php">Logout</a>
              </li>
            </ul>

          </div>

        </div>
      </nav>

      <div class="container">
        <div  style="background-color:white; border-radius: 10px;">
          <div style="margin-left:250px; margin-right: 250px; ">

            <div class="row">
              <h2 style="color: black;" class="mt-5 pt-4 col-4"><?php echo $name ?></h2>
              <img class="mt-5 ms-auto me-5 " src="<?php echo $image_src; ?>" style=" width:120px; height:90px; border-radius:50%;">
            </div>

            

            <h6 class=" ms-1"><?php echo $date_of_establishment ?></h6>
            <form class="pt-1" action="edit_profile_company.php">
              <button class=" btn  btn-success ">Edit Profile</button>
            </form>
            <h4>Staff</h4>
            <div class="scroll">

              <?php
              
              foreach ($staff_arr as $s) {
                echo $s . "<br>";
              }
              ?>
            </div>


          </div>
          <div style="margin-left:60px" class="col-11 rightUp">
        <div id="writeApost">
          <form method="POST">
            <br>
            <input type='text' id="input" placeholder="Write a post" name="write_post" class="form-control">
            <label class="custom-file-upload">
              <input type="file" />
              <i id="uploadPic" class="material-icons">add_a_photo</i>
            </label>
            <input type="submit" class="btn btn-success mt-3 col-3" name="post_btn" id="post_btn" value="Post">
          </form>
        </div>
      </div>


        </div>
        <div class="postsDiv">

              <?php
              displayPosts($con);
              ?>

            </div>
      </div>


      <?php
              //Functions tag

              function isLiked($con){

              }

              function displayPosts($con)
              {
                $user_id = $_SESSION['user_id'];
                $query_post = "SELECT post.id,text,num_likes,company.name,company.photo from post LEFT OUTER JOIN company ON company.id = post.company_id WHERE post.company_id = $user_id";
                $query_comment = "SELECT comments.post_id,text,company.name,company.photo FROM comments LEFT OUTER JOIN company ON company.id = comments.company_id WHERE comments.company_id = $user_id";
                $result_posts = mysqli_query($con, $query_post);
                $result_comments = mysqli_query($con, $query_comment);
                while ($post_row = mysqli_fetch_array($result_posts)) {
                  $post_id = $post_row['id'];
                  $post_text = $post_row['text'];
                  $post_num_likes = $post_row['num_likes'];
                  $poster_name = $post_row['name'];
                  $poster_photo = "uploads/" . $post_row['photo'];
                  echo "<div style='margin-left:60px;' class='col-11 post'>";

          echo "<img src='$poster_photo' width=50 height=50> <h6 id='postName'>$poster_name </h6>";
          echo "<p id='postContent'>$post_text </p>";

          echo "<hr class='divider' />";
          echo "
     <table class='tablereact'> 
     <tr>
     <td>
     <form  method='POST'>
     <i id='liketag' class='fas fa-thumbs-up' id='Tag'>
     <input class='likebtn' type='submit' value='like' name='like'> $post_num_likes</i>
     
     </form> 
  </td>
      
     <form method='POST'>
     <td>
     <i id='commenttag' class='fas fa-comment' id='commentTag'></i>
     <input class='commentbtn' type='submit' value='comment' name='comment_btn'>
     
     <input name='comment_text' id='commentfieldprofile' type='text' class='form-control'>
     <input type='hidden' value='$post_id' name='comment_post_id'>

     
</td>
     </form> 
     
     <div id='clear'></div>
</tr>
  </table></br>";
                  while ($comment_row = mysqli_fetch_array($result_comments)) {
                    $comment_post_id = $comment_row['post_id'];
                    $comment_text = $comment_row['text'];
                    $commenter_name = $comment_row['name'];
                    $commenter_photo = "uploads/" . $comment_row['photo'];
                    if ($comment_post_id == $post_id) {
                       echo "<img src='$commenter_photo' width=50 height=50> <h6 id='postName'> $commenter_name </h6>";
              echo "<div class='commentDiv'> $comment_text</div> ";
              echo "<hr class='divider' />";
                    }
                  }
                  echo "</div>";   //end post div
                  mysqli_data_seek($result_comments, 0);
                }
              }

              ?>


    </body>

    </html>