<?php
session_start();
include_once 'config.php';

if(isset($_FILES['image'])){
    $img_name = $_FILES['image']['name'];
    $img_type = $_FILES['image']['type'];
    $tmp_name = $_FILES['image']['tmp_name'];
    
    $img_explode = explode('.',$img_name);
    $img_ext = end($img_explode);

    $extensions = ["jpeg", "png", "jpg"];
    if(in_array($img_ext, $extensions) === true){
        $types = ["image/jpeg", "image/jpg", "image/png"];
        if(in_array($img_type, $types) === true){
            $time = time();
            $new_img_name = $time.$img_name;
            if(move_uploaded_file($tmp_name,"../php/images/".$new_img_name)){
                $update_query = mysqli_query($conn, "UPDATE users SET img='{$new_img_name}' WHERE unique_id={$_SESSION['unique_id']}");
                if($update_query){
                    header("location: ../profile.php");
                }else{
                    echo "Something went wrong. Please try again!";
                }
            }
        }else{
            echo "Please upload an image file - jpeg, png, jpg";
        }
    }else{
        echo "Please upload an image file - jpeg, png, jpg";
    }
}
?>
