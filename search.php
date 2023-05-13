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

// Define variables and initialize with empty values
$search = "";
$search_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate search term
    if(empty(trim($_POST["search"]))){
        $search_err = "Please enter a search term.";
    } else{
        $search = trim($_POST["search"]);
    }
    
    // Check input errors before searching in database
    if(empty($search_err)){
        
        // Prepare a select statement
        $sql = "SELECT * FROM products WHERE name LIKE ? OR description LIKE ?";
         
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $param_search = "%{$search}%";
            $stmt->bind_param("ss", $param_search, $param_search);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                $result = $stmt->get_result();
                
                // Check if any products were found
                if(mysqli_num_rows($result) > 0){
                    // Display search results
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                        echo "<p><strong>" . $row["name"] . "</strong></p>";
                        echo "<p>" . $row["description"] . "</p>";
                        echo "<hr>";
                    }
                } else{
                    // No products found
                    echo "<p>No products found.</p>";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection
    $mysqli->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search for Friends</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Search for Friends</h2>
        <p>Please enter a name to search.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($search_err)) ? 'has-error' : ''; ?>">
                <label>Search:</label>
                <input type="text" name="search" class="form-control" value="<?php echo $search; ?>">
                <span class="help-block"><?php echo $search_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Search">
            </div>
        </form>
    </div>    
</body>
</html>