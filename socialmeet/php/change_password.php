<?php
session_start();
include_once 'config.php';

if(isset($_POST['submit'])){
    $old_password = mysqli_real_escape_string($conn, $_POST['old_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $unique_id = $_SESSION['unique_id'];

    if(!empty($old_password) && !empty($new_password) && !empty($confirm_password)){
        $encrypt_old_password = md5($old_password);
        $sql = mysqli_query($conn, "SELECT password FROM users WHERE unique_id={$unique_id}");
        $result = mysqli_fetch_assoc($sql);
        if($result['password'] === $encrypt_old_password){
            if($new_password === $confirm_password){
                $encrypt_new_password = md5($new_password);
                $update_query = mysqli_query($conn, "UPDATE users SET password='{$encrypt_new_password}' WHERE unique_id={$unique_id}");
                if($update_query){
                    echo "Password updated successfully";
                    header("Location: ../profile.php"); //
                    exit();
                } else {
                    echo "Something went wrong. Please try again.";
                }
            } else {
                echo "New password and confirm password should match.";
            }
        } else {
            echo "Invalid old password";
        }
    } else {
        echo "All fields are required";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            background-color: #f0f0f0;
        }

        form {
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type=password] {
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #f0f0f0;
            margin-bottom: 20px;
        }

        input[type=submit], input[type=button] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        input[type=submit]:hover, input[type=button]:hover {
            background-color: #3e8e41;
        }
    </style>
</head>
<body>
    <form action="change_password.php" method="post">
        <label for="old_password">Old Password:</label>
        <input type="password" id="old_password" name="old_password" required>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <input type="submit" name="submit" value="Change Password">
        <input type="button" value="Cancel" onclick="window.location.href='../profile.php'">
    </form>
</body>
</html>
