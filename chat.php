<!DOCTYPE html>




<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="chat.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script src="https://kit.fontawesome.com/04f955b3c6.js" crossorigin="anonymous"></script>


    <title>Chat</title>
</head>

<body>
 





<?php
session_start();
$con = mysqli_connect("localhost", "root", "12345678", "project");

$sender_id = $_SESSION['user_id'];
$sender_type = $_SESSION['user_type'];

$receiver_name = $_SESSION['receiver_name'];
$receiver_id = $_SESSION['receiver_id'];
$receiver_type = $_SESSION['receiver_type'];
echo "<div  id='name'>
		
        <a  id='home' href='home.php'><i class='fas fa-arrow-alt-circle-left'></i></a> 
 <h6  id='recieverName'> $receiver_name </h6> 
  </div> 
";
displayOldMessages($con);

if (isset($_POST['send_btn'])) {
    addMessage($con);
    $URL = "http://localhost/project/chat.php";
    echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
    echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
    displayOldMessages($con);
}




?>
    <form method="POST">
    		<div class=" ps-5 col-4 ">
        <input id="txt" class=" form-control" type="text" name="sent_message" placeholder="Enter Your Message">
    </div>
        <input id="buton" class="btn " type="submit" value="Send" name="send_btn">
    </form>

    <?php
    // Function tag

    function addMessage($con)
    {
        $sender_id = $_SESSION['user_id'];
        $sender_type = $_SESSION['user_type'];
        $receiver_id = $_SESSION['receiver_id'];
        $receiver_type = $_SESSION['receiver_type'];

        $message_text = $_POST['sent_message'];

        $query = "INSERT INTO messages (text,sender_id,receiver_id,sender_type,receiver_type)
         VALUES ('$message_text',$sender_id,$receiver_id,'$sender_type','$receiver_type');";

        mysqli_query($con, $query);
    }


    function displayOldMessages($con)
    {

        $sender_id = $_SESSION['user_id'];
        $sender_type = $_SESSION['user_type'];
        $receiver_id = $_SESSION['receiver_id'];
        $receiver_type = $_SESSION['receiver_type'];
        $query = "SELECT * FROM messages WHERE (sender_id = $sender_id or sender_id = $receiver_id) and (sender_type = '$sender_type' or sender_type = '$receiver_type') and (receiver_id = $receiver_id or receiver_id = $sender_id) and (receiver_type = '$receiver_type' or receiver_type = '$sender_type')";
        $result = mysqli_query($con, $query);
        while ($row = mysqli_fetch_array($result)) {
            $current_message = $row['text'];
            if ($row['sender_id'] == $sender_id) {
                // echo $row['text']."<br>";
                echo "<p id='msg' >$current_message</p>";
            } else {
                echo "<p id='msg1' >$current_message</p>";
            }
        }
    }
    ?>



</body>

</html>