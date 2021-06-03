<?php

function uploadImgPost($imgID){
$imageUpdated = false;

    if (!file_exists($_FILES[$imgID]['tmp_name']) || !is_uploaded_file($_FILES[$imgID]['tmp_name'])) {
        return false;
    } else {
        $imageUpdated = true;
        $img_name = $_FILES[$imgID]['name'];
        $target_dir = "postImages/";
        $target_file = $target_dir . basename($_FILES[$imgID]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $extensions_arr = array("jpg", "jpeg", "png", "gif");
        if (in_array($imageFileType, $extensions_arr)){
            move_uploaded_file($_FILES[$imgID]['tmp_name'], $target_dir . $img_name);
            return true;
        }
    }
    return false;
}

?>