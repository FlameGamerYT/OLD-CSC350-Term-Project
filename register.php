<?php
// Initialize the session
session_start();
require_once 'database.php';
$mysqli = $connection;

// Check if the connection is successful
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// Check if the user is already logged in, if yes then redirect him to profile page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: profile.php");
    exit;
}
 
// Include database config file
require_once "database.php";
 
// Define variables and initialize with empty values
$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter your name.";
    } else{
        $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a valid email.";
    } else{
    // Prepare a select statement
    $sql = "SELECT id FROM users WHERE email = ?";
        
    if($stmt = $mysqli->prepare($sql)){
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("s", $param_email);
            
    // Set parameters
    $param_email = trim($_POST["email"]);
            
    // Attempt to execute the prepared statement
    if($stmt->execute()){
    // store result
    $stmt->store_result();
             
    if($stmt->num_rows == 1){
        $email_err = "This email is already taken.";
    } else{
        $email = trim($_POST["email"]);
    }
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt->close();
    }
    }
    
// Validate password
if (empty(trim($_POST["password"]))) {
    $password_err = "Please enter a password.";     
} elseif (strlen(trim($_POST["password"])) < 6) {
    $password_err = "Password must have at least 6 characters.";
} else {
    $password = trim($_POST["password"]);
}

// Validate confirm password
if (empty(trim($_POST["confirm_password"]))) {
    $confirm_password_err = "Please confirm password.";     
} else {
    $confirm_password = trim($_POST["confirm_password"]);
    // Check if password and confirm password match
    if (empty($password_err) && ($password !== $confirm_password)) {
        $confirm_password_err = "Passwords did not match.";
    }
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check input errors before inserting in database
if (empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
    // Prepare an insert statement
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        // Set parameters
        $param_email = $email;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to login page
            header("location: login.php");
            exit;
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }

    // Close statement
    $stmt->close();
}

// Close connection
$mysqli->close();
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo isset($email) ? $email : ''; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo isset($name) ? $name : ''; ?>">
                <span class="help-block"><?php echo isset($name_err) ? $name_err : ''; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo isset($password) ? $password : ''; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo isset($confirm_password) ? $confirm_password : ''; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>